<?php

namespace Pterodactyl\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pterodactyl\Models\Billing\BillingGames;

class BillingPlans extends Model
{
    use HasFactory;

    public function getGameName()
    {
      return BillingGames::getNameToId($this->game_id);
    }

    public function getArrayKeyData()
    {
      $data = array();
      foreach ($this->get() as $key => $value) {
        $data[$value->id] = $value;
      }
      return $data;
    }
}
