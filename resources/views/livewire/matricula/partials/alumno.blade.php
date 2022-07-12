@push('styles')
<link href="{{ asset('inspinia_admin/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
@endpush

<div class="ibox">
<div class="ibox-title">
    <span>
        <h5> {{  true? 'Nuevo alumno' : 'Datos del alumno'}} </h5>
    </span>
</div>
<div class="ibox-content">
    <div class="row ">
        <div class="col-lg-5">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-lg-2 control-label">DNI</label>
                    <div class="col-lg-10">
                        <input type="number" placeholder="Ingrese numero de DNI o carnet de extranjeria " class="form-control"> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">F Nac.</label>
                    <div class="col-lg-10">
                        <div class="input-group ">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" class="form-control" placeholder="01/01/2000" id="f_nacimiento">
                        </div>
                    </div>
                </div>
                <div class="form-group"><label class="col-lg-2 control-label">Telefono:</label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="nombre" class="form-control"> 
                    </div>
                </div>
                <div class="form-group"><label class="col-lg-2 control-label">Distrito</label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="Apellidos" class="form-control">
                    </div>
                </div>
                <div class="form-group"><label class="col-lg-2 control-label">Dirección</label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="Apellidos" class="form-control">
                    </div>
                </div>
                

            </div>
        </div>

        <div class="col-lg-7">
            <div class="form-horizontal">
                <div class="form-group"><label class="col-lg-2 control-label">Nombres:</label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="Ingrese los nombres completos del alumno." class="form-control"> 
                    </div>
                </div>
                <div class="form-group"><label class="col-lg-2 control-label">A.Paterno</label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="Ingrese el apellido paterno del alumno." class="form-control">
                    </div>
                </div>
                
                <div class="form-group"><label class="col-lg-2 control-label">A.Materno</label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="Ingrese el apellido materno del alumno." class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">I.E. Proc.</label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="Ingresa la instituciòn educativa de procedencia" class="form-control"> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Año de egreso</label>
                    <div class="col-lg-10">
                        <div class="input-group ">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" class="form-control" placeholder="01/01/2000" id="datepicker-year">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 ">
            <div class="text-center" style="margin: 5rem" >
                <button class="btn btn-sm btn-primary p-xs" type="submit"> {{  true? 'Guardar' : 'Actualizar'}}  </button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<!-- Data picker -->
<script src="{{ asset('inspinia_admin/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

<script>
$('#f_nacimiento').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true
    });

$('#datepicker-year').datepicker({
format: "yyyy",
weekStart: 1,
orientation: "bottom",
keyboardNavigation: false,
viewMode: "years",
minViewMode: "years"
});
</script


@endpush
</div>


