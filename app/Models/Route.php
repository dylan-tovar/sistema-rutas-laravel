<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'status',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'route_orders');
    }
}
