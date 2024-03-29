<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Forum;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;
use Illuminate\Support\Facades\Validator;

class forumController extends Controller
{
    public function index(Request $request){
        $pagination = $request->input('pagination',false);
        if ($pagination) {
            $perpage =$request->input('perPage',10);
            $forum = Forum::join('data_anggota','forum.user_id','=','data_anggota.user_id')
            ->select('forum.id','forum.content','forum.created_at','data_anggota.nama','data_anggota.avatar','data_anggota.user_id as no_user')
            ->orderBy('created_at','desc')
            ->paginate($perpage);
        }else{
            $forum = Forum::join('data_anggota','forum.user_id','=','data_anggota.user_id')
            ->select('forum.id','forum.content','forum.created_at','data_anggota.nama','data_anggota.avatar','data_anggota.user_id as no_user')
            ->orderBy('created_at','desc')
            ->get();
        }
        
        foreach ($forum as $forums) {
            $forums->formatted_created_at=Carbon::parse($forums->created_at)->diffForHumans();
        }

        return new AngResource(true,'data forum', $forum);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required', 
            'content' => 'required', 
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        Forum::create([
            'user_id'=> $request->user_id,
            'content'=> $request->content
        ]);

        $forum = Forum::join('data_anggota','forum.user_id','=','data_anggota.user_id')
        ->select('forum.id','forum.content','forum.created_at','data_anggota.nama','data_anggota.avatar','data_anggota.user_id as no_user')
        ->orderBy('created_at','desc')
        ->first();

        $forum->formatted_created_at=Carbon::parse($forum->created_at)->diffForHumans();

        $this->push('discuss', 'sent-discuss', ['data' => $forum]);
        
        return new AngResource(true,'message sent successfully', $forum);
    }
    public function destroy($id){
        $data = Forum::find($id);
        $data-> delete();

        $this->push('discuss', 'delete-discuss', ['data' => $data]);
        
        return new AngResource(true,'message delete successfully', $data);
    }
}
