<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Relative;

class RelativeController extends Controller
{
    public function search_relative(){
        $relatives=Relative::with(['entity'])->whereHas("entity", function($q){
            $q->where("document_number",'like','%'.$_GET['term'].'%');
        })->get();
        //  return $relatives;
        $response=array();

        foreach($relatives as $relative){
            $response[]=array('value'=>$relative->entity->document_number,'id'=>$relative->id,'relative'=>$relative);
        }
        return $response;
    }
}
