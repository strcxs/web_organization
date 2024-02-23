<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Voting;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;

class VotingController extends Controller
{
    public function index(){
        $vote = Voting::join('vote_topic','voting.id_vote_topic','=','vote_topic.id')
        // ->select('voting.id','voting.value','voting.image','voting.visi','voting.misi','vote_topic.topic_information')
        ->select('voting.*','vote_topic.topic_information',)
        ->orderBy('voting.number','asc')
        ->get();


        // $vote = Voting::orderBy('created_at','desc')->get();
        
        foreach ($vote as $votes) {
            // Mengganti format created_at menjadi waktu yang lebih ramah
            $votes->formatted_created_at=Carbon::parse($votes->created_at)->diffForHumans();
        }

        return new AngResource(true,'data vote', $vote);
    }
    public function show($id){

        $vote = Voting::join('vote_topic','voting.id_vote_topic','=','vote_topic.id')
        // ->select('voting.id','voting.value','voting.image','vote_topic.topic_information','voting.visi','voting.misi')
        ->select('voting.*','vote_topic.topic_information')
        ->where('voting.id_vote_topic',$id)
        ->get();

        foreach ($vote as $votes) {
            // Mengganti format created_at menjadi waktu yang lebih ramah
            $votes->formatted_created_at=Carbon::parse($votes->created_at)->diffForHumans();
        }
        return new AngResource(true,'data vote', $vote);
    }
}
