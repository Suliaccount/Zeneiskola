<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Tanar;
use Illuminate\Support\Facades\Log;
use App\Models\Tantargy;



class tanarController extends Controller
{
    //az a nev ami a route-ba van

         public function tanarListazas(){
            
            $tanarok = Tanar::where("telepules","LIKE","Cegléd")->paginate(4);
            return view('welcome',['tanarok' => $tanarok]);
        }
    
    public function upload(Request $req){
         $req->validate(
            [
                'fajl' => "required|mimes:png,jpg|max:100"
            ],
            [
                'fajl.required' => "Kötelező a fájlt megadni",
                'fajl.mimes' => 'Csak png,jpg típus lehet',
                'fajl.max' => 'Max 100kb lehet'
            ]
            );
            $kep = data('YmdHis').".".$req->fajl->extension();
            $req->fajl->storeAs("public/kepek",$kep);

            return back()->with('success','Kép feltöltése sikeres!');
    }
            
        
    


    
    public function tanarKereso(Request $req){
         $tanarok = Tanar::query();
         $telepulesek = Tanar::select("telepules")->groupBy("telepules")->get();
         $tantargyak = Tantargy::select("tantargy_id", "nev")->get();
         $nev = "";
         $tantargy = "";
         $cim = "";
         $telefon = "";
         $email = "";
         $leiras = "";
         $oradij = "";

         
         if ($req->get('telepules') != ""){
             $telepules = $req->get('telepules');
             $tanarok->where('telepules', 'like', '%' . $telepules . '%');
         }
        
        if ($req->get('tantargy_id') != ""){
            $tantargy_id = $req->get('tantargy_id');
            $tanarok->whereHas('tanarok_tantargyai',function($query) use ($tantargy_id){
                $query->where('tantargy_id',$tantargy_id);
            });
        }
        
        $tanarok = $tanarok->paginate(2);

        return view("tanarKereso",["tanarok" => $tanarok, "telepulesek"=> $telepulesek, "tantargyak"=> $tantargyak]);
    }

}
