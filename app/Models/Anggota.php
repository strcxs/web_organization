<?php

namespace App\Models;

use App\Models\Users;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Anggota extends Model
{
    use HasFactory;
    protected $table = "data_anggota";
    protected $guarded = ["created_at"];
    public function dataUsers(){
        return $this->hasOne(Users::class,'member_id','id');
    }
}
