<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\comment;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;
use Illuminate\Support\Facades\Validator;

class comController extends Controller
{
    public function show($id){
        $comment = comment::join('forum','comment.id_forum','=','forum.id')
        ->join('data_anggota','data_anggota.user_id','=','comment.user_id')
        ->select('data_anggota.avatar','data_anggota.nama','forum.id as forum_id','comment.content','comment.created_at','data_anggota.user_id as no_user','comment.id')
        ->where('forum.id', $id)
        ->orderBy('created_at','desc')
        ->get();

        foreach ($comment as $comments) {
            $comments->formatted_created_at=Carbon::parse($comments->created_at)->diffForHumans();
        }

        return new AngResource(true,'data comment', $comment);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'id_forum' => 'required',
            'user_id' => 'required', 
            'content' => 'required', 
        ]);
        if ($validator->fails()) {
            return new AngResource(false,"input is missing", null);
        }

        comment::create([
            'id_forum'=> $request->id_forum,
            'user_id'=> $request->user_id,
            'content'=> $request->content
        ]);

        $comment = comment::join('forum','comment.id_forum','=','forum.id')
        ->join('data_anggota','data_anggota.user_id','=','comment.user_id')
        ->select('data_anggota.avatar','data_anggota.nama','forum.id as forum_id','comment.content','comment.created_at','data_anggota.user_id as no_user')
        ->where('forum.id', $request->id_forum)
        ->orderBy('created_at','desc')
        ->first();

        $comment->formatted_created_at=Carbon::parse($comment->created_at)->diffForHumans();

        $this->push('discuss', 'sent-comment', ['data' => $comment]);
        
        return new AngResource(true,"comment send successfully", $comment);
    }
    public function destroy($id){
        $data = comment::find($id);
        $data-> delete();

        $this->push('discuss', 'delete-comment', ['data' => $data]);
        
        return new AngResource(true,'Data berhasil dihapus', $data);
    }
}
