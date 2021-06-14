<?php

namespace App\Models;

use App\Models\Parcel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledPickup extends Model
{
    use HasFactory;


    /**
    * @return relationship. A parcel belongs  
    */
    public function parcel()
    {
        return $this->belongsToMany(Parcel::class)->using(ParcelScheduledPickup::class);
    }
}
