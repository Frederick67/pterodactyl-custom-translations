<?php

namespace Pterodactyl\Http\Controllers\Billing;

use Pterodactyl\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Pterodactyl\Models\Billing\BillingUsers;
use Pterodactyl\Models\Billing\BillingSettings;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Facades\DB;
use Pterodactyl\Models\Billing\BillingLogs;
use Pterodactyl\Models\Billing\BLang;

class ProfileController extends Controller
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
    return view('templates.' . $this->template . '.billing.balance', ['billding_user' => $billding_user, 'settings' => BillingSettings::getAll()]);
  }

  public function updateUser(Request $request)
  {
    $validated = $request->validate([
      'address' => 'required',
      'city' => 'required',
      'country' => 'required',
      'postal_code' => 'required',
    ]);

    $billding_user = new BillingUsers;
    $billding_user = $billding_user->getAuth();
    $billding_user->address = $request->input('address');
    $billding_user->city = $request->input('city');
    $billding_user->country = $request->input('country');
    $billding_user->postal_code = $request->input('postal_code');
    $billding_user->save();
    return redirect()->back();
  }

  public function stripe(Request $request)
  {
    $billding_settings = BillingSettings::getAll();
    $publishable_key     = $billding_settings['publishable_key'];
    $secret_key            = $billding_settings['secret_key'];

    if(isset($_POST['stripeToken'])){
        Stripe::setApiKey($secret_key);
        $description     = "Invoice #". Auth::user()->id ."#".rand(99999,999999999);
        $amount_cents     = $_POST['amount'] * 100;
        $tokenid        = $_POST['stripeToken'];
      try {
        $charge = Charge::create(array(
          "amount" => $amount_cents,
          "currency" => $billding_settings['paypal_currency'],
          "source" => $tokenid,
          "description" => $description)              
        );
              
        $id            = $charge['id'];
        $amount     = $charge['amount'];
        $balance_transaction = $charge['balance_transaction'];
        $currency     = $charge['currency'];
        $status     = $charge['status'];
        $date     = date("Y-m-d H:i:s");
              
        $result = "succeeded";

        $log = new BillingLogs;
        $log->user_id = Auth::user()->id;
        $log->type = 'paypal';
        $log->txn_id = $id;
        $log->status = 'VERIFIED/' . $status;
        $log->data = json_encode($_REQUEST);
        $log->save();

        $this->updateBalance(Auth::user()->id, $_POST['amount'], '+');
        /* You can save the above response in DB */
        return redirect()->back()->with('success', BLang::get('stripe_status_url') . $charge['receipt_url']);

      } catch(\Stripe\Exception\CardException $e) {
        $error = $e->getMessage();
        return redirect()->back()->withErrors($error);
      } catch (\Stripe\Exception\RateLimitException $e) {
        $error = $e->getMessage();
        return redirect()->back()->withErrors($error);
      } catch (\Stripe\Exception\InvalidRequestException $e) {
        $error = $e->getMessage();
        return redirect()->back()->withErrors($error);
      } catch (\Stripe\Exception\AuthenticationException $e) {
        $error = $e->getMessage();
        return redirect()->back()->withErrors($error);
      } catch (\Stripe\Exception\ApiConnectionException $e) {
        $error = $e->getMessage();
        return redirect()->back()->withErrors($error);
      } catch (\Stripe\Exception\ApiErrorException $e) {
        $error = $e->getMessage();
        return redirect()->back()->withErrors($error);
      } catch (Exception $e) {
        $error = $e->getMessage();
        return redirect()->back()->withErrors($error);
      }
    }
    
  }

  public function updateBalance($user_id, $count, $param = '+')
  {
    if ($param == '+') {
      DB::table('billing_users')->where('user_id', $user_id)->increment('balance', $count);
    } elseif ($param == '-') {
      DB::table('billing_users')->where('user_id', $user_id)->decrement('balance', $count);
    }
  }

}
