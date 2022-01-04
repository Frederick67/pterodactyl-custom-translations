<?php

namespace Pterodactyl\Models\Billing;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Pterodactyl\Models\Billing\PteroAPI\PteroAPI;
use Pterodactyl\Models\Billing\BillingPlans;
use Pterodactyl\Models\Billing\BillingInvoices;
use Pterodactyl\Models\User;
use Pterodactyl\Models\Billing\BillingLogs;
use Pterodactyl\Models\Billing\BLang;

class BillingSettings extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'data'];

    public static function getAll()
    {
      $settings = array();
      if (count(self::get())) {
        foreach (self::get() as $value) {
          if (isset($value->name)) {
            $settings[$value->name] = $value->data;
          }
        }
      }
      return $settings;
    }

    public static function getParam($param)
    {
      $settings = self::getAll();
      if (isset($settings[$param])) {
        return $settings[$param]; 
      } else {
        return '';
      }
      
    }

    public static function getAllEggs()
    {
      if (!empty(self::getParam('api_key'))) {
        $url = request()->getSchemeAndHttpHost();
        $api = self::getParam('api_key');
        $api = new PteroAPI($api, $url);
  
        $nests = $api->nests->getAll();
        $nests_ids = array();
        if (isset($nests['data'])) {
          foreach ($nests['data'] as $nest) {
            $nests_ids[$nest['attributes']['id']] = $nest['attributes']['id'];
          }
        }
  
        $eggs_array = array();
        foreach ($nests_ids as $nest) {
          if (isset($nest)) {
            foreach ($api->eggs->getAll($nest)['data'] as $egg) {
              $eggs_array[$egg['attributes']['id']] = $egg['attributes'];
            }
          }
        }
        return $eggs_array;
      }
    }

    public static function getEgg($id)
    {
      $eggs = self::getAllEggs();
      return $eggs[$id];
    }

    public function setSetting(Request $request)
    {
  
      foreach ($request->input() as $key => $value) {
        $settings = BillingSettings::updateOrCreate(
          [
             'name'   => $key,
          ],
          [
             'name' => $key,
             'data' => $value
          ],
      );
      }
      return redirect()->back();
    }

    // return port
    public static function getPortOrCreateAllocation($node, $exception_id = 0, $exception_port = 0)
    {
      $url = request()->getSchemeAndHttpHost();
      $api = self::getParam('api_key');
      $api = new PteroAPI($api, $url);

      $allocations = $api->allocations->get($node);

      $allocations = $allocations['data'];

      foreach ($allocations as $allocation) {

        $ip = $allocation['attributes']['ip'];

        // Skip bind allocation 
        if ($allocation['attributes']['id'] == $exception_id or $allocation['attributes']['port'] == $exception_port) {
          continue;
        }

        // Checking free ports
        if(!$allocation['attributes']['assigned']){
          $port = $allocation['attributes']['port'];
          break;
        }
      }

      // Create new allocation
      if(!isset($port)){
        $port = end($allocations)['attributes']['port'] + 1;
        $param = array(
          "ip" => $ip,
          "ports" => array (
            $port
          )
        );
        $api->allocations->create($node, $param);
      }
      return $port;
    }

    public static function getAllocationWherePort($node_id, $port){
      $url = request()->getSchemeAndHttpHost();
      $api = self::getParam('api_key');
      $api = new PteroAPI($api, $url);

      $allocations = $api->allocations->get($node_id);
      $allocations = $allocations['data'];

      foreach ($allocations as $allocation) {

        if ($allocation['attributes']['port'] == $port) {
          return $allocation['attributes'];
        }
      }
    }

    public static function createServer($user_id, $plan_id)
    {
      $url = request()->getSchemeAndHttpHost();
      $api = self::getParam('api_key');
      $api = new PteroAPI($api, $url);

      $plan = BillingPlans::find($plan_id);
      $egg = self::getEgg($plan->egg);
      $user = User::find($user_id);

      $server_port = self::getPortOrCreateAllocation($plan->node);

      $allocation = $api->allocations->get($plan->node)['data'];

      foreach ($allocation as $loc) {
        if ($loc['attributes']['port'] == $server_port) {
          $allocation_id = $loc['attributes']['id'];
          break;
        }
      }

      $environment = json_decode($plan->variable, true);
      $env = [];
      $rcon_port = self::getPortOrCreateAllocation($plan->node, $allocation_id);
      foreach ($environment as $value) {
        foreach ($value as $key => $val) {
          if ($key == 'RCON_PORT') {
            $env[$key] = $rcon_port;
            continue;
          }
          if ($key == 'APP_PORT') {
            $env[$key] = self::getPortOrCreateAllocation($plan->node, $allocation_id, $rcon_port);
            continue;
          }
          $env[$key] = $val;
        }
      }

      $allocations_ids['default'] = $allocation_id;
      if (isset($env['RCON_PORT'])) {
        $allocations_ids['rcon'] = self::getAllocationWherePort($plan->node, $env['RCON_PORT'])['id'];
      }
      if (isset($env['APP_PORT'])) {
        $allocations_ids['app'] = self::getAllocationWherePort($plan->node, $env['APP_PORT'])['id'];
      }

      $create_params = array(
        "name" => $plan->name,
        "user" => $user->id,
        "egg" => $egg['id'],
        "docker_image" => $egg['docker_image'],
        "startup" => $egg["startup"],
        "environment" => $env,
        "limits" => array(
          "memory" => $plan->memory,
          "swap" => 0,
          "disk" => $plan->disk_space,
          "io" => 100,
          "cpu" => $plan->cpu_limit
        ),
        "feature_limits" => array(
          "databases" => $plan->database_limit,
          "backups" => $plan->backup_limit,
          "allocations" => $plan->allocation_limit
        ),
        "allocation" => $allocations_ids
      );
      
      $server = $api->servers->create($create_params);
      if (!isset($server['errors'])) {
        $invoice = new BillingInvoices;
        $invoice->user_id = $user->id;
        $invoice->plan_id = $plan->id;
        $invoice->server_id = $server['attributes']['id'];
        $dt = date("Y-m-d");
        $invoice->invoice_date = $dt;
        $invoice->due_date = date("Y-m-d", strtotime("{$dt} +{$plan->days} day"));
        $invoice->status = 'Paid';
        $invoice->save();
        BillingLogs::setInvoiceLog($plan->price, '-', $invoice->id, $user->id);

        // $api->network->set($server['attributes']['identifier'], self::getAllocationWherePort($plan->node, $env['RCON_PORT'])['id'], ['notes' => 'RCON_PORT']);
        // $api->network->set($server['attributes']['identifier'], self::getAllocationWherePort($plan->node, $env['APP_PORT'])['id'], ['notes' => 'APP_PORT']);
        
        return true;
      } else {
        dd($server);
      }
    }

    public static function scheduler()
    {
      $url = request()->getSchemeAndHttpHost();
      $api = self::getParam('api_key');
      $api = new PteroAPI($api, $url);
      $dt = date("Y-m-d");
      $invoices = BillingInvoices::where('due_date', '<', $dt)->get();
      if (!empty($invoices)) {
        foreach ($invoices as $invoice) {
          try {
            $api->servers->suspend($invoice->server_id);
          } catch (Exception $e) {
            // contine
          }
          
          $invoice = BillingInvoices::find($invoice->id);
          $invoice->status = 'Unpaid';
          $invoice->save();
        }
      }
      return 'done';
    }

    public static function getNodes($id = 0)
    {
      $url = request()->getSchemeAndHttpHost();
      $api = self::getParam('api_key');
      $api = new PteroAPI($api, $url);
      if ($id == 0) {
        return $api->node->getAll();
      }
      return $api->node->get($id);
      
    }

    public static function getCustomPages()
    {
      return DB::table('custom_pages')->get();
    }
}
