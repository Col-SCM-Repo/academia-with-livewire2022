<div>

    @push('styles')
        <link href="{{ asset('inspinia_admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
        <link href="{{ asset('inspinia_admin/css/plugins/steps/jquery.steps.css') }}" rel="stylesheet">

        <style>
            .wizard .content {
                min-height: 100px;
            }
            .wizard .content > .body {
                width: 100%;
                height: auto;
                padding: 15px;
                position: absolute;
            }
            .wizard .content .body.current {
                position: relative;
            }

        </style>
        
        <link href="{{ asset('inspinia_admin/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
    @endpush

    @push('scripts')
        <!-- Steps -->
        <script src="{{ asset('inspinia_admin/js/plugins/steps/jquery.steps.min.js') }}"></script>

        <!-- Jquery Validate -->
        <script src="{{ asset('inspinia_admin/js/plugins/validate/jquery.validate.min.js') }}"></script>

        <!-- Data picker -->
        <script src="{{ asset('inspinia_admin/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

        <!-- Typehead -->
        <script src="{{ asset('inspinia_admin/js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>

        <script>
            var settings = {
                onFinished: function (event, currentIndex) {
                    toastr.success('Se creo la matricula correctamente', 'Alerta')
                },
                labels: {
                    cancel: "Cancelar",
                    current: "Siguiente paso:",
                    finish: "Finalizar",
                    next: "Siguiente",
                    previous: "Anterior",
                    loading: "Cargando"
                }
            };
            var wizard = $("#wizard").steps(settings);
        </script>

    @endpush

    @section('header')
    <div class="col-md-5">
        <h2>Nueva matricula</h2>
        <ol class="breadcrumb">
            <li>
                <a href="">Home</a>
            </li>
            <li>
                <a>Matricula</a>
            </li>
            <li class="active">
                <strong>Nueva matricula</strong>
            </li>
        </ol>
    </div>
    @endsection


    <div class="row" >
        <div class="col-lg-12">
            <div class="ibox" style="min-width: 60vh;">
                <div class="ibox-content">
                    <div id="wizard">
                        <h1>Crear alumno</h1>
                        <div>
                            @livewire('matricula.partials.alumno')
                        </div>
                        <h1>Crear apoderado</h1>
                        <div>
                            @livewire('matricula.partials.apoderado')
                        </div>
                        <h1>Matricular</h1>
                        <div>
                            @livewire('matricula.partials.matricula')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
