<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VoteController extends Controller
{
    public function index(){
        $data = Vote::with('dataTeam.dataCandidate')
        ->get();

        return new AngResource(true,'Vote List',$data);
    }
    public function show($id){
        $data = Vote::find($id);

        return new AngResource(true,'data Vote',$data);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'voteStart' => 'required',
            'voteEnds' => 'required'
        ]);
        if ($validator->fails()) {
            return new AngResource(false,"input is missing", null);
        }

        $data = Vote::create([
            'description'=> $request->description,
            'voteStart'=>$request->voteStart,
            'voteEnds'=>$request->voteEnds
        ]);

        return new AngResource(true,"create new vote successfully", $data);
    }
    public function destroy($id){
        $data = Vote::with('dataTeam')->find($id);
        foreach ($data->dataTeam as $tim) {
            Storage::delete('public/images/banner/'.$tim->banner_image);
        }
        $data-> delete();

        return new AngResource(true,'vote delete successfully', $data);
    }
    public function update(Request $request, $id){
        $key = collect($request->all())->keys();
        $data = Vote::find($id);

        foreach ($key as $value) {
            if ($request->$value != $data->$value) {
                $data->update([
                    $value => $request->$value
                ]);
            }
        }

        $data = Vote::find($id);

        return new AngResource(true,'success update vote', $data);
    }
}
