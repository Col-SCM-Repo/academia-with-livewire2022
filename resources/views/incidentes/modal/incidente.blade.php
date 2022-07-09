
    <!-- Begin Modal: Incidentes -->
    <div class="modal fade" id="incidentes-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form class="modal-dialog modal-lg" id="modal-incidentes" v-on:submit="submitIncidente($event)" method="POST" action="{{ action('IncidenteController@store') }}">
            @csrf
            
            <div class="modal-content">
                <div class="modal-header">
                    
                <template v-if="edicion">
                    <h5 class="modal-title text-uppercase" >Actualizando Incidente</h5>
                    <input type="hidden" name="_method" value="put">
                </template>
                <template v-else>
                    <h5 class="modal-title text-uppercase" >Nuevo Incidente</h5>
                </template>

                    <button type="button" class="close" v-on:click="closeModalIncidencias()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row d-flex flex-wrap justify-content-center">
                    <div class="col-sm-10 col-12 row mt-3">
                        <div class="col-3 text-right">Tipo de Incidente</div>
                        <div class="col-8 d-flex">
                            <select class="flex-grow-1" name="tipo_incidente" required>
                                <option value="-">Seleccione una opcion</option>
                                <option value="inasistencia" >Inasistencia</option>
                                <option value="tardanza" >Tardanza</option>
                                <option value="comportamiento" >Comportamiento</option>
                                <option value="notas" >Notas</option>
                            </select>
                            &nbsp;
                            <select class="flex-grow-1" name="justificacion_estado" v-on:change="toggleState($event)" required>
                                <option value="0" >No Justificado</option>
                                <option value="1" >Justificado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-10 col-12 row mt-3" v-show="justificado">
                        <div class="col-3 text-right">Parentesco</div>
                        <div class="col-8">
                            <select name="parentesco" required>
                                <option value="-">Seleccione una opcion</option>
                                <option value="padre" >Padre</option>
                                <option value="madre" >Madre</option>
                                <option value="hermano" >Hermano</option>
                                <option value="hermana" >Hermana</option>
                                <option value="tio(a)" >Tio (a)</option>
                                <option value="abuelo(a)" >Abuelo (a) </option>
                                <option value="otro" >Otro </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-10 col-12 row mt-3">
                        <div class="col-3 text-right">Incidente</div>
                        <div class="col-8" required>
                            <textarea name="text_incidente" class="w-100" rows="5" style="resize: none;" required></textarea>
                        </div>
                    </div>
                    
                    <div class="col-sm-10 col-12 row mt-3" v-show="justificado">
                        <div class="col-3 text-right" >Justificacion</div>
                        <div class="col-8">
                            <textarea name="text_justificacion" class="w-100" rows="5" style="resize: none;" ></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                
                <input type="hidden" name="id_incidente" id="id_incidente">
                <template v-if="edicion">
                    <button type="button" class="btn btn-danger" v-on:click="onClickEliminarIncidente()">Eliminar incidente</button>
                    <button  type="submit" class="btn btn-primary">Actualizar</button>
                </template>
                <template v-else>
                    <button type="button" class="btn btn-secondary" v-on:click="closeModalIncidencias()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Incidente</button>
                </template>

            </div>
            </div>
        </form>
    </div>
    <!-- End Modal: Incidentes -->
