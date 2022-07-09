
{{-- <form id="payment_form" method="post" action=""> --}}

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">HISTORIAL DE PAGOS</h4>
        {{-- <small class="font-bold">Agregue infracciones a esta acta.</small> --}}
    </div>
    <div  class="modal-body">
    
        
            
    
        <div class="row">

            <div class="col-12">
                <table class="footable table table-stripped toggle-arrow-tiny">
                    <thead>
                    <tr>
                        <th data-toggle="true">#</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th>Documento</th>
                        <th></th> 
                        <th data-hide="all">Nota de Crédito</th>
                        <th data-hide="all">Fecha</th>
                        <th data-hide="all">Usuario</th>
                        <th data-hide="all">Documento</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                            $cont=1
                        ?>
                        @foreach ($installment->payments as $key => $payment)
                            
                        
                        <tr>
                            <td>
                                {{$cont}}
                                <?php
                                  $cont++
                                ?>
                            </td>
                            <td>
                                S/ {{$payment->amount}}
                            </td>

                            <td>
                                {{date_format(date_create($payment->created_at),'d/m/Y')}}
                                
                            </td>

                            <td>
                                {{$payment->user->entity->name}} {{$payment->user->entity->father_lastname}}
                                
                            </td>

                            <td>
                                @if ($payment->notes->isEmpty())
                                <strong style="color: #1ab394">Emitido <i class="fa fa-check"></i> </strong>
                                @else 
                                <strong style="color: #9d2222">Revertido <i class="fa fa-redo"></i> </strong>
                                @endif
                            </td>

                            <td style="text-align: center">
                                <a href="javascript:void(0)" onclick="payment_document_pdf({{$payment->id}})" style="font-size: 1.2em"> <i class="fa fa-file"></i> {{$payment->serie.' - '.$payment->numeration}} </a>
                            </td>

                            <td>
                                @if ($payment->notes->isEmpty())
                                    <a href="javascript:void(0)" onclick="revert_payment({{$installment->id}},{{$payment->id}},{{$payment->amount}})" style="font-size: 1.2em"> <i class="fa fa-redo"></i> </a>
                                @endif
                            </td>
                        
                            {{-- ---------------------- --}}

                            @if ($payment->notes->isNotEmpty())
                                
                                @foreach ($payment->notes as $note)
                                
                                <td></td>

                                <td>
                                    {{date_format(date_create($note->created_at),'d/m/Y')}}
                                </td>
    
                                <td>
                                    {{$note->user->entity->name}} {{$note->user->entity->father_lastname}}
                                </td>
    
                                <td>
                                    <a href="javascript:void(0)" onclick="payment_document_pdf({{$note->id}})" style="font-size: 1.2em"> <i class="fa fa-file"></i> {{$note->serie.' - '.$note->numeration}} </a>
                                </td>
    
                               
                                    
                                @endforeach
                                
                            @endif
                           
                        </tr>
                        @endforeach
                        
                    </tbody>
                    
                </table>
            </div>
    
           
            
        </div>
            
       
        
    
        
        
        
    </div>
    
    <div class="modal-footer">
        <button type="submit" class="btn btn-white" data-dismiss="modal">Salir</button>
    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
    </div>
    
    {{-- </form> --}}
    
    
    
    
    
    
    