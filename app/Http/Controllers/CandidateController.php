<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;
use Illuminate\Support\Facades\Validator;

class CandidateController extends Controller
{
    public function index(){
        $data = Candidate::with('dataUsers.dataAnggota')
        ->with('dataTeam.dataVote')
        ->with('dataTeam.dataDetail')
        ->get();
        
        return new AngResource(true,'data candidate',$data);
    }
    public function show($id){
        $data = Candidate::with('dataUsers.dataAnggota')
        ->with('dataDetail')
        ->with('dataVote')
        ->find($id);

        return new AngResource(true,'data candidate',$data);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'id_team' => 'required', 
        ]);
        if ($validator->fails()) {
            return new AngResource(false,"input is missing", null);
        }

        $data = Candidate::create([
            'user_id'=> $request->user_id,
            'id_team'=> $request->id_team,
        ]);

        return new AngResource(true,"create new candidate successfully", $data);
    }
    public function update(Request $request, $id){
        $key = collect($request->all())->keys();
        $data = Candidate::where('id_team','=',$id)->get();
        foreach ($data as $index => $datax) {  
            foreach ($key as $value) {
                if ($request->$value[$index] != $datax->$value) {
                    $datax->update([
                        $value => $request->$value[$index]
                    ]);
                }
            }
        }

        return new AngResource(true,'success update candidate', $data);
    }
}
