<?php

namespace Pterodactyl\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingGames extends Model
{
    use HasFactory;
    
    public static function getNameToId($id)
    {
      $game = self::find($id);
      return $game->label;
    }
}
