<?php

namespace App\Http\Controllers;

use App\Models\Ballot;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;
use Illuminate\Support\Facades\Validator;

class BallotController extends Controller
{
    public function index(){
        $data = Ballot::with('dataUsers.dataAnggota')
        ->with('dataCandidate.dataUsers.dataAnggota')
        ->get();

        return new AngResource(true,'data ballots',$data);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'id_candidate' => 'required', 
        ]);
        if ($validator->fails()) {
            return new AngResource(false,$validator->errors(), null);
        }

        $check = Ballot::with('dataUsers.dataAnggota')
        ->with('dataCandidate.dataUsers.dataAnggota')
        ->where('user_id','=',$request->user_id)
        ->first();

        if($check){
            return new AngResource(false,"you already vote!", $check);
        }
        Ballot::create([
            'user_id'=> $request->user_id,
            'id_candidate'=> $request->id_candidate,
        ]);
        return new AngResource(true,"voting successfully", $check);
    }
}
