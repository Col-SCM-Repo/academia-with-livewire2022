
<form id="classroom_form" method="post" action="{{action('ClassroomController@store')}}">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">NUEVA AULA</h4>
        {{-- <small class="font-bold">Agregue infracciones a esta acta.</small> --}}
    </div>
    <div  class="modal-body">
    
        <input type="hidden" name="_token" value="{{csrf_token()}}">

        <input type="hidden" name="level_id" value="{{$level_id}}">
    
        
    
            
    
        <div class="row">
    
            <div class="col-2"></div>
            <div class="form-group col-8">
                <label style="font-weight: bold">Nombre</label> 
                <input type="text" name="name" id="classroom_name" class="form-control">

                
            </div>

            

            <div class="col-2"></div>
            
        </div>

        <div class="row">

            <div class="col-2"></div>

            <div class="form-group col-8">
                <label style="font-weight: bold">Total Vacantes</label> 
                <input type="number" min="0" step="1" name="vacancy" id="" class="form-control">

                
            </div>

            <div class="col-2"></div>
        </div>
            
        {{-- <div class="row">
            
            <div class="col-4"></div>
            <div class="form-group col-4">
                <label style="font-weight: bold">Total Pagado</label> <br>
                <strong style="color: #328926;font-size: 1.3em;"> S/ {{number_format($installment->balance() , 2)}}</strong>
            </div>
            <div class="col-4"></div>
            
        </div>
            
        <div class="row">
            
            <div class="col-4"></div>
            <div class="form-group col-4">
                <label style="font-weight: bold">Total Deuda</label> <br>
                <strong style="color: #9f293f;font-size: 1.3em;"> S/ {{ number_format( ( $installment->amount - $installment->balance() ) , 2 )}}</strong>
            </div>
            <div class="col-4"></div>
            
        </div>
            
        <div class="row">
            
            <div class="col-4"></div>
            <div class="form-group col-4">
                <label class="control-label">Monto (S/)</label> <br>
                <input type="number" min="0" step="0.01" required name="amount" id="payment_amount" class="form-control">
            </div>
            <div class="col-4"></div>
            
        </div> --}}
    
        
        
        
    </div>
    
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Registrar</button>
    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
    </div>
    
    </form>
    
    
    
    
    
    
    