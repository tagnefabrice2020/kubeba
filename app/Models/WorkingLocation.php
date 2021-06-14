<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingLocation extends Model
{
    use HasFactory;


    /**
    * @return relationship. A worker has many working location at a given time
    */
    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    /**
    * @return relationship. A branch belongs to a working location 
    */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
