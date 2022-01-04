<?php

namespace Pterodactyl\Http\Controllers\Billing\Admin;

use Pterodactyl\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Pterodactyl\Models\Billing\BillingSettings;
use Pterodactyl\Models\Billing\BillingGames;
use Pterodactyl\Models\Billing\BillingPlans;
use Pterodactyl\Models\Billing\BillingUsers;
use Pterodactyl\Models\Billing\BillingLogs;
use Pterodactyl\Models\Billing\BillingInvoices;
use Pterodactyl\Models\Billing\BLang;

class AdminCoreController extends Controller
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
    $currency_list = array(
      'ALL' => 'Albania Lek',
      'AFN' => 'Afghanistan Afghani',
      'ARS' => 'Argentina Peso',
      'AWG' => 'Aruba Guilder',
      'AUD' => 'Australia Dollar',
      'AZN' => 'Azerbaijan New Manat',
      'BSD' => 'Bahamas Dollar',
      'BBD' => 'Barbados Dollar',
      'BDT' => 'Bangladeshi taka',
      'BYR' => 'Belarus Ruble',
      'BZD' => 'Belize Dollar',
      'BMD' => 'Bermuda Dollar',
      'BOB' => 'Bolivia Boliviano',
      'BAM' => 'Bosnia and Herzegovina Convertible Marka',
      'BWP' => 'Botswana Pula',
      'BGN' => 'Bulgaria Lev',
      'BRL' => 'Brazil Real',
      'BND' => 'Brunei Darussalam Dollar',
      'KHR' => 'Cambodia Riel',
      'CAD' => 'Canada Dollar',
      'KYD' => 'Cayman Islands Dollar',
      'CLP' => 'Chile Peso',
      'CNY' => 'China Yuan Renminbi',
      'COP' => 'Colombia Peso',
      'CRC' => 'Costa Rica Colon',
      'HRK' => 'Croatia Kuna',
      'CUP' => 'Cuba Peso',
      'CZK' => 'Czech Republic Koruna',
      'DKK' => 'Denmark Krone',
      'DOP' => 'Dominican Republic Peso',
      'XCD' => 'East Caribbean Dollar',
      'EGP' => 'Egypt Pound',
      'SVC' => 'El Salvador Colon',
      'EEK' => 'Estonia Kroon',
      'EUR' => 'Euro Member Countries',
      'FKP' => 'Falkland Islands (Malvinas) Pound',
      'FJD' => 'Fiji Dollar',
      'GHC' => 'Ghana Cedis',
      'GIP' => 'Gibraltar Pound',
      'GTQ' => 'Guatemala Quetzal',
      'GGP' => 'Guernsey Pound',
      'GYD' => 'Guyana Dollar',
      'HNL' => 'Honduras Lempira',
      'HKD' => 'Hong Kong Dollar',
      'HUF' => 'Hungary Forint',
      'ISK' => 'Iceland Krona',
      'INR' => 'India Rupee',
      'IDR' => 'Indonesia Rupiah',
      'IRR' => 'Iran Rial',
      'IMP' => 'Isle of Man Pound',
      'ILS' => 'Israel Shekel',
      'JMD' => 'Jamaica Dollar',
      'JPY' => 'Japan Yen',
      'JEP' => 'Jersey Pound',
      'KZT' => 'Kazakhstan Tenge',
      'KPW' => 'Korea (North) Won',
      'KRW' => 'Korea (South) Won',
      'KGS' => 'Kyrgyzstan Som',
      'LAK' => 'Laos Kip',
      'LVL' => 'Latvia Lat',
      'LBP' => 'Lebanon Pound',
      'LRD' => 'Liberia Dollar',
      'LTL' => 'Lithuania Litas',
      'MKD' => 'Macedonia Denar',
      'MYR' => 'Malaysia Ringgit',
      'MUR' => 'Mauritius Rupee',
      'MXN' => 'Mexico Peso',
      'MNT' => 'Mongolia Tughrik',
      'MZN' => 'Mozambique Metical',
      'NAD' => 'Namibia Dollar',
      'NPR' => 'Nepal Rupee',
      'ANG' => 'Netherlands Antilles Guilder',
      'NZD' => 'New Zealand Dollar',
      'NIO' => 'Nicaragua Cordoba',
      'NGN' => 'Nigeria Naira',
      'NOK' => 'Norway Krone',
      'OMR' => 'Oman Rial',
      'PKR' => 'Pakistan Rupee',
      'PAB' => 'Panama Balboa',
      'PYG' => 'Paraguay Guarani',
      'PEN' => 'Peru Nuevo Sol',
      'PHP' => 'Philippines Peso',
      'PLN' => 'Poland Zloty',
      'QAR' => 'Qatar Riyal',
      'RON' => 'Romania New Leu',
      'RUB' => 'Russia Ruble',
      'SHP' => 'Saint Helena Pound',
      'SAR' => 'Saudi Arabia Riyal',
      'RSD' => 'Serbia Dinar',
      'SCR' => 'Seychelles Rupee',
      'SGD' => 'Singapore Dollar',
      'SBD' => 'Solomon Islands Dollar',
      'SOS' => 'Somalia Shilling',
      'ZAR' => 'South Africa Rand',
      'LKR' => 'Sri Lanka Rupee',
      'SEK' => 'Sweden Krona',
      'CHF' => 'Switzerland Franc',
      'SRD' => 'Suriname Dollar',
      'SYP' => 'Syria Pound',
      'TWD' => 'Taiwan New Dollar',
      'THB' => 'Thailand Baht',
      'TTD' => 'Trinidad and Tobago Dollar',
      'TRY' => 'Turkey Lira',
      'TRL' => 'Turkey Lira',
      'TVD' => 'Tuvalu Dollar',
      'UAH' => 'Ukraine Hryvna',
      'GBP' => 'United Kingdom Pound',
      'USD' => 'United States Dollar',
      'UYU' => 'Uruguay Peso',
      'UZS' => 'Uzbekistan Som',
      'VEF' => 'Venezuela Bolivar',
      'VND' => 'Viet Nam Dong',
      'YER' => 'Yemen Rial',
      'ZWD' => 'Zimbabwe Dollar'
    );
    return view('templates.' . $this->template . '.billing.admin.index', ['settings' => BillingSettings::getAll(), 'currency_list' => $currency_list]);
  }

  public function setSetting(Request $request)
  {

    foreach ($request->input() as $key => $value) {
      BillingSettings::updateOrCreate(['name' => $key], ['name' => $key, 'data' => $value]);
    }
    return redirect()->back();

  }

  public function games()
  {
    $games = new BillingGames;
    return view('templates.' . $this->template . '.billing.admin.games', ['biling_games' => $games->get()]);
  }

  public function createGame(Request $request)
  {
    $validated = $request->validate([
      'label' => 'required|max:40',
      'link' => 'required',
      'icon' => 'required',
    ]);
    $game = new BillingGames;
    $game->label = $request->input('label');
    $game->link = $request->input('link');
    $game->icon = $request->input('icon');
    $game->save();
    return redirect()->back();
  }

  public function editGame(Request $request)
  {
    $validated = $request->validate([
      'game_id' => 'required',
      'label' => 'required|max:40',
      'link' => 'required',
      'icon' => 'required',
    ]);
    $game = BillingGames::find($request->input('game_id'));
    $game->label = $request->input('label');
    $game->link = $request->input('link');
    $game->icon = $request->input('icon');
    $game->save();
    return redirect()->back();
  }

  public function deleteGame(Request $request)
  {
    $validated = $request->validate([
      'game_id' => 'required',
    ]);
    if (!empty(BillingPlans::where('game_id', $request->input('game_id'))->first())) {
      return redirect()->back()->withErrors(BLang::get('err_remove_game'));
    }
    $game = BillingGames::find($request->input('game_id'));
    $game->delete();
    return redirect()->back();
  }


  public function plans()
  {
    $nodes = BillingSettings::getNodes();
    $eggs = BillingSettings::getAllEggs();
    $plans = new BillingPlans;
    $games = new BillingGames;
    $games_arr = array();
    foreach ($games->get() as $game) {
      $games_arr[$game->id] = $game;
    }
    return view('templates.' . $this->template . '.billing.admin.plans', ['nodes' => $nodes, 'eggs' => $eggs, 'settings' => BillingSettings::getAll(), 'plans' => $plans->get(), 'games' => $games_arr]);
  }

  public function createPlan(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|max:40',
      'price' => 'required',
      'game_id' => 'required',
      'egg' => 'required',
      'memory' => 'required',
      'disk_space' => 'required',
      'node' => 'required',
      'limit' => 'required',
    ]);

    $plan = new BillingPlans;
    $plan->name = $request->input('name');
    $plan->price = $request->input('price');
    $plan->icon = $request->input('icon');
    $plan->cpu_model = $request->input('cpu_model');
    $plan->game_id = $request->input('game_id');
    $plan->egg = $request->input('egg');
    $plan->days = $request->input('days');
    $plan->cpu_limit = $request->input('cpu_limit');
    $plan->memory = $request->input('memory');
    $plan->disk_space = $request->input('disk_space');
    $plan->database_limit = $request->input('database_limit');
    $plan->allocation_limit = $request->input('allocation_limit');
    $plan->backup_limit = $request->input('backup_limit');
    $plan->description = $request->input('description');
    $plan->node = $request->input('node');
    $plan->limit = $request->input('limit');
    if (!empty($request->input('variables'))) {
      $variables = explode(PHP_EOL, $request->input('variables'));
      $variables_data = array();
      foreach ($variables as $value) {
         $arr = explode('=', trim($value));
         $variables_data[] = array(
          trim($arr['0']) => trim($arr['1'])
         );
      }
      $variables_data = json_encode($variables_data);
    }
    $plan->variable = $variables_data;
    $plan->save();
    return redirect()->back();
  }

  public function editPlan(Request $request)
  {
    $validated = $request->validate([
      'plan_id' => 'required',
      'name' => 'required|max:40',
      'price' => 'required',
      'node' => 'required',
      'limit' => 'required',
    ]);

    $plan = BillingPlans::find($request->input('plan_id'));
    $plan->name = $request->input('name');
    $plan->price = $request->input('price');
    $plan->icon = $request->input('icon');
    $plan->cpu_model = $request->input('cpu_model');
    $plan->description = $request->input('description');
    $plan->node = $request->input('node');
    $plan->limit = $request->input('limit');
    if (!empty($request->input('variables'))) {
      $variables = explode(PHP_EOL, $request->input('variables'));
      $variables_data = array();
      foreach ($variables as $value) {
         $arr = explode('=', trim($value));
         $variables_data[] = array(
          trim($arr['0']) => trim($arr['1'])
         );
      }
      $variables_data = json_encode($variables_data);
    }
    $plan->variable = $variables_data;
    $plan->save();
    return redirect()->back();
  }

  public function deletePlan(Request $request)
  {
    $validated = $request->validate([
      'plan_id' => 'required',
    ]);
    $plan = BillingPlans::find($request->input('plan_id'));
    $plan->delete();
    return redirect()->back();
  }


  // Users Function
  public function users()
  {
    $biling_settings = new BillingSettings;
    $biling_users = BillingUsers::getAllUsers();
    return view('templates.' . $this->template . '.billing.admin.users', ['users' => $biling_users, 'settings' => BillingSettings::getAll(), 'biling_settings' => $biling_settings->get()]);
  }

  public function newBalance(Request $request)
  {
    $validated = $request->validate([
      'user_id' => 'required',
      'count' => 'required',
    ]);
    $user = BillingUsers::find($request->input('user_id'));
    $user->editBalance($request->input('count'), '=');
    return redirect()->back();
  }

  public function userInvoices($id)
  {
    $billding_user = BillingUsers::where('user_id', $id)->first();
    $plans = new BillingPlans;
    $invoices = BillingInvoices::where('user_id', $billding_user->user_id)->get();
    return view('templates.' . $this->template . '.billing.admin.users_invoices', ['plans' => $plans->getArrayKeyData(),'invoices' => $invoices,'billding_user' => $billding_user, 'settings' => BillingSettings::getAll()]);
  }

  public function userPayments($id)
  {
    $billding_user = BillingUsers::where('user_id', $id)->first();
    $logs = BillingLogs::where('user_id', $billding_user->user_id)->where('type', 'paypal')->get();
    return view('templates.' . $this->template . '.billing.admin.users_payments', ['logs' => $logs, 'billding_user' => $billding_user, 'settings' => BillingSettings::getAll()]);
  }

  public function alerts()
  {
    return view('templates.' . $this->template . '.billing.admin.alerts', ['settings' => BillingSettings::getAll(),]);
  }

  public function meta()
  {
    return view('templates.' . $this->template . '.billing.admin.meta', ['settings' => BillingSettings::getAll(),]);
  }

  public function update()
  {
    return view('templates.' . $this->template . '.billing.admin.update');
  }

  public function getPages()
  {
    $pages = DB::table('custom_pages')->get();
    return view('templates.' . $this->template . '.billing.admin.pages', ['pages' => $pages]);
  }

  public function createPage()
  {
    return view('templates.' . $this->template . '.billing.admin.edit_pages');
  }

  public function updatePage($id)
  {
    $page = DB::table('custom_pages')->where('id', $id)->first();
    return view('templates.' . $this->template . '.billing.admin.edit_pages', ['page_id' => $id, 'page' => $page]);
  }

  public function savePage(Request $request)
  {
    $validated = $request->validate([
      'url' => 'required',
      'content' => 'required',
    ]);
    
    if (isset($_POST['page_id'])) {
      DB::table('custom_pages')->where('id', $request->input('page_id'))->update(array(
        'url' => $request->input('url'),
        'icon' => $request->input('icon'),
        // 'auth' => (int) $request->input('auth'),
        'auth' => 1,
        'data' => htmlentities($request->input('content')),
      ));
    } else {
      DB::table('custom_pages')->insert(array(
        'url' => $request->input('url'),
        'icon' => $request->input('icon'),
        // 'auth' => (int) $request->input('auth'),
        'auth' => 1,
        'data' => htmlentities($request->input('content')),
      ));
    }

    return redirect()->route('admin.billing.pages');
  }

  public function deletePage(Request $request)
  {
    $validated = $request->validate([
      'page_id' => 'required',
    ]);
    DB::table('custom_pages')->where('id', $request->input('page_id'))->delete();
    return redirect()->back();
  }


}
