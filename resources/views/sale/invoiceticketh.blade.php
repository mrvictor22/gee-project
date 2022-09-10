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
            
            <h2>Productos JB DEVELOPMENT</h2>
            
            <p>David Antonio Hernandez Sosa
                <br>REG # XXXXXXX
                <br>NIT XXXXXXXXXX
                <br>Giro vta. Venta de productos 
                <br>5ta av. Sur Barrio San Sebastian ,
                <br>SAN SALVADOR, Tel 2216-1231 - 7308-1234
            </p>
        </div>
        <!--METER TABLA AQUI PARA PONER EL CORRELATIVO A LA DERECHA-->
        <div class="centered">
        <table>
            <tr>
                    <td colspan="2" >{{trans('file.Date')}}: {{$lims_sale_data->created_at}}</td>
                    
            </tr>
            <tr>
                    <td>Caja#{{$lims_biller_data->name}}</td>
                    <td style="text-align:right">{{$corr_siguiente}}</td>
            </tr>
        </table>
        </div>
       
        
       
           
           <div class="centered">
           <h2>Detalle / Totales</h2>
           </div>
        </p>
        <table>
            <tbody>
                @foreach($lims_product_sale_data as $product_sale_data)
                @php 
                    $lims_product_data = \App\Product::find($product_sale_data->product_id);
                    if($product_sale_data->variant_id) {
                        $variant_data = \App\Variant::find($product_sale_data->variant_id);
                        $product_name = $lims_product_data->name.' ['.$variant_data->name.']';
                    }
                    else
                        $product_name = $lims_product_data->name;
                @endphp
                <tr><td colspan="2">{{$product_name}}<br>{{$product_sale_data->qty}} x {{number_format((float)($product_sale_data->total / $product_sale_data->qty), 2, '.', '')}}</td>
                    <td style="text-align:right;vertical-align:bottom">${{number_format((float)$product_sale_data->total, 2, '.', '')}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
            @if($lims_sale_data->coupon_discount)   
                <tr>
                    <th colspan="2">{{trans('file.Coupon Discount')}}</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->coupon_discount, 2, '.', '')}}</th>
                </tr>
                @endif
                @if($lims_sale_data->shipping_cost)
                <tr>
                    <th colspan="2">{{trans('file.Shipping Cost')}}</th>
                    <th style="text-align:right">${{number_format((float)$lims_sale_data->shipping_cost, 2, '.', '')}}</th>
                </tr>
                @endif
            @if($lims_sale_data->order_discount)
                        @php
                        $total_condescuento = $lims_sale_data->total_price - $lims_sale_data->order_discount;
                        @endphp
                <tr>
                    <th colspan="2">Descuento:</th>
                    <th style="text-align:right">${{number_format((float)$lims_sale_data->order_discount, 2, '.', '')}}</th>
                </tr>
                <tr>
                    <th colspan="2">Tot. Gravado</th>
                    <th style="text-align:right">${{number_format((float)$total_condescuento, 2, '.', '')}}</th>
                </tr>
                @else
                <tr>
    
                    <th colspan="2">Tot. Gravado</th>
                    <th style="text-align:right">${{number_format((float)$lims_sale_data->grand_total, 2, '.', '')}}</th>
                </tr>
                @endif
                
              
                <tr>
                    <th colspan="2">Tot. Excento</th>
                    <th style="text-align:right">$0.00</th>
                </tr>
                <tr>
                    <th colspan="2">{{trans('file.Total')}}</th>
                    <th style="text-align:right">${{number_format((float)$lims_sale_data->grand_total, 2, '.', '')}}</th>
                </tr>
             
               
            
                <tr>
                   
                    <th class="centered" colspan="3">{{str_replace("-"," ",$numberInWords)}}  CON {{$fraction}}/100 {{$general_setting->currency}}</th>
                  
                </tr>
                @foreach($lims_payment_data as $payment_data)
                <tr >
                    <td style="padding: 1px;width:30%"> <b>{{trans('file.Paid By')}}: {{$payment_data->paying_method}}</td>
                    <td style="padding: 1px;width:40%"><b>{{trans('file.Amount')}}: ${{number_format((float)$payment_data->amount + $payment_data->change, 2, '.', '')}}</td>
                    <td style="padding: 1px;width:30%"><b>{{trans('file.Change')}}: ${{number_format((float)$payment_data->change, 2, '.', '')}}</td>
                </tr>
                <tr>
                <td class="centered" colspan="3">{{trans('file.Thank you for shopping with us. Please come again')}}</td>
                </tr>
                
                @endforeach
            </tfoot>
        </table>
        <table >
            <tbody>
                @foreach($lims_payment_data as $payment_data)
                <tr style="background-color:#ddd;" >
                 <!--   <td style="padding: 5px;width:30%">{{trans('file.Paid By')}}: {{$payment_data->paying_method}}</td>
                    <td style="padding: 5px;width:40%">{{trans('file.Amount')}}: {{number_format((float)$payment_data->amount, 2, '.', '')}}</td>
                    <td style="padding: 5px;width:30%">{{trans('file.Change')}}: {{number_format((float)$payment_data->change, 2, '.', '')}}</td>
                DATOS DE RESOLUCION Y SERIE PARA EL TICKET -->
                    <td colspan="2" class="centered" >{{$lims_biller_data->resolucion}}</td>
                </tr>
                <tr style="background-color:#ddd;">
                <td colspan="2" class="centered">CODIGO UNICO {{$lims_biller_data->n_autorizacion}} </td>
                </tr>
                <tr style="background-color:#ddd;">
                <td colspan="2" class="centered">RANGO DEL {{$lims_biller_data->serie_inicio}} AL  {{$lims_biller_data->serie_final}}</td>
                </tr>
                <tr style="background-color:#ddd;">
                <td colspan="2" class="centered">****SERIE AUTORIZADA****</td>
                </tr>
                <tr style="background-color:#ddd;">
                <td colspan="2" class="centered">{{$lims_biller_data->serie_autorizada}} </td>
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
                @endforeach
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
