<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Peru\Http\ContextClient;
use Peru\Jne\{Dni, DniParser};
use App\Models\District;

class AdministratorController extends Controller
{
    //
    use AuthenticatesUsers;

    public function showLoginForm()
    {
        if (Auth::user() != null) return redirect('/main');
        return view('auth.login');
    }


    public function dni($dni)
    {
        $cs = new Dni(new ContextClient(), new DniParser());
        $person = $cs->get($dni);
        // return $person;
        if (!$person) {
            return array('success' => false);
        }
        return array('success' => true, 'person' => $person);
    }

    public function quertium($dni)
    {
        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.MTE5MQ.lP8EKqTUblmUB5DDWkqakaYZ8DJtIa06dCZJBPtIW1E';
        $person = json_decode(file_get_contents("https://quertium.com/api/v1/reniec/dni/" . $dni . "?token=" . $token), true);
        if (!$person) {
            return array('success' => false);
        }
        $person['dni'] = $dni;
        $person['nombres'] = $person['primerNombre'] . ' ' . $person['segundoNombre'];
        return array('success' => true, 'person' => $person);
    }


    public function search_district()
    {
        $districts = District::where('name', 'like', '%' . strtoupper($_GET['term']) . '%')->get();
        $response = array();
        foreach ($districts as $district)
            $response[] = array('value' => html_entity_decode($district->name), 'id' => $district->id);
        return $response;
    }

    // public function search_expediente(){
    //     $expedientes=Expediente::where('num_expediente','like','%'.$_GET['term'].'%')->take(15)->get();

    //     $response=array();

    //     foreach($expedientes as $expediente){

    //         $date = new \DateTime($expediente->fecha_doc);
    //         $year = $date->format("Y");

    //         $response[]=array('value'=>$expediente->num_expediente.' - '.$year,'id'=>$expediente->cod_expediente);
    //     }
    //     return $response;
    // }

    // public function search_recibo(){
    //     $recibos=Caja_Recibo::where('nrorecibo','like','%'.$_GET['term'].'%')->take(15)->get();

    //     $response=array();

    //     foreach($recibos as $recibo){

    //         $date = new \DateTime($recibo->fecha);
    //         $year = $date->format("Y");

    //         $response[]=array('value'=>$recibo->nrorecibo.' - S/ '.$recibo->total.' - '.$year,'id'=>$recibo->idrecibo,'total'=>$recibo->total);
    //     }
    //     return $response;
    // }
}
