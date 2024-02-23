<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vote;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;

class ResultController extends Controller
{
    public function show($id){

        $vote = Vote::join('voting','vote.id_voting','=','voting.id')
        ->select('voting.id_vote_topic','voting.number','vote.id_voting', \DB::raw('count(*) as result'))
        ->where('vote.id_vote_topic',$id)
        ->groupBy('vote.id_voting')
        ->orderBy('voting.number','asc')
        ->get();

        // $vote = array_column($data,'result');

        foreach ($vote as $votes) {
            // Mengganti format created_at menjadi waktu yang lebih ramah
            $votes->formatted_created_at=Carbon::parse($votes->created_at)->diffForHumans();
        }
        return new AngResource(true,'result', $vote);
    }
}
