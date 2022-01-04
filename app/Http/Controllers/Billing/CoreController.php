<?php

namespace Pterodactyl\Http\Controllers\Billing;

use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Models\Billing\BillingUsers;
use Pterodactyl\Models\Billing\BillingGames;
use Pterodactyl\Models\Billing\BillingSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Pterodactyl\Models\Billing\BLang;

class CoreController extends Controller
{

  public $template = 'Default';

  public function __construct()
  {
    if (!empty(config('billing.theme'))){
      $this->template = config('billing.theme');
    }
    BillingSettings::scheduler();

    if (!Auth::guest()){
      if (!Cache::has('carbondarckmode' . Auth::user()->id)) {
        Cache::put('carbondarckmode' . Auth::user()->id, 'on');
      }
    }

  }
  
  public function index()
  {
    $billding_user = new BillingUsers;
    $billding_user->issetOrCreateUser();

    $billding_games = new BillingGames;
    $games = $billding_games->get();
    return view('templates.' . $this->template . '.billing.games', ['games' => $games, 'settings' => BillingSettings::getAll()]);
  }

  public function scheduler()
  {
    echo BillingSettings::scheduler();
  }

  public function getPage($page)
  {
    $page = DB::table('custom_pages')->where('url', $page)->first();
    return view('templates.' . $this->template . '.billing.page_view', ['page' => $page, 'settings' => BillingSettings::getAll()]);
  }

  public function toggleMode()
  {

    $mode = Cache::get('carbondarckmode' . Auth::user()->id);
    if ($mode == 'off') {
      $mode = 'on';
    } else {
      $mode = 'off';
    }
    Cache::put('carbondarckmode' . Auth::user()->id, $mode);
    
    return redirect()->back();
  }

  public function toggleUserLang($lang)
  {
    if (file_exists(app_path() . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Billing' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $lang . '.php')) {
      Cache::put('billinguserlang' . Auth::user()->id, $lang);
    }
    return redirect()->back();
  }
}
