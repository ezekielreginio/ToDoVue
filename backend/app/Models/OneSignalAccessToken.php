<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OneSignalAccessToken extends Model
{
    protected $fillable = [
        'user_id',
        'one_signal_token',
        'device_uuid',
   ];

   protected $hidden = [
       'created_at',
       'updated_at'
   ];
}
