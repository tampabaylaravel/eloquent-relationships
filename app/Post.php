<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Let's not do this for real!
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
