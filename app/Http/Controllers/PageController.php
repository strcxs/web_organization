<?php

namespace App\Http\Controllers;

use App\Models\Landing;
use Illuminate\Http\Request;
use App\Http\Resources\AngResource;

class PageController extends Controller
{
    public function index(){
        $data = Landing::first();

        return new AngResource(true,'data landing page',$data);
    }
    public function store(Request $request){
        $del = Landing::first();
        $del->delete();
        
        $data = Landing::create([
            'about_text'=> $request->about_text,
        ]);

        return new AngResource(true,'data landing page',$data);
    }
}
