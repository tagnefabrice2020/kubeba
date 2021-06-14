<?php

namespace App\Models;

use App\Models\Country;
use App\Models\WorkingLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    /**
    * @return relationship. A branch belongs to a country
    */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
    * @return relationship. A many workers work in a branch at a given time
    */
    public function workersLocation()
    {
        return $this->hasMany(WorkingLocation::class);
    }
}
