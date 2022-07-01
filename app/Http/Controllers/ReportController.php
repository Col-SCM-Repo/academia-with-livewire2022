<?php

namespace App\Http\Controllers;

use App\EJB\PeriodoEJB;
use App\Enrollment;
use Illuminate\Http\Request;
use App\Release;
use App\Record;
use App\Internment;
use App\Level;
use App\Period;
use App\Type_code;
use PDF;
use Carbon\Carbon;

class ReportController extends Controller
{
    private $period_EJB;

    public function __construct()
    {
        $this->period_EJB = new PeriodoEJB();
    }

    public function test(Request $request){
        // $releases=Release::with(['internment.record.infractions'])->where('paid_out',1)->has('internment.record.infractions')->get();

        $start=Carbon::createFromFormat('d/m/Y', $request->start);
        $end=Carbon::createFromFormat('d/m/Y', $request->end);
        
        $internments=Internment::with(['record','release'])->whereHas('release',function($q) use($start,$end){
            $q->where('paid_out',1)->where('date','>=',$start)->where('date','<=',$end);
        })->get();

        return $internments;
    }

    public function records_collected_report(){
        return view('reports.records_collected_report');   
    }

    public function do_records_collected_report(Request $request){
        
        $start=Carbon::createFromFormat('d/m/Y', $request->start);
        $end=Carbon::createFromFormat('d/m/Y', $request->end);
        
        // $releases=Release::with(['internment.record.infractions'])->where('date','>=',$start)->where('date','<=',$end)->where('paid_out',1)->has('internment.record.infractions')->get();
        
        $records=Record::with(['internment.release','infractions'])->whereHas('internment.release',function($q) use($start,$end){
            $q->where('paid_out',1)->where('date','>=',$start)->where('date','<=',$end);
        })->get();

        // $records=collect([]);

        $totals=[];
        $pay_dates=[];
        // $releases->each(function($release) use(&$pay_dates,&$totals,$records)  {
        //     $record=$release->internment->record;
        //     $records->push($record);
        //     $sum=$record->infractions->sum('pivot.price_paid');
        //     $totals[$record->id]=$sum;
        //     $pay_dates[$record->id]=$release->date;
        // });

        return view('reports.listing_records_collected_report')->with('records',$records)->with('totals',$totals)->with('pay_dates',$pay_dates);
    }


    public function internments_collected_report(){
        return view('reports.internments_collected_report');   
    }

    public function do_internments_collected_report(Request $request){
        
        $start=Carbon::createFromFormat('d/m/Y', $request->start);
        $end=Carbon::createFromFormat('d/m/Y', $request->end);
        
        // $releases=Release::with(['internment.record'])->where('date','>=',$start)->where('date','<=',$end)->where('paid_out',1)->get();

        $internments=Internment::with(['record','release'])->whereHas('release',function($q) use($start,$end){
            $q->where('paid_out',1)->where('date','>=',$start)->where('date','<=',$end);
        })->get();

        
        // $internments=collect([]);

        $pay_dates=[];
        $vehicular_registrations=[];
        // $releases->each(function($release) use(&$pay_dates,&$vehicular_registrations,$internments)  {
        //     $internment=$release->internment;
        //     $internments->push($internment);
        //     $pay_dates[$internment->id]=$release->date;
        //     $vehicular_registrations[$internment->id]=$internment->type=='acta'?$release->internment->record->vehicular_registration:$internment->vehicular_registration;
        // });

        return view('reports.listing_internments_collected_report')->with('internments',$internments)->with('pay_dates',$pay_dates)->with('vehicular_registrations',$vehicular_registrations);
    }

    public function records_company_report(Request $request){
        // return Record::with(['internment.release'])->where('taxi_company_id',$request->taxi_company_id)->get();
        return view('reports.records_company_report');   
    }

    public function do_records_company_report(Request $request){
        $records=Record::with(['internment.release','infractions'])->where('taxi_company_id',$request->taxi_company_id)->get();
        return view('reports.listing_records_company_report')->with('records',$records);
    }


    public function vehicular_registration_report(Request $request){
        // $vehicular_registration=trim($request->vehicular_registration);

        // $records=Record::with(['internment.release','infractions'])->where('vehicular_registration',$vehicular_registration)->get();

        // $policial_internments=Internment::with(['release'])->where('type','policial')->where('vehicular_registration',$vehicular_registration)->get();

        // $acta_internments=Internment::with(['record','release'])->whereHas('record',function($q) use($vehicular_registration){
        //     $q->where('vehicular_registration',trim($vehicular_registration));
        // })->get();


        // $internments=$policial_internments->merge($acta_internments)->all();

        // return $internments;
        return view('reports.vehicular_registration_report');  
    }

    public function do_vehicular_registration_report(Request $request){

        $vehicular_registration=trim($request->vehicular_registration);

        $records=Record::with(['internment.release','infractions'])->where('vehicular_registration',$vehicular_registration)->get();

        $policial_internments=Internment::with(['release'])->where('type','policial')->where('vehicular_registration',$vehicular_registration)->get();

        $acta_internments=Internment::with(['record','release'])->whereHas('record',function($q) use($vehicular_registration){
            $q->where('vehicular_registration',trim($vehicular_registration));
        })->get();

        // $plucked_internments=$records->pluck('internment');

        // $plucked_internments=$plucked_internments->filter()->all();

        // $internments=$policial_internments->merge($plucked_internments)->all();

        $internments=$policial_internments->merge($acta_internments)->all();

        return view('reports.listing_vehicular_registration_report')->with('records',$records)->with('internments',$internments);
    }

/*
    public function outstanding_report(){
        // $records_1=Record::with(['internment','infractions'])->whereDoesntHave('internment.release')->get();
        $records_1=Record::with(['infractions'])->withCount('internment')->whereDoesntHave('internment.release')->get();

        $records_2=Record::with(['internment.release','infractions'])->whereHas('internment.release',function($q){
            $q->where('paid_out',0);
        })->get();

        $records=$records_1->merge($records_2)->all();

        ///////////

        $internment_1=Internment::with(['record'])->whereDoesntHave('Release')->get();
        $internment_2=Internment::with(['record','release'])->whereHas('release',function($q){
            $q->where('paid_out',0);
        })->get();;

        $internments=$internment_1->merge($internment_2)->all();

        return view('reports.outstanding_report')->with('records',$records)->with('internments',$internments); 
    } 

    public function do_outstanding_report(Request $request){
        
    }
    */

    public function fichaMatricula($id){
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $enrollment=Enrollment::with([ 'career.group', 'student.school.district','student.entity.district','relative.entity','classroom.level.level_type','classroom.level.period','user.entity','installments.payments'])->whereId($id)->firstOrFail();
        //return compact('enrollment');
        $pdf = PDF::loadView('reports.pdf.ficha_matricula_academia',compact('enrollment'));          
        return $pdf->stream();
    }

    public function alumnos_y_apoderados(){
        
        $periods= $this->period_EJB->getPeriodosAndLevels();
        return view('reports.students_and_parents.index', compact('periods'));
    }
    
    public function show_alumnos_y_apoderados($id, $descargar=null){
        $data = $this->period_EJB->getStudentsAndParents($id);
        if($descargar){
            return view('reports.excel.report_students_parents', compact('data'));
            return $data;
        }
        return view('reports.students_and_parents.partials.table_alumnos_apoderados', compact('data'));
    }


    public function reportIncidentes(){
        
        //Buscar alumnos
        $tipos = Type_code::all();
        return view('incidentes.report')->with('niveles', $tipos);
        


        $periodo = Period::where('active', 1)->first();
        $levels = null;
        if($periodo){
            $levels = $periodo->classrooms;
            //return $levels;
        }
        return view('incidentes.report')->with('levels', $levels);
    }

    public function incidentesGeneral(Request $request){
        // Validacion
        /* $request->validate([
            'period_id'=> 'required | string | min:3',
        ]); */



        return $request->all();

        // retornar vista

    }

    public function incidentesEspecifico(Request $request){
        // Validacion
        return 'hoa';


        //Buscar alumnos
        


        // retornar vista

        return $request->all();
    }


}
