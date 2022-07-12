<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> 
                    <div class="text-center">
                        <img alt="image" class="img-circle" width="100px;" src="https://scontent.flim10-1.fna.fbcdn.net/v/t1.18169-9/26238769_333394743844244_6939617783275579729_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=GmdspAAer_QAX8QAl6L&_nc_ht=scontent.flim10-1.fna&oh=00_AT_S2z4t074KsUI3FOP9R7iZp-w6_S_y1dq4I1Kwx02H3g&oe=62F2504D" />
                    </div>
                    <br>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">Academia Preuniversitaria Cabrera</strong>
                    </span> <span class="text-muted text-xs block text-capitalize"> {{ Auth::user()->type }} <b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="profile.html">Perfil</a></li>
                        <li class="divider"></li>
                        <li><a href="login.html">Cerrar sesión</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    AC
                </div>
            </li>
            <li>
                <a href="layouts.html"><i class="fa fa-diamond"></i> <span class="nav-label">Dashboard</span></a>
            </li>
            <li>
                <a href="index.html"><i class="fa fa-th-large"></i> <span class="nav-label">Matricula</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="{{ route('matricula.buscar') }}">Buscar</a></li>
                    <li><a href="{{ route('matricula.nueva') }}">Nueva</a></li>
                    <li><a href="{{ route('matricula.pagos') }}">Pagos</a></li>
                    <li><a href="{{ route('matricula.alumno') }}">Alumnos</a></li>
                    <li><a href="{{ route('matricula.apoderado') }}">Apoderados</a></li>
                </ul>
            </li>
            <li>
                <a href="index.html"><i class="fa fa-th-large"></i> <span class="nav-label">Evaluaciones</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="">Option 1</a></li>
                    <li><a href="">Option 2</a></li>
                    <li><a href="">Option 3</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">Mantenimiento</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="">Ciclos y aulas</a></li>
                    <li><a href="">Usuarios</a></li>
                    <li><a href="">Base de datos</a></li>
                </ul>
            </li>
            <li>
                <a href="mailbox.html"><i class="fa fa-envelope"></i> <span class="nav-label">Reportes </span><span class="label label-warning pull-right">16/24</span></a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="">Alumnos por aula</a></li>
                    <li><a href="">Recaudado por usuario</a></li>
                    <li><a href="">Alumnos por periodo</a></li>
                    <li><a href="">Alumnos y apoderados</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-edit"></i> <span class="nav-label">Incidencias</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="">Incidencias</a></li>
                    <li><a href="">Reportes</a></li>
                </ul>
            </li>
            
            {{-- <li>
                <a href="#"><i class="fa fa-sitemap"></i> <span class="nav-label">Menu Levels </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li>
                        <a href="#" id="damian">Third Level <span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li>
                                <a href="#">Third Level Item</a>
                            </li>
                            <li>
                                <a href="#">Third Level Item</a>
                            </li>
                            <li>
                                <a href="#">Third Level Item</a>
                            </li>

                        </ul>
                    </li>
                    <li><a href="#">Second Level Item</a></li>
                    <li>
                        <a href="#">Second Level Item</a></li>
                    <li>
                        <a href="#">Second Level Item</a></li>
                </ul>
            </li> --}}
        </ul>

    </div>
</nav>