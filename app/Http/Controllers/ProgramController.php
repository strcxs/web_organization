<?php

namespace App\Http\Controllers;

use App\Http\Resources\AngResource;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    public function index(){
        $data = Program::get();

        return new AngResource(true,'data Program',$data);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'program' => 'required', 
            'leader_id' => 'required', 
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        Program::create([
            'program'=>$request->program,
            'leader_id'=>$request->leader_id,
        ]);
        $data = Program::orderBy('created_at','desc')
        ->first();

        return new AngResource(true,'success create program',$data);
    }
}
