<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    protected $table = 'vote';
    protected $guarded = [];

    public function dataTeam(){
        return $this->hasMany(Team::class,'id_vote','id');
    }
}
