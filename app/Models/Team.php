<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $table = 'team';
    protected $guarded = [];

    public function dataVote(){
        return $this->hasOne(Vote::class,'id','id_vote');
    }
    public function dataDetail(){
        return $this->hasOne(Detail::class,'id_team','id');
    }
    public function dataCandidate(){
        return $this->hasMany(Candidate::class,'id_team','id');
    }
}
