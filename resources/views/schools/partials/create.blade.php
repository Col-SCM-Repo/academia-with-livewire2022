
<form id="school_form" method="post" action="{{action('SchoolController@store')}}">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">NUEVA I.E.</h4>
        {{-- <small class="font-bold">Agregue infracciones a esta acta.</small> --}}
    </div>
    <div  class="modal-body">
    
        <input type="hidden" name="_token" value="{{csrf_token()}}">

    
        
    
            
    
        <div class="row">
    
            <div class="col-2"></div>
            <div class="form-group col-8">
                <label style="font-weight: bold">Nombre</label> 
                <input type="text" name="name" id="classroom_name" class="form-control" required autocomplete="no">

                
            </div>

            

            <div class="col-2"></div>
            
        </div>

        <div class="row">

            <div class="col-2"></div>

            <div class="form-group col-8">
                <label style="font-weight: bold">Distrito</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="school_district" name="school_district" autocomplete="no" required>
                    <input type="hidden" id="hidden_school_district_id" name="school_district_id" value="0">
                    <span id="redo-school-district" class="input-group-btn">
                        <a id="clean-button" onclick="clean('#school_district','#hidden_school_district_id')" style="color:white" title="Limpiar" class="btn btn-primary"><i class="fas fa-redo"></i></a>
                    </span>
                </div>
                
            </div>

            <div class="col-2"></div>
        </div>
            
     
    
        
        
        
    </div>
    
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Registrar</button>
    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
    </div>
    
    </form>
    
    
    
    
    
    
    