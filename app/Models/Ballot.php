<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ballot extends Model
{
    use HasFactory;
    protected $table = 'ballots';
    protected $guarded = [];
    public function dataUsers(){
        return $this->hasOne(Users::class,'id','user_id');
    }
    public function dataCandidate(){
        return $this->hasOne(Candidate::class,'id','id_candidate');
    }
}
