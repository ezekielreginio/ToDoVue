<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'task',
        'isCompleted'
   ];

   protected $hidden = [
       'created_at',
       'updated_at'
   ];
}
