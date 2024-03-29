<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vote;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;

class CheckController extends Controller
{
    public function show($id){

        $vote = Vote::join('voting','vote.id_voting','=','voting.id')
        ->select('*')
        ->where('vote.id_vote_topic',$id)
        ->orderBy('voting.number','asc')
        ->get();

        // $vote = array_column($data,'result');

        foreach ($vote as $votes) {
            $votes->formatted_created_at=Carbon::parse($votes->created_at)->diffForHumans();
        }
        return new AngResource(true,'Check', $vote);
    }
    public function store(Request $request){
        $existingVote = Vote::where('id_user', $request->id_user)
            ->where('id_vote_topic', $request->id_vote_topic)
            ->first();

        if ($existingVote) {
            return new AngResource(false,'You have already voted for this topic.', null);
        }

        Vote::create([
            'id_user'=> $request->id_user,
            'id_vote_topic'=> $request->id_vote_topic,
            'id_voting'=> $request->id_voting,
        ]);

        $vote = Vote::join('voting','vote.id_voting','=','voting.id')
        ->select('*')
        ->where('vote.id_vote_topic',$request->id)
        ->orderBy('voting.number','asc')
        ->get();


        foreach ($vote as $votes) {
            $votes->formatted_created_at=Carbon::parse($votes->created_at)->diffForHumans();
        }
        return new AngResource(true,'voted', $vote);
    }
}
