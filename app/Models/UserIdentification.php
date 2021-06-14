<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIdentification extends Model
{
    use HasFactory;

    /**
    * @return relationship. Each identification belongs to a user
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
