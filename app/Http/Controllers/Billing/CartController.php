<?php

namespace Pterodactyl\Http\Controllers\Billing;

use Pterodactyl\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pterodactyl\Models\Billing\BillingUsers;
use Pterodactyl\Models\Billing\BillingSettings;
use Pterodactyl\Models\Billing\BillingCart;
use Pterodactyl\Models\Billing\BillingPlans;
use Pterodactyl\Models\Billing\BillingInvoices;
use Pterodactyl\Models\Billing\BLang;

class CartController extends Controller
{

  public $template = 'Default';

  public function __construct(Request $request)
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
    $cart = new BillingCart;
    $user_cart = array();
    foreach ($cart->where('user_id', Auth::user()->id)->get() as $value) {
      if (!empty($plan = BillingPlans::find($value->plan_id))) {
        $user_cart[$value->id] = $plan;
      } else {
        BillingCart::find($value->id)->delete();
      }
    }
    $data = array(
      'billding_user' => $billding_user,
      'settings' => BillingSettings::getAll(),
      'carts' => $user_cart,
    );
    return view('templates.' . $this->template . '.billing.cart', $data);
  }

  public function addToCart(Request $request)
  {
    $plan = BillingPlans::find($request->input('plan_id'));
    if ($plan->limit > 0) {
      $invoices = BillingInvoices::where('user_id', Auth::user()->id)->where('plan_id', $plan->id)->get();
      if (count($invoices) >= $plan->limit) {
        return redirect()->back()->withErrors(BLang::get('err_plan_limit') . $plan->limit);
      }
      $cart = BillingCart::where('user_id', Auth::user()->id)->where('plan_id', $plan->id)->get();
      if (count($cart) >= $plan->limit) {
        return redirect()->back()->withErrors(BLang::get('err_plan_limit') . $plan->limit);
      }
    }

    $cart = new BillingCart;
    $cart->user_id = Auth::user()->id;
    $cart->plan_id = $plan->id;
    $cart->save();
    return redirect()->route('billing.cart')->with('success', BLang::get('plan_added_cart'));
  }

  public function removeCart(Request $request)
  {
    $cart = BillingCart::find($request->input('plan_id'));
    if ($cart->user_id == Auth::user()->id) {
      $cart->delete();
    }
    return redirect()->route('billing.cart');
  }

  public function orderAll()
  {
    $user_id = Auth::user()->id;
    $billding_user = new BillingUsers;
    foreach (BillingCart::where('user_id', $user_id)->get() as $value) {
      if (!empty($plan = BillingPlans::find($value->plan_id))) {
        $user = $billding_user->getAuth();
        if ($user->balance >= $plan->price) {
          if (BillingSettings::createServer($user_id, $plan->id)) {
            $user->editBalance($plan->price, '-');
            BillingCart::where('id', $value->id)->delete();
          } else {
            return redirect()->back()->withErrors(BLang::get('err_create_server'));
          }
        } else {
          return redirect()->back()->withErrors(BLang::get('err_user_balance'));
        }
      } else {
        return redirect()->back()->withErrors(BLang::get('err_plan_exist'));
      }
    } 
    return redirect()->back()->with('success', BLang::get('server_create_success'));
  }
}
