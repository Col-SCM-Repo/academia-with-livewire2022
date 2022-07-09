<nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                                <?php

                                    switch(Auth::user()->genre){
                                        case 'm': $file='user_male.png';break;
                                        case 'f': $file='user_female.png';break;
                                    }

                                ?>
                                <img alt="image" class="img-circle" src="{{ asset('inspinia/img/'.$file) }}">
                                 </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear">
                            <span class="block m-t-xs">
                                <strong class="font-bold">{{ Auth::user()->full_name }}</strong>
                            </span>
                            <span class="text-muted text-xs block">
                                <?php

                                switch(Auth::user()->type){
                                    case 'member': echo 'Recepcionista';break;
                                    case 'admin': echo 'Administrador';break;
                                    case 'super_admin': echo 'Super Usuario';break;
                                }

                                ?>
                                <b class="caret"></b>
                            </span>
                        </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="{{ action('AdministratorController@main') }}">Panel principal</a></li>
                            <li class="divider"></li>
                            <li>
                                    <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form-side').submit();">
                                       Cerrar Sesión
                                </a>
                                <form id="logout-form-side" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        HMA
                    </div>
                </li>
                <li id="reservations-control">
                    <a href="#"><i class="fas fa-calendar-week" aria-hidden="true"></i> <span class="nav-label">Reservaciones</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">

                        <li id="history"><a href="{{ action('ReservationController@history') }}">Historial</a></li>
                        <li id="schedule"><a href="{{ action('ReservationController@resume') }}">Resumen</a></li>

                    </ul>
                </li>
                <li id="check-control">
                    <a href="{{ action('RoomController@checkInOut') }}"><i class="fas fa-exchange-alt" aria-hidden="true"></i> <span class="nav-label">Check in / Check out</span> </a>
                    {{-- <ul class="nav nav-second-level">
                        <li id="schedule"><a href="">Resumen</a></li>
                        <li id="history"><a href="">Historial</a></li>

                    </ul> --}}
                </li>

                <li id="clients-control">
                    <a href="{{ action('ClientController@index',['p'=>'view']) }}"><i class="fas fa-users" aria-hidden="true"></i> <span class="nav-label">Control de Clientes</span> </span></a>
                </li>

                {{-- <li id="clients-control">
                    <a href=""><i class="fas fa-users" aria-hidden="true"></i> <span class="nav-label">Control de Clientes</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li id="clients"><a href="{{ action('ClientController@index',['p'=>'view']) }}">Personas</a></li>
                        <li id="companies"><a href="{{ action('CompanyController@index',['p'=>'view']) }}">Empresas</a></li>

                    </ul>
                </li> --}}
                <li id="rooms-control">
                    <a href=""><i class="fas fa-bed" aria-hidden="true"></i> <span class="nav-label">Control Habitaciones</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li id="rooms"><a href="{{ action('RoomController@index',['p'=>'view']) }}">Habitaciones</a></li>
                        <li id="room_types"><a href="{{ action('RoomTypeController@index',['p'=>'view']) }}">Tipos de habitacion</a></li>

                    </ul>
                </li>
                <li id="user-control">
                    <a href=""><i class="fa fa-cog fa-spin fa-x fa-fw" aria-hidden="true"></i> <span class="nav-label">Mantenimiento</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li id="user-menu"><a href="{{ action('UserController@index',['p'=>'view']) }}">users</a></li>
                        {{-- <li id="configuration-menu"><a href="{{ action('RoomTypeController@index',['p'=>'view']) }}">Configuraciones</a></li> --}}

                    </ul>
                </li>

            </ul>

        </div>
    </nav>
