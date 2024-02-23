<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteTopic extends Model
{
    use HasFactory;

    protected $table = 'vote_topic';
    protected $guarded = [];
}
