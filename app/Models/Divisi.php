<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class divisi extends Model
{
    use HasFactory;

    protected $table = 'divisi';
    protected $guarded = ['id'];

    public function dataUsers(){
        return $this->hasOne(Users::class,'id','leader_id');
    }
}