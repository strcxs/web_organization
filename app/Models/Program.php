<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $table = 'program';
    protected $guarded = [];
    public function dataUsers(){
        return $this->hasOne(Users::class,'id','leader_id');
    }
}
