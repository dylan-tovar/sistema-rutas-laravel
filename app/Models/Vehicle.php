<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    //
    use HasFactory;

    protected $fillable =  ['make', 'model', 'year', 'status', 'user_id'];

     public function user()
     {
         return $this->belongsTo(User::class);
     }
}
