<?php

namespace Pterodactyl\Http\Controllers\Billing;

use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Models\Billing\BillingUsers;
use Pterodactyl\Models\Billing\BillingSettings;
use Pterodactyl\Models\Billing\BillingInvoices;
use Pterodactyl\Models\Billing\BillingPlans;
use Pterodactyl\Models\Billing\PteroAPI\PteroAPI;
use Pterodactyl\Models\Billing\BillingLogs;
use Pterodactyl\Models\Billing\BLang;

class InvoicesController extends Controller
{

  public $template = 'Default';

  public function __construct()
  {
    if (!empty(config('billing.theme'))){
      $this->template = config('billing.theme');
    }
    BillingSettings::scheduler();
  }
  
  public function index()
  {
    
    $billding_user = new BillingUsers;
    $billding_user = $billding_user->getAuth();
    $plans = new BillingPlans;
    $invoices = BillingInvoices::where('user_id', $billding_user->user_id)->get();
    return view('templates.' . $this->template . '.billing.invoices', ['plans' => $plans->getArrayKeyData(),'invoices' => $invoices,'billding_user' => $billding_user, 'settings' => BillingSettings::getAll()]);
  }

  public function view($id)
  {
    $billding_user = new BillingUsers;
    $billding_user = $billding_user->getAuth();
    $invoice = BillingInvoices::find($id);
    if (!isset($invoice) or $invoice->user_id != $billding_user->user_id) {
      return redirect()->back();
    }

    $invoice_logs = BillingLogs::getInvoiceLog($id, $billding_user->user_id);
    $plan = BillingPlans::find($invoice->plan_id);
    return view('templates.' . $this->template . '.billing.view-invoice', ['invoice_logs' => $invoice_logs, 'plan' => $plan, 'invoice' => $invoice, 'billding_user' => $billding_user, 'settings' => BillingSettings::getAll()]);
  }


  public function orderUpdate($id)
  {
    $invoice = BillingInvoices::find($id);
    $user = new BillingUsers;
    $user = $user->getAuth();

    if ($invoice->user_id != $user->user_id) {
      return redirect()->back();
    }

    if (!empty($plan = BillingPlans::find($invoice->plan_id))) {
      if ($user->balance >= $plan->price) {

        $dt = date("Y-m-d");

        $url = request()->getSchemeAndHttpHost();
        $api = BillingSettings::getParam('api_key');
        $api = new PteroAPI($api, $url);

        if ($invoice->due_date >= $dt) {
          $invoice->due_date = date("Y-m-d", strtotime("{$invoice->due_date} +{$plan->days} day"));
          $api->servers->unsuspend($invoice->server_id);
        } else {
          $invoice->due_date = date("Y-m-d", strtotime("{$dt} +{$plan->days} day"));
          $api->servers->unsuspend($invoice->server_id);
        }
        $user->editBalance($plan->price, '-', $invoice->id);
        $invoice->status = 'Paid';
        $invoice->save();
      } else {
        return redirect()->back()->withErrors(BLang::get('err_user_balance'));
      }
    } else {
      return redirect()->back()->withErrors(BLang::get('err_plan_exist'));
    }
    return redirect()->back();
  }

  public function invoiceDelete($id)
  {
    $invoice = BillingInvoices::find($id);
    $user = new BillingUsers;
    $user = $user->getAuth();

    if ($invoice->user_id != $user->user_id) {
      return redirect()->back();
    }
    $url = request()->getSchemeAndHttpHost();
    $api = BillingSettings::getParam('api_key');
    $api = new PteroAPI($api, $url);

    $api->servers->delete($invoice->server_id);
    $invoice->delete();
    return redirect()->back();
  }
}
