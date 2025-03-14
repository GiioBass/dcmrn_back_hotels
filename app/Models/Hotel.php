<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'address',
        'city',
        'nit',
        'qty_rooms'
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
