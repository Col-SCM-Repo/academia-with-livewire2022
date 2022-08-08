<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        <form role="search" class="navbar-form-custom" action="search_results.html">
            <div class="form-group">
                <input type="text" placeholder="Search for something..." class="form-control" name="top-search"
                    id="top-search">
            </div>
        </form>
    </div>
    <ul class="nav navbar-top-links navbar-right">
        <li>
            <span class="m-r-sm text-muted welcome-message"> Bienvenido, {{ Auth::user()->nombreCompleto() }} .</span>
        </li>
        <li>
            <a href="">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="padding: 0; background: transparent; margin: 0; border: none">
                        <i class="fa fa-sign-out"></i> Cerrar Sesión
                    </button>
                </form>
            </a>
        </li>
    </ul>
</nav>