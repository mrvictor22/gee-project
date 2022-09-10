<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="<?php echo e(url('public/logo', $general_setting->site_logo)); ?>" />
    <title><?php echo e($general_setting->site_title); ?></title>
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

        @media  print {
            * {
                font-size:12px;
                line-height: 12px;
            }
            td,th {padding: 0px 0;}
            .hidden-print {
                display: none !important;
            }
            @page  { margin: 0; } body { margin: 0.5cm; margin-bottom:1.6cm; } 
        }
    </style>
  </head>
<body>

<div style="max-width:400px;margin:0 auto">
    <?php if(preg_match('~[0-9]~', url()->previous())): ?>
        <?php $url = '../../pos'; ?>
    <?php else: ?>
        <?php $url = url()->previous(); ?>
    <?php endif; ?>
    <div class="hidden-print">
        <table>
            <tr>
                <td><a href="<?php echo e($url); ?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> <?php echo e(trans('file.Back')); ?></a> </td>
                <td><button onclick="window.print();" class="btn btn-primary"><i class="dripicons-print"></i> <?php echo e(trans('file.Print')); ?></button></td>
            
            </tr>
            <tr>
            <td colspan="2" style="text-align:center"> Tickets del dia</td>
            </tr>
        </table>
        <br>
        
    </div>
    <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div id="receipt-data">
    
        <div class="centered">
       
         <!--   <?php if($general_setting->site_logo): ?>
                <img src="<?php echo e(url('public/logo', $general_setting->site_logo)); ?>" height="42" width="42" style="margin:10px 0;filter: brightness(0);">
            <?php endif; ?> -->
            
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
                    <td colspan="2" ><?php echo e(trans('file.Date')); ?>: <?php echo e($post->fecha_venta); ?> </td>
                    
            </tr>
            <tr>
                    <td>Caja# <?php echo e($lims_biller_data->name); ?> </td>
                    <td style="text-align:right">Corr <?php echo e($post->correlativo_no); ?></td>
            </tr>
        </table>
        </div>
        <div class="centered">
           <h2>Detalle / Totales</h2>
           </div>
                      <?php 
                    $lims_product_data = \App\Product_Sale::where('sale_id', $post->venta_id)->get();
                    ?>

                    <?php $__currentLoopData = $lims_product_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php
                    $lims_product = \App\Product::find($sale->product_id);
                    ?>
                  
                    
           
           
        </p>
        <table>
            <tbody>
    
                <tr><td colspan="2"><?php echo e($lims_product->name); ?><br><?php echo e($sale->qty); ?> x<?php echo e(number_format((float)($sale->total / $sale->qty), 2, '.', '')); ?></td>
                    <td style="text-align:right;vertical-align:bottom">$<?php echo e(number_format((float)$sale->total, 2, '.', '')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
            <?php if($post->shipping_cost): ?>
                <tr>
                    <th colspan="2"><?php echo e(trans('file.Shipping Cost')); ?></th>
                    <th style="text-align:right">$<?php echo e(number_format((float)$post->shipping_cost, 2, '.', '')); ?></th>
                </tr>
                <?php endif; ?>
                <?php if($post->order_discount): ?>
                <tr>
                    <th colspan="2">Descuento:</th>
                    <th style="text-align:right">$<?php echo e(number_format((float)$post->order_discount, 2, '.', '')); ?></th>
                </tr>
                <?php endif; ?>
                <tr>
                    <th colspan="2">Tot. Gravado  </th>
                    <th style="text-align:right">$<?php echo e($post->total_price); ?></th>
                </tr>
                <tr>
                    <th colspan="2">Tot. Excento</th>
                    <th style="text-align:right">$0.00</th>
                </tr>
                <tr>
                    <th colspan="2"><?php echo e(trans('file.Total')); ?></th>
                    <th style="text-align:right">$<?php echo e($post->total_price); ?> </th>
                </tr>
               
                <tr>
                   <?php
                 
                   $numberToWords;
        if(\App::getLocale() == 'ar' || \App::getLocale() == 'hi' || \App::getLocale() == 'vi' || \App::getLocale() == 'en-gb')
            $numberTransformer = $numberToWords->getNumberTransformer('en');
        else
            $numberTransformer = $numberToWords->getNumberTransformer(\App::getLocale());
            $convertir=$post->grand_total;
            $int = (int)$convertir;
            $fraction = ($convertir*100)%100;
          $numberInWords = $numberTransformer->toWords($int);
          $numberInWords2 = $numberTransformer->toWords($fraction);
                   ?>

                    <th class="centered" colspan="3"><?php echo e(str_replace("-"," ",$numberInWords)); ?>  CON <?php echo e($fraction); ?>/100 <?php echo e($general_setting->currency); ?></th>
                  
                </tr>
                <?php
              $lims_pago = \App\Payment::where('sale_id',$post->venta_id)->get();

              ?>
            <?php $__currentLoopData = $lims_pago; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr >
                <td style="padding: 1px;width:30%"><?php echo e(trans('file.Paid By')); ?>:<?php echo e($pago->paying_method); ?>                           </td>
                <td style="padding: 1px;width:40%"><?php echo e(trans('file.Amount')); ?>: $<?php echo e(number_format((float)$pago->amount + $pago->change, 2, '.', '')); ?></td>
                    <td style="padding: 1px;width:30%"><?php echo e(trans('file.Change')); ?>:$<?php echo e(number_format((float)$pago->change, 2, '.', '')); ?></td>
                </tr>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                <td class="centered" colspan="3"><?php echo e(trans('file.Thank you for shopping with us. Please come again')); ?></td>
                </tr>
                
           
            </tfoot>
        </table>
        <table >
            <tbody>
              
                <!--DATOS DE RESOLUCION Y SERIE PARA EL TICKET -->
                <tr style="background-color:#ddd;">
                    <td colspan="2" class="centered" ><?php echo e($lims_biller_data->resolucion); ?></td>
                </tr>
                <tr style="background-color:#ddd;">
                <td colspan="2" class="centered">CODIGO UNICO <?php echo e($lims_biller_data->n_autorizacion); ?> </td>
                </tr>
                <tr style="background-color:#ddd;">
                <td colspan="2" class="centered">RANGO DEL <?php echo e($lims_biller_data->serie_inicio); ?> AL  <?php echo e($lims_biller_data->serie_final); ?></td>
                </tr>
                <tr style="background-color:#ddd;">
                <td colspan="2" class="centered">****SERIE AUTORIZADA****</td>
                </tr>
                <tr style="background-color:#ddd;">
                <td colspan="2" class="centered"><?php echo e($lims_biller_data->serie_autorizada); ?> </td>
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
            <small><?php echo e(trans('file.Invoice Generated By')); ?> <?php echo e($general_setting->site_title); ?>.
            <?php echo e(trans('file.Developed By')); ?> LionCoders</strong></small>
        </div> -->
    </div>
   
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<script type="text/javascript">
/*
    function auto_print() {     
        window.print()
    }
    setTimeout(auto_print, 1000);*/
</script>

</body>
</html>
