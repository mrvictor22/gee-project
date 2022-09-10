<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    <title>{{$general_setting->site_title}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <style type="text/css">
        * {
            font-size: 14px;
            line-height: 20px;
            font-family: 'Ubuntu', sans-serif;
            text-transform: capitalize;
        }
        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor:pointer;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }
        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }
        tr {border-bottom: 0px dotted #ddd;}
        td,th {padding: 0px 0;width: 50%;}

        table {width: 100%;}
        tfoot tr th:first-child {text-align: left;}

        .centered {
            text-align: center;
            align-content: center;
        }
        small{font-size:10px;}

        @media print {
            * {
                font-size:12px;
                line-height: 12px;
            }
            td,th {padding: 0px 0;}
            .hidden-print {
                display: none !important;
            }
            @page { margin: 0; } body { margin: 0.5cm; margin-bottom:1.6cm; } 
        }
    </style>
  </head>
<body>

<div style="max-width:400px;margin:0 auto">
    @if(preg_match('~[0-9]~', url()->previous()))
        @php $url = '../../pos'; @endphp
    @else
        @php $url = url()->previous(); @endphp
    @endif
    <div class="hidden-print">
        <table>
            <tr>
                <td><a href="{{$url}}" class="btn btn-info"><i class="fa fa-arrow-left"></i> {{trans('file.Back')}}</a> </td>
                <td><button onclick="window.print();" class="btn btn-primary"><i class="dripicons-print"></i> {{trans('file.Print')}}</button></td>
            </tr>
        </table>
        <br>
        
    </div>
        
    <div id="receipt-data">
        <div class="centered">
       
         <!--   @if($general_setting->site_logo)
                <img src="{{url('public/logo', $general_setting->site_logo)}}" height="42" width="42" style="margin:10px 0;filter: brightness(0);">
            @endif -->
            
            <h2>Productos Lácteos La Lecheria</h2>
            
            <p>David Antonio Hernandez Sosa
                <br>REG # 158628-3
                <br>NIT 0614-190273-108-8
                <br>Giro vta. Venta de productos lácteos
                <br>5ta av. Sur Barrio San Sebastian #9,
                <br>Apopa, Tel 2216-1854 - 7308-3651
            </p>
        </div>
        <!--METER TABLA AQUI PARA PONER EL CORRELATIVO A LA DERECHA-->
        <div class="centered">
        <table>
            <tr>
                    <td colspan="2" >{{trans('file.Date')}}: {{$fechahoy->toDateTimeString()}}</td>
                    
            </tr>
            <tr>
                    <td>Caja#{{$lims_caja_data->name}}</td>
                    <td style="text-align:right">Corte No.{{$cortesum}}</td>
            </tr>
            <tr>
                    <td collspan="2">Ticket No.Documento</td>
                    
            </tr>
            <tr>
                    <td>Del:{{$ticket_inicio}}</td>
                    <td >Al: {{$ticket_fin}}</td>
            </tr>
        </table>
        </div>
       
        

           
           <div class="centered">
           <h2>Detalle / Totales</h2>
           </div>
        </p>
        <table>
            <tbody>
           
                <tr>
                <td style="text-align:left;vertical-align:bottom">Transacciones: {{$emitidos}}</td>
                <td style="text-align:right;vertical-align:bottom">Total Productos: {{$sum}} </td>
                </tr>
              
            </tbody>
            <tfoot>
          
        </p>
        <table>
           
            <tbody>
                <tr>
                    <th colspan="2" style="text-align:left;vertical-align:bottom">Tot. Gravado</th>
                    <th style="text-align:right">${{$total_venta_ticket}} </th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align:left;vertical-align:bottom">Tot. Excento</th>
                    <th style="text-align:right">$0.00</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align:left;vertical-align:bottom">Sub. Total</th>
                    <th style="text-align:right">${{$total_venta_ticket}}</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align:left;vertical-align:bottom">Total Corte Z</th>
                    <th style="text-align:right">${{$total_venta_ticket}}</th>
                </tr>
            </tbody>
            <tbody>
            <tr>
                    <th colspan="2"><h2>Anulados</h2></th>
                    
                </tr>
           
        
                <tr>
                <td style="text-align:left;vertical-align:bottom">Transacciones: {{$emitidos_nulos}}</td>
                <td style="text-align:right;vertical-align:bottom">Total Productos: {{$sumn}} </td>
                </tr>
                <tr>
                    <th style="text-align:left" colspan="2">Tot. Anulado</th>
                    <th style="text-align:right">${{$total_venta_ticket_nulo}} </th>
                </tr>
              
            </tbody>
        </table>
        <table >
            <tbody>
             
                <tr style="background-color:#ddd;" >
                 <!--   
                DATOS DE RESOLUCION Y SERIE PARA EL TICKET -->
                    <td colspan="2" class="centered" >{{$lims_caja_data->resolucion}}</td>
                </tr>
                <tr style="background-color:#ddd;">
                <td colspan="2" class="centered">CODIGO UNICO {{$lims_caja_data->n_autorizacion}} </td>
                </tr>
                <tr style="background-color:#ddd;">
                <td colspan="2" class="centered">RANGO DEL {{$lims_caja_data->serie_inicio}} AL  {{$lims_caja_data->serie_final}}</td>
                </tr>
                <tr style="background-color:#ddd;">
                <td colspan="2" class="centered">****SERIE AUTORIZADA****</td>
                </tr>
                <tr style="background-color:#ddd;">
                <td colspan="2" class="centered">{{$lims_caja_data->serie_autorizada}} </td>
                </tr>

                <tr style="background-color:#ddd;">
                <td class="centered" colspan="3">Datos del cliente</td>
                
                </tr>
                <tr style="background-color:#ddd;">
                <td >Nombre:</td>
                <td class="centered"></td>
                <td class="centered"></td>
               
                </tr>
                <tr style="background-color:#ddd;">
                <td >DUI-NIT</td>
                      
                <td class="centered"></td>
              
                <td class="centered"></td>
                </tr>
            
            </tbody>
        </table>
        <!-- <div class="centered" style="margin:30px 0 50px">
            <small>{{trans('file.Invoice Generated By')}} {{$general_setting->site_title}}.
            {{trans('file.Developed By')}} LionCoders</strong></small>
        </div> -->
    </div>
</div>

<script type="text/javascript">
/*
    function auto_print() {     
        window.print()
    }
    setTimeout(auto_print, 1000);*/
</script>

</body>
</html>
