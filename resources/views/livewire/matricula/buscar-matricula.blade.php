<div>
    @section('header')
    <div class="col-md-3">
        <h2>Buscar matricula</h2>
        <ol class="breadcrumb">
            <li>
                <a href="">Home</a>
            </li>
            <li>
                <a>Matricula</a>
            </li>
            <li class="active">
                <strong>Buscar</strong>
            </li>
        </ol>
    </div>
    @endsection
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content row">
                    <div class="col-sm-10" style="padding-bottom: 3rem">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar matricula por nombres, apellidos o DNI" wire:model="search" > 
                            <span class="input-group-btn"> 
                                <button type="button" class="btn btn-primary">Buscar</button> 
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-10 ">
                        <span><h5>Resultados de busqueda para: {{ $search }} </h5></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
