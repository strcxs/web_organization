<?php

namespace App\Http\Controllers;

use App\Http\Resources\AngResource;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function index(){
        $data = Vote::get();

        return new AngResource(true,'Vote List',$data);
    }
    public function show($id){
        $data = Vote::find($id);

        return new AngResource(true,'data Vote',$data);
    }
}
