<?php

namespace App\Http\Controllers;

use App\Models\Ballot;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;
use Illuminate\Support\Facades\Validator;

class BallotController extends Controller
{
    public function index(){
        $data = Ballot::with('dataUsers.dataAnggota')
        ->with('dataTeam.dataVote')
        ->with('dataTeam.dataCandidate.dataUsers.dataAnggota')
        ->get();

        return new AngResource(true,'data ballots',$data);
    }
    public function show($id){
        $data = Ballot::with('dataUsers.dataAnggota')
        ->with('dataTeam.dataVote')
        ->with('dataTeam.dataCandidate.dataUsers.dataAnggota')
        ->whereHas('dataTeam', function ($query) use ($id) {
            $query->where('id_vote', $id);
        })
        ->get();

        return new AngResource(true,'data ballots',$data);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'id_team' => 'required', 
        ]);
        if ($validator->fails()) {
            return new AngResource(false,$validator->errors(), null);
        }

        $check = Ballot::with('dataUsers.dataAnggota')
        ->with('dataTeam')
        ->where('user_id','=', $request->user_id)
        ->whereHas('dataTeam', function ($query) use ($request) {
            $query->where('id_vote', $request->id_vote);
        })
        ->first();

        $voteEnd = Vote::select('voteEnds','voteStart')
        ->where('id','=',$request->id_vote)
        ->first();
        
        $carbonTime = Carbon::now();
        $timeNow =  $carbonTime->timestamp * 1000;
        $votingStart = $voteEnd->voteStart;
        $votingEnds = $voteEnd->voteEnds;

        if($timeNow<$votingStart){
            return new AngResource(false,"voting hasn't started!", $voteEnd);
        }

        if($timeNow>$votingEnds){
            return new AngResource(false,"voting has ended!", $voteEnd);
        }

        if($check){
            return new AngResource(false,"you already vote!", $check);
        }
        $vote = Ballot::create([
            'user_id'=> $request->user_id,
            'id_team'=> $request->id_team,
        ]);
        $vote->load('dataTeam');

        $this->push('vote', 'vote-success', ['data' => $vote]);
        return new AngResource(true,"voting successfully", $vote);
    }
}
