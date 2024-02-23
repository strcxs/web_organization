<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class divisi extends Model
{
    use HasFactory;

    protected $table = 'divisi';
    protected $guarded = ['id'];

    public function anggota(){
        return $this->hasMany("App\Models\Anggota");
    }
}