<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Forum;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;

class forumController extends Controller
{
    public function index(){
        // $forum = Forum::orderBy('created_at','desc')->get();

        $forum = Forum::join('data_anggota','forum.user_id','=','data_anggota.user_id')
        ->select('forum.id','forum.content','forum.created_at','data_anggota.nama','data_anggota.avatar','data_anggota.user_id as no_user')
        ->orderBy('created_at','desc')
        ->get();

        
        foreach ($forum as $forums) {
            // Mengganti format created_at menjadi waktu yang lebih ramah
            // $forums->formatted_created_at = Carbon::parse($forums->created_at)->diffForHumans();
            $forums->formatted_created_at=Carbon::parse($forums->created_at)->diffForHumans();
            // $forums->comment_created_at=Carbon::parse($forums->comment_created_at)->diffForHumans();
        }

        return new AngResource(true,'data forum', $forum);
    }
    public function store(Request $request){
        Forum::create([
            'user_id'=> $request->user_id,
            'content'=> $request->content
        ]);

        $forum = Forum::join('data_anggota','forum.user_id','=','data_anggota.user_id')
        ->select('forum.id','forum.content','forum.created_at','data_anggota.nama','data_anggota.avatar','data_anggota.user_id as no_user')
        ->orderBy('created_at','desc')
        ->first();

        $forum->formatted_created_at=Carbon::parse($forum->created_at)->diffForHumans();

        return new AngResource(true,"post send successfully", $forum);
    }
    public function destroy($id){
        $data = Forum::find($id);
        $data-> delete();
        
        return new AngResource(true,'Data berhasil dihapus', $data);
    }
}
