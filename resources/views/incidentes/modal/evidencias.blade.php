
    <!-- Begin Modal: Evidencias -->
    <div class="modal fade" id="evidencias-modal" tabindex="-1" aria-labelledby="EvidenciasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-evidencias" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">EVIDENCIAS</h5>
                    <button type="button" v-on:click="closeModalEvidencias" style="cursor: pointer !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="modal-body pt-0 pb-0" method="POST" action="{{ action('EvidenciaController@store') }}" enctype="multipart/form-data" id="form-evidencias"  v-on:submit="onSubmitEnvidencias($event)" >
                    @csrf
                    <input type="hidden" name="id_incidente" >
                    <div class="row d-flex flex-wrap justify-content-center">
                        <div class="col-sm-10 col-12 row mt-3">
                            <label for="evidencia-descripcion" class="col-3 text-right">Descripcion</label>
                            <div class="col-8 d-flex">
                                <textarea name="descripcion" id="evidencia-descripcion" style="width: 100%" rows="5"  required></textarea>
                            </div>
                        </div>
                        <div class="col-sm-10 col-12 row mt-3">
                            <label for="file_evidencia" class="col-3 text-right">Evidencia</label>
                            <div class="col-8">
                                <input type="file" name="file_evidencia" id="file_evidencia" required>
                            </div>
                        </div>
                    </div>
                    <div class="p-2 text-right">
                        <button type="button" class="btn btn-secondary" v-on:click="closeModalEvidencias" >Cerrar</button>
                        <button type="submit" class="btn btn-primary">Crear Evidencia</button>
                    </div>
                </form>
                <div class="modal-footer d-block">
                    <hr>
                    <div id="container-evidencias" ></div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Modal: Evidencias -->



    