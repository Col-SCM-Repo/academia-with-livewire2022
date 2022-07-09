<!-- Recibe installemnts -->
<h3>Pagos</h3>
<div class="ibox-tools">
    @if ($enrollment->payment_status_descripion() == 'pending')
        <span class="label label-danger">Con Deuda</span>
    @endif
    @if ($enrollment->payment_status_descripion() == 'finished')
        <span class="label label-primary">Total Pagado</span>
    @endif
</div>
            
<div class="sk-spinner sk-spinner-rotating-plane"></div>
    <h4>Matricula</h4>
    <div class="hr-line-dashed"></div>
    <div class="row">
    @php
        $enrollment_installment=$enrollment->installments->where('type','enrollment')->first();        
    @endphp
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Monto</th>
                        <th>Fecha de Pago</th>
                        <th>Estado</th>
                        <th></th> 
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            1
                        </td>
                        <td>
                            S/ {{$enrollment_installment->amount}}
                        </td>
                        <td>
                            @if($enrollment_installment->amount==0.00)
                            -
                            @elseif($enrollment_installment->payments->isNotEmpty())
                            {{ date_format(date_create($enrollment_installment->payments->last()->created_at),'d/m/Y')  }}
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if($enrollment_installment->amount==0.00 || $enrollment_installment->amount == $enrollment_installment->balance())
                            <strong style="color: #1ab394"> <i class="fa fa-check"></i> Total Cancelado </strong>    
                            {{-- @elseif($enrollment_installment->payments->isEmpty()) --}}
                            @elseif($enrollment_installment->balance() == 0.00)
                            <strong style="color: #9d2222"> <i class="fas fa-dot-circle"></i> Pendiente de pago</strong>   

                            @endif
                            
                        </td>
                        <td>
                            @if ($enrollment_installment->status() == 'pending')
                            <a href="javascript:void(0)" onclick="show_add_payment_modal({{$enrollment_installment->id}})" style="font-size: 1.2em" title="Abonar a cuota"> <i class="fa fa-plus"></i>  </a>
                            @endif
                            {{-- @if($enrollment_installment->amount > 0.00) --}}
                            <a href="javascript:void(0)" onclick="show_history_payment_modal({{$enrollment_installment->id}},false)"  style="font-size: 1.2em" title="Historial de pagos"> <i class="fa fa-calendar"></i>  </a>
                            {{-- @endif --}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <h4>Cuotas</h4>
    <div class="hr-line-dashed"></div>

    <div class="row">
        @php
            $installments=$enrollment->installments->where('type','installment');        
        @endphp
        
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Monto</th>
                        <th>Fecha de Pago</th>
                        <th>Estado</th>
                        <th></th> 
                    </tr>
                </thead>
                <tbody>
                    @php
                        $anterior = null;
                        $pendiente = true;
                    @endphp
                    @foreach ($installments as $key => $item)
                    
                        <tr>
                            <td>
                                {{$key+1}}
                            </td>
                            <td>
                                S/ {{$item->amount}}
                            </td>
                            <td>
                                @if($item->amount==0.00)
                                -
                                @elseif($item->payments->isNotEmpty())
                                    {{ date_format(date_create($item->payments->last()->created_at),'d/m/Y')  }}
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                @if($item->amount==0.00 || $item->amount == $item->balance())
                                    <strong style="color: #1ab394"> <i class="fa fa-check"></i> Total Cancelado </strong>    
                                @else
                                    <strong style="color: #9d2222"> <i class="fas fa-dot-circle"></i> Pendiente de pago</strong>   
                                @endif
                            </td>
                            <td>
                                <!-- Si es el primero -->
                                @if ($pendiente)
                                    @if ( !$anterior )
                                        @if ($item->status() == 'pending')
                                            <a href="javascript:void(0)" onclick="show_add_payment_modal({{$item->id}})" style="font-size: 1.2em" title="Abonar a cuota">
                                                <i class="fa fa-plus"></i>  
                                            </a>
                                            @php $pendiente = false; @endphp
                                        @endif
                                    <!-- Si no es el primero -->  
                                    @else
                                        @if ($item->status() == 'pending')
                                            <a href="javascript:void(0)" onclick="show_add_payment_modal({{$item->id}})" style="font-size: 1.2em" title="Abonar a cuota">
                                                <i class="fa fa-plus"></i>  
                                            </a>
                                            @php $pendiente = false; @endphp
                                        @endif
                                    @endif
                                @endif
                                {{-- @if($item->amount > 0.00) --}}
                                <a href="javascript:void(0)" onclick="show_history_payment_modal({{$item->id}},false)"  style="font-size: 1.2em" title="Historial de pagos"> <i class="fa fa-calendar"></i>  </a>
                                {{-- @endif --}}
                            </td>
                        </tr>
                        @php $anterior = $item; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>