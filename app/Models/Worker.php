<?php

namespace App\Models;

use App\Models\User;
use App\Models\WorkingLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends User
{
    use HasFactory;

    /**
    * @return relationship. A worker has many working location at a given time
    */
    public function workingLocation()
    {
        return $this->hasMany(WorkingLocation::class);
    }
}
