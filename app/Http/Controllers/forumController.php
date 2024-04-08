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
        $connection = $request->input('connection_id','1');

        $connection = $request->connection;

        if ($connection==null) {
            $connection = 1;
        }

        if ($pagination) {
            $perpage =$request->input('perPage',10);
            $forum = Forum::with(['dataUsers.dataAnggota'])
            ->where('forum.connection_id',$connection)
            ->orderBy('created_at','desc')
            ->paginate($perpage);
        }else{
            $forum = Forum::with(['dataUsers.dataAnggota'])
            ->where('forum.connection_id',$connection)
            ->orderBy('created_at','desc')
            ->get();
        }
        
        foreach ($forum as $forums) {
            $forums->dataUsers->dataAnggota->nama = ucwords(strtolower($forums->dataUsers->dataAnggota->nama));
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
        if ($request->connection != null) {
            Forum::create([
                'user_id'=> $request->user_id,
                'content'=> $request->content,
                'connection_id'=> $request->connection,
            ]);
        }else{
            Forum::create([
                'user_id'=> $request->user_id,
                'content'=> $request->content,
                'connection_id'=> '1',
            ]);
        }

        $forum = Forum::with(['dataUsers.dataAnggota'])
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
