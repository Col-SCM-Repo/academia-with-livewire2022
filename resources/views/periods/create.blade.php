@extends('templates.inspinia_template')

@section('styles')

<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
@endsection




@section('content')


<div class="container">

    <div class="row">
        
        <div class="col-lg-12">
            <form action="{{action('PeriodController@store')}}" class="form-horizontal" method="POST">
            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <div class="ibox animated bounceInDown">
                <div class="ibox-title">
                    <h5><i class="fa fa-calendar"></i> NUEVO CICLO</h5>        
                </div>
                
                <div class="ibox-content">
                    
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-4">  
                            <div class="form-group">
                                <label class="control-label">Año</label>
                                
                                <div class="input-group date" id="datepicker-year">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    
                                    <input type="text" name="year" id="year" class="form-control" required value="{{ date('Y')}}" autocomplete="no">
                                </div>
                            </div>                          
                        </div>
                    </div>
                    
                    <div class="hr-line-dashed"></div>


                    <h3>Niveles</h3>
                    <div class="row">

                        @foreach ($level_types as $level_type)
                            
                        

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="id-check-{{$level_type->id}}" class="control-label">{{$level_type->description}}</label>
                                <input type="checkbox" class="i-checks" id="id-check-{{$level_type->id}}" name="checks[]" value="{{$level_type->id}}">
                            </div>
                        </div>

                        @endforeach


                    </div>

                    @foreach ($level_types as $level_type)
                    
                    <div class="row" id="panel-{{$level_type->id}}" style="display: none">
                        
                        <div class="col-sm-12">
                            <div class="hr-line-dashed"></div>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{$level_type->description}}</h4>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label">Fecha Inicio</label>
                                
                                <div class="input-group date datepicker" id="">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>                                    
                                    <input type="text" name="start_date[]" class="form-control" disabled required autocomplete="no">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label">Fecha Fin</label>
                                <div class="input-group date datepicker" id="">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>                                    
                                    <input type="text" name="end_date[]" class="form-control" disabled required autocomplete="no">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label">Costo de Ciclo (S/)</label>
                                <input type="number" min="0" style="0.01" name="price[]" disabled class="form-control" required>
                            </div>
                        </div>


                    </div>

                    @endforeach

                </div>

                <div class="ibox-footer">
                    <div class="col-sm-4 col-sm-offset-8">
                        <button id="submit" class="btn btn-primary" type="submit">Registrar</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
        
        
        

        
    </div>

    {{-- <div class="row">


    </div> --}}

</div>



@endsection

@section('scripts')
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/datapicker/bootstrap-datepicker.es.min.js') }}"></script>
<script src="{{ asset('inspinia_admin-v2.9.2/js/plugins/iCheck/icheck.min.js') }}"></script>
<script>

    

    document.addEventListener('DOMContentLoaded', (event) => {
        document.getElementById('menu-maintenance').classList.add("active");
        document.getElementById('submenu-maintenance-1').classList.add("active");

    });
   
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
 


    $('.i-checks').on('ifChanged', function(event) { 
        var value=event.target.value;
        if(event.target.checked){
            // console.log(event.target.value);            
            $('#panel-'+value).css('display','');
            $('#panel-'+value).find('input').prop('disabled',false);
        }else{
            $('#panel-'+value).css('display','none');
            $('#panel-'+value).find('input').prop('disabled',true);
        }
    });

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        language: 'es',
    });

    

    $('#datepicker-year').datepicker({
        format: "yyyy",
        weekStart: 1,
        orientation: "bottom",
        keyboardNavigation: false,
        viewMode: "years",
        minViewMode: "years"
    });

  

</script>
@endsection