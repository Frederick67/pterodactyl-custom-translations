<?php

namespace Pterodactyl\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Pterodactyl\Models\Billing\BillingLogs;
use Pterodactyl\Models\User;

class BillingUsers extends Model
{
  use HasFactory;

  public function issetOrCreateUser()
  {
    if (empty($this->where('user_id', Auth::user()->id)->first())) {
      $this->user_id = Auth::user()->id;
      $this->balance = 0;
      $this->save();
    }
    return true;
  }

  public function getAuth()
  {
    $this->issetOrCreateUser();
    return $this->where('user_id', Auth::user()->id)->first();
  }

  public function editBalance($count, $param, $invoice_id = NULL)
  {
    if ($param == '+') {
      $this->increment('balance', $count);
    } elseif($param == '-'){
      $this->decrement('balance', $count);
    } elseif($param == '='){
      $this->balance = $count;
      $this->save();
    }

    if ($invoice_id != NULL) {
      BillingLogs::setInvoiceLog($count, $param, $invoice_id, $this->user_id);
    }
  }

  public static function getAllUsers()
  {
    $data = array();
    foreach (self::get() as $key => $user) {
      $data[$user->user_id]['billing'] = $user;
      $data[$user->user_id]['ptero'] = User::find($user->user_id);
    }
    return $data;
  }
}
