<?php

namespace App\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    /**
    * @return relationship. A country has many branches
    */
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
