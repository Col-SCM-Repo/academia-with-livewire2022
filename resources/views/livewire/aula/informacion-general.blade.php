<div>

    @section('header')
    <div class="col-md-6">
        <h2>Alumnos matriculados</h2>
        <ol class="breadcrumb">
            <li>
                <a href="">Home</a>
            </li>
            <li>
                <a>Aulas</a>
            </li>
            <li class="active">
                <a>Información</a>
            </li>
        </ol>
    </div>
    <div class="col-md-6 text-right " style="padding-top: 2rem" >
      {{--   @livewire('common.ciclo-select') --}}
      <button class="btn btn-xs btn-primary" type="button">
            <i class="fa fa-download" aria-hidden="true"></i> Descargar lista alumnos
      </button>
      <button class="btn btn-xs btn-success" type="button">
            <i class="fa fa-download" aria-hidden="true"></i> Descargar resultados examen
      </button>
    </div>
@endsection

@section('content')

    @livewire('aula.informacion-lista-alumnos', ['aula_id' => $aulaId] )

@endsection





</div>
