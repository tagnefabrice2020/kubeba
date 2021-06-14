<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;


    /**
    * @return relationship. A contact belongs to a user at a given peroid
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
