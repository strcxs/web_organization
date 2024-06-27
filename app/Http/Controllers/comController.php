<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Forum;
use App\Models\Users;
use App\Models\divisi;
use App\Models\comment;
use App\Models\Program;
use App\Models\Connection;
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
    public function destroy(Request $request,$id){
        
        $role = Users::select('*')->where('id',$request->user_id)->first();
        $comm = comment::find($id);
        $data = Forum::find($comm->id_forum);
        
        $connection = Connection::select('*')->where('id',$data->connection_id)->first();

        $divisi = divisi::select('*')->where('id',$connection->divisi_id)->first();
        $Program = Program::select('*')->where('id',$connection->program_id)->first();
        if ($divisi != null) {
            $leader = $divisi->leader_id;
        }
        if ($Program != null){
            $leader = $Program->leader_id;
        }

        if ($role->role_id != 1 && $role->id != $leader) {
            return new AngResource(false,"You don't have access", null);
        }
        $data = comment::find($id);
        $data-> delete();

        $this->push('discuss', 'delete-comment', ['data' => $data]);
        
        return new AngResource(true,'Data berhasil dihapus', $data);
    }
}
