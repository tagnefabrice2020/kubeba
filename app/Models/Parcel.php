<?php

namespace App\Models;

use App\Models\ParcelScheduledPickup;
use App\Models\ScheduledPickup;
use App\Models\Shipement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    use HasFactory;


    /**
    * @return relationship. A parcel belongs to a shipment at a given peroid
    */
    public function shipement()
    {
        return $this->belongsTo(Shipement::class);
    }

    /**
    * @return relationship. A parcel belongs  
    */
    public function scheduledPickup()
    {
        return $this->belongsToMany(ScheduledPickup::class)->using(ParcelScheduledPickup::class);
    }
}
