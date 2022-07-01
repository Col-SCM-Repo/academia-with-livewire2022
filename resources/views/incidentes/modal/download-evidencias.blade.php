    <!-- Begin Modal: Evidencias -->
    <div class="modal fade" id="intervalo-reporte-modal" tabindex="-1" aria-labelledby="EvidenciasModalLabel" aria-hidden="true">
        <div class="modal-dialog " id="modal-evidencias" >
            <form class="modal-content" method="POST" action="{{ action('IncidenteController@reportIncidentes') }}" id="form-evidencias-report"  v-on:submit="descargarReporteIncidencias($event)">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">REPORTES </h5>
                    <button type="button"  style="cursor: pointer !important;" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0 pb-0 "  >
                    @csrf
                    <input type="hidden" name="id_incidente" >
                    <div class="row d-flex ">
                        <div class="col-12 row mt-3">
                            <label for="f_inicio" class="col-4 text-right " >Fecha inicio</label>
                            <div class="col-8 d-flex ">
                                <input disabled type="date" name="fInicio" id="f_inicio" style="width: 100%;" required>
                            </div>
                        </div>
                        <div class="col-12 row mt-3">
                            <label for="f_fin" class="col-4 text-right">Fecha fin</label>
                            <div class="col-8 ">
                                <input disabled type="date" name="fFin" id="f_fin" style="width: 100%;" required>
                            </div>
                        </div>

                        <div class="col-12 text-right pr-5 mt-1">
                            <span style="font-size: .75em">
                                <label for="f_fin" >Buscar en todo el periodo matriculado</label>
                                <input checked type="checkbox" name="todoPeriodo" id="todo-periodo" >
                            </span>    
                        </div>

                        <div class="col-12 row mt-3">
                            <label class="col-4 text-right">Filtrar</label>
                            <div class="col-8 row" style="font-size: .9em;">

                                <div class="col-5">
                                    <input checked type="checkbox" id="cbx-todos" name="cbxTodos"> <label for="cbx-todos">Todos</label><br>
                                </div>
                                <div class="col-7">
                                    <input checked type="checkbox" id="cbx-inasistencia" name="cbxInasistencia">  <label for="cbx-inasistencia">Inasistencia</label><br>
                                </div>
                                <div class="col-5">
                                    <input checked type="checkbox" id="cbx-tardanza" name="cbxTardanza">  <label for="cbx-tardanza">Tardanza</label><br>
                                </div>
                                <div class="col-7">
                                    <input checked type="checkbox" id="cbx-comportamiento" name="cbxComportamiento">  <label for="cbx-comportamiento">Comportamiento</label><br>
                                </div>
                                <div class="col-5">
                                    <input checked type="checkbox" id="cbx-notas" name="cbxNotas">  <label for="cbx-notas">Notas</label><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-2 text-right">
                    </div>
                </div>
                
                <div class="modal-footer d-block text-center">
                    <button type="submit" class="btn btn-primary">Descargar</button>
                </div>
            </form>
        </div>

    </div>
    <!-- End Modal: Evidencias -->



    