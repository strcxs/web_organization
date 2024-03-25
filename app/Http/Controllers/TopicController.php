<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\VoteTopic;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;

class TopicController extends Controller
{
    public function index(){
        $topic = VoteTopic::select('*')
        ->orderBy('created_at','asc')
        ->get();
        
        foreach ($topic as $topics) {
            $topics->formatted_created_at=Carbon::parse($topics->created_at)->diffForHumans();
        }

        return new AngResource(true,'topic', $topic);
    }
    public function destroy($id){
        $data = VoteTopic::find($id);
        $data-> delete();
        
        return new AngResource(true,'Topic Deleted', $data);
    }
    public function store(Request $request){

        if ($request->topic_name == null && $request->topic_information == null) {
            return new AngResource(false,'please input', null);
        }
        VoteTopic::create([
            'topic_name'=> $request->topic_name,
            'topic_information'=> $request->topic_information
        ]);

        $topic = VoteTopic::select('*')
        ->orderBy('created_at','asc')
        ->get();
        
        foreach ($topic as $topics) {
            $topics->formatted_created_at=Carbon::parse($topics->created_at)->diffForHumans();
        }

        return new AngResource(true,'topic added', $topic);
    }
    public function update(Request $request, $id)
    {
        $topic = VoteTopic::find($id);

        if ($request->topic_name != null && $request->topic_name != $topic->topic_name) {
            $topic->topic_name = $request->topic_name;
        }

        if ($request->topic_information != null && $request->topic_information != $topic->topic_information) {
            $topic->topic_information = $request->topic_information;
        }

        // Memeriksa apakah ada perubahan pada data
        if ($topic->isDirty()) {
            $topic->save();
            return new AngResource(true, "Data updated", $topic);
        } else {
            return new AngResource(true, "No data changed", null);
        }
    }
}
