


<nav class="navbar navbar-expand-lg navbar-static-top" role="navigation">
        <style>
            .dropright:hover > .dropdown-menu {
                display: block;
            }
            .dropright > .dropdown-toggle:active {
                /*Without this, clicking will make it sticky*/
                pointer-events: none;
            }

        </style>

    {{-- <nav class="navbar navbar-expand-lg navbar-static-top navbar-fixed-top" role="navigation"> --}}

        <a href="#" class="navbar-brand">Academia Cabrera</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-reorder"></i>
        </button>

    <div class="navbar-collapse collapse" id="navbar">
        <ul class="nav navbar-nav mr-auto">
            <li class="active">
                <a aria-expanded="false" role="button" href=""> Usuario ({{Auth::user()->type}}):  {{Auth::user()->entity['name']}} {{Auth::user()->entity['father_lastname']}}</a>
            </li>
            <li class="dropdown" id="menu-enrollments">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Matriculas</a>
                <ul role="menu" class="dropdown-menu">
                    <li id="submenu-enrollments-1"><a href="{{ action('EnrollmentController@main')}}">Buscar Matricula</a></li>
                    <li id="submenu-enrollments-2"><a href="{{ action('EnrollmentController@create')}}">Nueva Matricula</a></li>
                </ul>
            </li>
            <li class="dropdown" id="menu-maintenance">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Mantenimiento</a>
                <ul role="menu" class="dropdown-menu">
                    <li id="submenu-maintenance-1"><a href="{{action('PeriodController@index')}}">Ciclos y Aulas</a></li>
                    <li id="submenu-maintenance-1"><a href="">Alumnos</a></li>
                    
                </ul>
            </li>
            {{-- <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Liberaciones</a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="#">Menu item</a></li>
                </ul>
            </li> --}}
            <li class="dropdown" id="menu-reports">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Reportes</a>
                <ul role="menu" class="dropdown-menu">
                    <li id="submenu-reports-1"><a href="{{ action('StudentController@classroom_students_report') }}">Alumnos por Aula</a></li>
                    <li id="submenu-reports-2"><a href="{{ action('PaymentController@users_collection_report') }}">Recaudo por usuario</a></li>
                    <li id="submenu-reports-3"><a href="{{ action('EnrollmentController@period_enrollments_report') }}">Alum. por periodo</a></li>
                    <li id="submenu-reports-4"><a href="{{ action('ReportController@alumnos_y_apoderados') }}">Alumnos y apoderados </a></li>
                </ul>
            </li>
            <li class="dropdown" id="menu-incidentes">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Incidencias</a>
                <ul role="menu" class="dropdown-menu">
                    <li id="submenu-incidentes-1"><a href="{{ action('IncidenteController@search') }}">Incidentes</a></li>
                    <li id="submenu-incidentes-1"><a href="{{ action('ReportController@reportIncidentes') }}">Reportes</a></li>
                    <!--
                    <li class="btn-group dropright d-none d-sm-block">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Reportes</a>
                        <ul class="dropdown-menu" style="left: 100px">
                            <li id="submenu-reporte-incidentes-1"><a href="">General</a></li>
                            <li id="submenu-reporte-incidentes-2"><a href="">Especifico</a></li>
                            <li id="submenu-reporte-incidentes-3"><a href="">Por rango</a></li>
                        </ul>
                    </li>
                    <li id="submenu-incidentes-3" class="d-block d-sm-none" ><a href="">Reporte general</a></li>
                    <li id="submenu-incidentes-4" class="d-block d-sm-none" ><a href="">Reporte especifico</a></li>
                -->
                </ul>
            </li>

        </ul>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <a href="{{ route('logout') }}"onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </div>
</nav>


