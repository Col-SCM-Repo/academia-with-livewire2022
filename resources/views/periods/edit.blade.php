@extends('templates.inspinia_template')

@section('styles')

<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
<link href="{{ asset('inspinia_admin-v2.9.2/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
@endsection




@section('content')


<div class="container">

    <div class="row">
        
        <div class="col-lg-12">
            <form action="{{action('PeriodController@update')}}" class="form-horizontal" method="POST">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="period_id" value="{{$period->id}}">

            <div class="ibox animated bounceInDown">
                <div class="ibox-title">
                    <h5><i class="fa fa-calendar"></i> EDITAR CICLO</h5>        
                </div>
                
                <div class="ibox-content">
                    
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <input type="text" name="name" id="name" class="form-control" required value="{{$period->name}}">
                            </div>
                        </div>
                        <div class="col-sm-4">  
                            <div class="form-group">
                                <label class="control-label">Año</label>
                                
                                <div class="input-group date" id="datepicker-year">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    
                                    <input type="text" name="year" id="year" class="form-control" required autocomplete="no">
                                </div>
                            </div>                          
                        </div>
                    </div>
                    
                    <div class="hr-line-dashed"></div>


                    <h3>Niveles</h3>
                    <div class="row">

                        @foreach ($level_types as $level_type)
                        
                        <?php
                        $include=$period->levels->where('type_id',$level_type->id)->isNotEmpty();
                        $level=null;
                        $count=0;
                        if($include){
                            $level=$period->levels->where('type_id',$level_type->id)->first();
                            // $count=$period->levels->where('type_id',$level_type->id)->first()->enrollments->count();
                            $count=$level->enrollments->count();
                        }
                        ?>
                        

                        <div class="col-sm-3" style="{{$count>0?'display:none':''}}">
                            <div class="form-group">
                                <label for="id-check-{{$level_type->id}}" class="control-label">{{$level_type->description}}</label>
                                <input type="checkbox" class="i-checks" id="id-check-{{$level_type->id}}" name="checks[]" {{$include?'checked':''}} value="{{$level?$level->id:$level_type->id}}" style="{{$count>0?'display:none':''}}">
                            </div>
                        </div>

                        @endforeach


                    </div>

                    @foreach ($level_types as $level_type)

                    <?php
                        $include=$period->levels->where('type_id',$level_type->id)->isEmpty();
                        $level=null;
                        if(!$include){
                            $level=$period->levels->where('type_id',$level_type->id)->first();
                        }
                    ?>
                    
                    <div class="row" id="panel-{{$level?$level->id:$level_type->id}}" style="display: {{$include?'none':''}}">
                        
                        <div class="col-sm-12">
                            <div class="hr-line-dashed"></div>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{$level_type->description}}</h4>
                        </div>


                        
                       

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label">Fecha Inicio</label>
                                
                                <div class="input-group date datepicker" id="{{$include?'':'datepicker-start-'.$level->id}}">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>                                    
                                    <input type="text" name="{{$include?'start_date[]':'edit_start_date[]'}}" class="form-control" {{$include?'disabled':''}} required autocomplete="no">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label">Fecha Fin</label>
                                <div class="input-group date datepicker" id="{{$include?'':'datepicker-end-'.$level->id}}">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>                                    
                                    <input type="text" name="{{$include?'end_date[]':'edit_end_date[]'}}" class="form-control" {{$include?'disabled':''}} required autocomplete="no">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label">Costo de Ciclo (S/)</label>
                                <input type="number" min="0" style="0.01" name="{{$include?'price[]':'edit_price[]'}}" {{$include?'disabled':''}} value="{{$include?'':$level->price}}" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            
                        </div>

                        @if (!$include)

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">AULAS</label>
                                    <p id="add_ie" style="display: inline"><a href="javascript:void(0)" onclick="show_add_classroom_modal({{$level->id}})"> <i class="fa fa-plus"></i> </a></p>
                                </div>
                            </div>

                            <div class="col-sm-6" id="{{$include?'':'classroom-listing-'.$level->id}}">
                                <table class="table">
                                    <thead>
                                        <th>Nombre</th>
                                        <th>Vancantes</th>
                                        <th></th>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

                        @endif


                        @if ($level)
                            <input type="hidden" name="level_id[]" class="level_id" value="{{$level->id}}">
                            <input type="hidden" name="deleted_level_id[]" disabled class="deleted_level_id">
                        @else
                            <input type="hidden" name="level_type_id[]" disabled class="level_type_id">
                        @endif


                    </div>

                    @endforeach

                </div>

                <div class="ibox-footer">
                    <div class="col-sm-4 col-sm-offset-8">
                        <button id="submit" class="btn btn-primary" type="submit">Actualizar</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
        
        
        

        
    </div>

    {{-- <div class="row">


    </div> --}}

</div>


<div class="modal inmodal fade" id="add_classroom_modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <div class="modal-content" id="add_classroom_modal_body">
            
        </div>
    </div>
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
            
            $('#panel-'+value).find('.deleted_level_id').attr('disabled',true);
            $('#panel-'+value).find('.deleted_level_id').val('');
            $('#panel-'+value).find('.level_type_id').val(value);
            

            
        }else{
            $('#panel-'+value).css('display','none');
            $('#panel-'+value).find('.level_type_id').val('');
            
            $('#panel-'+value).find('.deleted_level_id').attr('disabled',false);
            $('#panel-'+value).find('.deleted_level_id').val(value);
            $('#panel-'+value).find('input[type!="hidden"]').prop('disabled',true);
            $('#panel-'+value).find('.level_id').prop('disabled',true);
            
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

    $("#datepicker-year").datepicker( "setDate" , "{{$period->year}}" );
    // $("#datepicker").datepicker( "setDate" , "{{date_format(date_create(),'d/m/Y')}}" );
  
    function classroom_listing(level_id){
        // $('.ibox').children('.ibox-content').toggleClass('sk-loading');
        $.get("{{action('ClassroomController@listing',['level_id'=>''])}}/"+level_id, function(data) {
		    $("#classroom-listing-"+level_id).html(data);
            // set_dataTable();
            // $('.ibox').children('.ibox-content').toggleClass('sk-loading');
	    });
    }

    function show_add_classroom_modal(level_id){

        $.ajax({
            type: "get",
            url: "{{ action('ClassroomController@create',['level_id'=>'']) }}/"+level_id,     
            success: function(data){
                setModal_addClassroom(data);             
            }                
        });  

    }

    function setModal_addClassroom(data){

        $('#add_classroom_modal_body').html(data);  

        $('#add_classroom_modal').on('shown.bs.modal',function(){
            $('#classroom_name').focus();
        });

        $('#add_classroom_modal').modal({
            backdrop: 'static',
            keyboard: false
        });       



        $('#classroom_form').unbind();
        $('#classroom_form').submit(function(e){
            e.preventDefault();
            
            
            var url = $(this).attr('action');
            var form_data = $(this).serialize();

            $.ajax({
                type: "post",
                url: url,   
                dataType:'json',             
                data    : form_data,   
                success: function(data){
                    console.log(data);
                    if(data.success){
                        var id=data.level_id;
                        $('#add_classroom_modal').modal('hide');
                        classroom_listing(data.level_id);
                    }else{
                        alert('Error');
                    }
                    
                        
                }
            });

        });

    }
    

    function delete_classroom(classroom_id){

        var r = confirm("¿Desea eliminar esta Aula?");
        if ( r ) {

            arr={id:classroom_id,_token:"{{ csrf_token() }}"}
            $.ajax({
                type: "post",
                dataType:'json',       
                data: arr,
                url: "{{action('ClassroomController@destroy')}}",
                success: function(data){

                    console.log(data);
                    
                    if (data.success) {
                        classroom_listing(data.level_id);
                        alert("Aula Eliminada.");
                    }else{
                        alert("El Aula no puede ser eliminada, ya que tiene alumnos matriculados.");
                    }
                        
                    
                }                
            });

        }
    }

</script>

@foreach ($period->levels as $level)

<script>


$("#datepicker-start-{{$level->id}}").datepicker( "setDate" , "{{date_format(date_create($level->start_date),'d/m/Y')}}" );
$("#datepicker-end-{{$level->id}}").datepicker( "setDate" , "{{date_format(date_create($level->end_date),'d/m/Y')}}" );

classroom_listing({{$level->id}});


</script>
    
@endforeach


@endsection