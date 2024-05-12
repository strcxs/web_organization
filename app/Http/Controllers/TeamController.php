<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function index(){
        $data = Team::with('dataVote')
        ->with('dataCandidate.dataUsers.dataAnggota')
        ->get();

        return new AngResource(true,'data team',$data);;
    }
    public function show($id){
        $data = Team::with('dataVote')
        ->with('dataCandidate.dataUsers.dataAnggota')
        ->where('id_vote','=',$id)
        ->get();

        return new AngResource(true,'data team',$data);;
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'id_vote' => 'required',
            'name' => 'required', 
        ]);
        if ($validator->fails()) {
            return new AngResource(false,"input is missing", null);
        }

        $data = Team::create([
            'id_vote'=> $request->id_vote,
            'name'=> $request->name,
        ]);

        return new AngResource(true,"create new team successfully", $data);
    }
    public function update(Request $request, $id){
        $key = collect($request->all())->keys();
        $data = Team::find($id);

        foreach ($key as $value) {
            if ($request->$value != $data->$value) {
                $data->update([
                    $value => $request->$value
                ]);
            }
        }

        $data = Team::find($id);
        return new AngResource(true,'success update', $data);
    }
}