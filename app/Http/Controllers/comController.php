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
        $comment = comment::with(['dataUsers.dataAnggota'])
        ->where('id_forum', $id)
        ->orderBy('created_at','desc')
        ->get();

        foreach ($comment as $comments) {
            $comments->dataUsers->dataAnggota->nama = ucwords(strtolower($comments->dataUsers->dataAnggota->nama));
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

        $comment = comment::with(['dataUsers.dataAnggota'])
        ->where('id_forum', $request->id_forum)
        ->orderBy('created_at','desc')
        ->first();

        $comment->dataUsers->dataAnggota->nama = ucwords(strtolower($comment->dataUsers->dataAnggota->nama));
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
