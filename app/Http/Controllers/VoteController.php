<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Voting;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;

class VoteController extends Controller
{
    public function index(){
        $vote = Voting::select('*')
        ->get();
        
        foreach ($vote as $votes) {
            $votes->formatted_created_at=Carbon::parse($votes->created_at)->diffForHumans();
        }

        return new AngResource(true,'data vote', $vote);
    }
    public function show($id){
        $vote = Voting::select('*')
        ->where('id_vote_topic',$id)
        ->get();
        
        foreach ($vote as $votes) {
            $votes->formatted_created_at=Carbon::parse($votes->created_at)->diffForHumans();
        }
        return new AngResource(true,'data vote', $vote);
    }
}
