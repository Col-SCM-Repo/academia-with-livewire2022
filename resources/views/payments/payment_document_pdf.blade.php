
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Documento de pago</title>

    <style>
        /* @page { size: 10cm 20cm landscape; } */
        .wrapper{
				/* transform: rotate(180deg);
				
				position:absolute;
				top: 5%;
				left: 0%; */
                top: -10%;
				width: 226pt;
				/* height: 841pt; */

                /* background-color: aqua;

                opacity: 0.3; */

                margin-left: -50px;
                margin-top: -45px;
                
				
				
				
				
				
		}

        table{
            width: 100%
        }

        table,tr,td{
            /* border: solid 1px black; */
            
        }
    </style>
</head>
<body class="wrapper">
    
<table >
        <tr>
            <td colspan="2" style="text-align: center;">
                <span style="font-weight: bold">Academia Preuniversitaria Cabrera</span> <br> <br>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center;">
                <span style="font-weight: bold">R.U.C. 20311341376</span>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                Jr. San Martin 239
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                Cajamarca - Cajamarca - Cajamarca
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                Telf. 367032
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <br>
                ____________________________________
            </td>
        </tr>
        <tr>
            <td style="text-align: left;">
                
                @if ($payment->type == 'ticket')
                    TICKET
                @endif
                @if ($payment->type == 'note')
                    NOTA DE CREDITO
                @endif
            </td>
            <td style="text-align: left;">
                {{$payment->serie}} - {{$payment->numeration}}
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: left;">
                <br>
                <span style="font-weight: bold">Apellidos y Nombres:</span>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: left;">
                {{$payment->installment->enrollment->relative->entity->full_name()}}
            </td>
        </tr>

        <tr>
            <td style="text-align: left;">
                <span style="font-weight: bold">Nro. Matricula:</span>
            </td>
            <td style="text-align: left;">
                {{$payment->installment->enrollment->code}}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left">
                <span style="font-weight: bold">Concepto:</span>
            </td>
        </tr>
        <tr>
            {{-- <td style="text-align: left;">
                Concepto:
            </td> --}}
            <td colspan="2" style="text-align: left;">
                @php
                    $concept='';
                    $period_name=$payment->installment->enrollment->classroom->level->period->name;
                    if ($payment->installment->order == 0) {
                        $concept='Matricula '.$period_name;
                    }else{

                        $payment_=$payment;

                        if ($payment_->concept_type=='none') {
                            $payment_=$payment->payment;
                        }


                        if ($payment_->concept_type=='whole') {
                            $concept= $payment_->installment->order.'.º Cuota '.$period_name;
                        }

                        if ($payment_->concept_type=='partial') {
                            $concept='Adelanto '.$payment_->installment->order.'.º Cuota '.$period_name;
                        }

                       
                    }
                @endphp
                {{$concept}}
            </td>
        </tr>

        <tr>
            <td style="text-align: left;">
                <span style="font-weight: bold">Monto:</span>
            </td>
            <td style="text-align: left;">
                S/ {{$payment->type=='note'?'-':''}}{{$payment->amount}}
            </td>
        </tr>

        <tr>
            <td style="text-align: left;">
                <span style="font-weight: bold">Fecha:</span>
            </td>
            <td style="text-align: left;">
                {{date_format(date_create($payment->created_at),'d/m/Y')}}
            </td>
        </tr>

        <tr>
            <td style="text-align: left;">
                <span style="font-weight: bold">Hora:</span>
            </td>
            <td style="text-align: left;">
                {{date_format(date_create($payment->created_at),'h:i a')}}
            </td>
        </tr>

        <tr>
            <td style="text-align: left;">
                <span style="font-weight: bold">Cajero:</span>
            </td>
            <td style="text-align: left;">
                {{$payment->user->entity->name}}
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: left;">
                <br><br>
                ____________________________________
            </td>
        </tr>
        
    </table>

</body>
</html>

        