<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AngResource;
use Illuminate\Support\Facades\Validator;

class CsvController extends Controller
{
    public function store(Request $request){
        if ($request->hasFile('csv')) {
            $file = $request->file('csv');
            $csvData = array_map('str_getcsv', file($file));
            array_shift($csvData); //menghapus header
            
            DB::beginTransaction();

            try {
                foreach ($csvData as $data) {
                    $data = Anggota::create([
                        'nama' => $data[1],
                        'gender' => $data[2],
                        'id' => $data[0],
                        'tahun_akt' => $data[3],
                    ]);
                }
                DB::commit();
                return new AngResource(true, 'Success add all members',$data);
            } catch (\Exception $e) {
                DB::rollback();
                preg_match("#Duplicate entry '(.+)' for key '(.+)'#", $e->getMessage(), $matches);
                $duplicate = isset($matches[1]) ? $matches[1] : "Unknown error";

                return new AngResource(false, 'Duplicate Student ID, '+$duplicate, null);
            }
        } else {
            return response()->json(['error' => 'File CSV tidak ditemukan.'], 400);
        }
    }
}
