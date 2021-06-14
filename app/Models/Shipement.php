<?php

namespace App\Models;

use App\Models\Parcel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipement extends Model
{
    use HasFactory;


    /**
    * @return relationship. A shipment has many parcels
    */
    public function parcels()
    {
        return $this->hasMany(Parcel::class);
    }
}
