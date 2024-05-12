<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $table = 'candidate';
    protected $guarded = [];

    public function dataUsers(){
        return $this->hasOne(Users::class,'id','user_id');
    }
    public function dataTeam(){
        return $this->hasOne(Team::class,'id','id_team');
    }
}
