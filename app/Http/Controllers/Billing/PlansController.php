<?php

namespace Pterodactyl\Http\Controllers\Billing;

use Exception;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Models\Billing\BillingGames;
use Pterodactyl\Models\Billing\BillingPlans;
use Pterodactyl\Models\Billing\BillingSettings;
use Pterodactyl\Models\Billing\BLang;

class PlansController extends Controller
{

  public $template = 'Default';

  public function __construct()
  {
    if (!empty(config('billing.theme'))){
      $this->template = config('billing.theme');
    }
		BillingSettings::scheduler();
  }
  
  public function getPlans($game_type)
  {
    $game = new BillingGames;
    $game = $game->where('link', $game_type)->first();
    $plans = new BillingPlans;
    try {
      $plans = $plans->where('game_id', $game->id)->get();
    } catch (Exception $e) {
      return redirect()->back()->withErrors(BLang::get('err_plans_in_game'));
    }
    
    return view('templates.' . $this->template . '.billing.plans', ['plans' => $plans, 'game' => $game, 'settings' => BillingSettings::getAll()]);
  }

}