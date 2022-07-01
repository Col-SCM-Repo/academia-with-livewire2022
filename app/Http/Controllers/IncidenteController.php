<?php

namespace App\Http\Controllers;

use App\EJB\EnrollmentEJB;
use App\EJB\IncidenteEJB;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//tEMPORALES**********************
use PDF;
use App\Enrollment;
use App\Incidente;
class IncidenteController extends Controller
{
    protected $enrollmentEJB, $incidenteEJB;
    public function __construct()
    {
        $this->enrollmentEJB = new EnrollmentEJB();
        $this->incidenteEJB = new IncidenteEJB();
    }

    
    public function index($id)
    {
        $enrollment = Enrollment::with('student.entity')->whereId($id)->firstOrFail();
        //return $enrollment;
        if(!$enrollment) throw new NotFoundHttpException("No se encuentra la matricula");
        return view('incidentes.main', compact('enrollment'));
    }
    
    public function search()
    {
        return view('incidentes.search');
    }
    
    public function search_enrollment($param)
    {
        $enrollment = $this->enrollmentEJB->search_enrollment($param);
        return view('incidentes.partials.search_result')->with('enrollments', $enrollment);
    }

    public function infoIncidentes($id_enrollment){
        return Enrollment::with(['student.entity', 
                                'classroom.level.level_type', 
                                'classroom.level.period', 
                                'incidentes.auxiliar.entity', 
                                'incidentes.secretaria.entity'
                                ])
                            ->whereId($id_enrollment)->firstOrFail();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_enrollment'=> 'min:1',
            'tipo_incidente'=> 'min:3',
            'text_incidente'=> 'required | min:3',
        ]);

        return $this->incidenteEJB->crearIncidente($request);
        //return $request->all();
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_enrollment'=> 'min:1',
            'id_incidente'=> 'min:1',
            'tipo_incidente'=> 'min:3',
            'text_incidente'=> 'required | min:3',
        ]);

        return $this->incidenteEJB->updateIncidente($request, $id);
        //return $request->all();
    }

    
    public function destroy($id)
    {
        $Incidente = Incidente::find($id);
        if(!$Incidente) throw new NotFoundHttpException('No se encuentra el incidente a eliminar');
        $Incidente->delete();
        return $Incidente; 
    }


    public function reportIncidentes(Request $request){
        // Validar
        $request->validate([
            "id_matricula" => 'string | required | min:1',
            "filtro_todos" => 'string | required | min:1',
            "todo_periodo" => 'string | required',
        ]);

        // Obtener data
        $data = (object) Enrollment::with(['incidentes.auxiliar.entity',
                                            'incidentes.secretaria.entity',
                                            'student.entity', 
                                            'classroom.level.period', 
                                            'classroom.level.level_type'  
                                            ])->whereId($request->id_matricula)
                                            ->firstOrFail();

        /**************************** Filtrando - opcines ****************************/
        $incidentesFiltrados = [];
        if($request->filtro_todos != "true" ){
            $tipos = [];
            
            if( $request->filtro_inasistencia == "true" ) $tipos[] = "inasistencia";
            if( $request->filtro_tardanza == "true" ) $tipos[] = "tardanza";
            if( $request->filtro_comportamiento == "true" ) $tipos[] = "comportamiento";
            if( $request->filtro_notas == "true" ) $tipos[] = "notas";
    
            foreach ($data->incidentes as $incidente) {
                if(in_array( $incidente->tipo_incidente ,$tipos))
                $incidentesFiltrados[] = $incidente;
            }

            unset($data->incidentes);
            $data->incidentes = $incidentesFiltrados;
        }


        /**************************** Filtrando - fechas ****************************/
        if($request->todo_periodo == "false" ){
            $tipos = [];
            $f_incio =  \DateTime::createFromFormat('Y-m-d H:i:s', ((string)$request->fecha_inicio).' 00:00:00')  ;
            $f_fin =  \DateTime::createFromFormat('Y-m-d H:i:s', ((string)$request->fecha_fin).' 23:59:59')  ;
            
            $incidentesFiltrados_2 = [];
            foreach ($incidentesFiltrados as $incidente) {
                $fecha = \DateTime::createFromFormat('Y-m-d H:i:s', $incidente->created_at) ;
                if( $fecha > $f_incio && $fecha < $f_fin )
                $incidentesFiltrados_2[] = $incidente;
            }
            unset($data->incidentes);
            $data->incidentes = $incidentesFiltrados_2;
            
            //return $incidentesFiltrados_2;
        }

        //return $data;

        //Generar reporte
        $nombre_documento = 'Reporte-especifico-'.date('y-m-').((string)$data->student->entity->father_lastname).'-'.((string)$data->student->entity->mother_lastname);
        return PDF::loadView('reports.incidentes.pdf.reporte-especifico', compact('data'))
                            ->setPaper('a4', 'landscape')
                            ->stream( $nombre_documento .'.pdf');
    }

}

/*
    public function temp($id){
        // Validar
        // Obtener data
        $data = Enrollment::with(['incidentes.auxiliar.entity',
                                            'incidentes.secretaria.entity',
                                            'student.entity', 
                                            'classroom.level.period', 
                                            'classroom.level.level_type' 
                                            ])->whereId($id)
                                            ->firstOrFail();
        //Generar reporte
        $nombre_documento = 'Reporte-especifico-'.date('y-m-').((string)$data->student->entity->father_lastname).'-'.((string)$data->student->entity->mother_lastname);
        return PDF::loadView('reports.incidentes.pdf.reporte-especifico', compact('data'))
                            ->setPaper('a4', 'landscape')
                            ->stream( $nombre_documento .'.pdf');
    }
*/

