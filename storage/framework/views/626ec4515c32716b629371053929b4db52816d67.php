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
            font-size: 11px;
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
        /*
        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }
        tr {border-bottom: 1px dotted #ddd;}
        td,th {padding: 7px 0;width: 50%;}

        table {width: 100%;}
        tfoot tr th:first-child {text-align: left;}
        
        */
       
       /* td,
        th,
        tr,table{
            border:1px solid black;
        }*/
        .centered {
            text-align: center;
            align-content: center;
        }
        small{font-size:11px;}

        @media  print {
            * {
                font-size:12px;
                line-height: 20px;
            }
            td,th {padding: 0px 0;}
            .hidden-print {
                display: none !important;
            }
            @page  { margin: 0; } body { margin: 0.5cm; margin-bottom:1.6cm; } 
        }
        table, th, td{
            border: 1px solid white;
        }
    </style>
  </head>
<body>

<!--div style="max-width:400px;margin:0 auto" -->
        <div>
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
        </table>
        <br>
    </div>
       
<!--INICIO DE CREDITO FISCAL-->
<div id="receipt-data">

        <table style="max-width:750px;margin:0 auto; " border="1px" width="750px"  cellpadding="1px" cellspacing="0px" bgcolor="beige">
        <tr height="135px">
        <td colspan="8">&nbsp;</td>
        </tr>
  
        <!--tr>
            <td>A1</td>
            <td>B1</td>
            <td>C1</td>
            <td>D1</td>
            <td>E1</td>
            <td>F1</td>
            <td>G1</td>
            <td>H1</td>
        </tr-->

                <tr>
               <!-- <td></td> -->
                <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo e($lims_customer_data->name); ?></td>
                
                <td colspan="3" style="text-align:left;"><?php echo e($lims_sale_data->created_at->format('d-m-Y')); ?></td>
               
                </tr>

                <tr >
             <!--   <td></td>-->
                <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo e($lims_customer_data->address); ?></td>
                <td  colspan="3" style="text-align:left;">&nbsp;<?php echo e($lims_customer_data->registro); ?></td> <!--registro-->
               <!-- <td></td> -->
                </tr>

                <tr >
              <!--  <td></td>-->
                <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo e($lims_customer_data->city); ?></td> <!--Municipio / Departamento-->
                <td  colspan="3" style="text-align:left;" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo e($lims_customer_data->nit); ?></td><!--NIT-->
               <!-- <td></td> -->
                </tr>
                
                <tr>
             <!--   <td></td>-->
                <td colspan="3" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><!--Extranjero pasarte/carnet de residencia-->
                <td colspan="3" style="text-align:left;"><?php echo e($lims_customer_data->giro); ?></td>
                </tr>

                <tr>
             <!--   <td></td>-->
                <td colspan="3" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><!--Extranjero pasarte/carnet de residencia-->
                <td colspan="3"></td>
                </tr>
                <tr>
             <!--   <td></td>-->
                <td colspan="3" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><!--Extranjero pasarte/carnet de residencia-->
                <td colspan="3"></td>
                </tr>


              <!--table style="max-width:750px;max-height:163.5px; margin:0 auto; " border="1px" width="750px  " height="163.5px" cellpadding="2px" cellspacing="3px" bgcolor="beige"-->
        <tr style="min-height:30px; max-height:30px; height:30px;">
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <!-- <td></td> -->
       
           
            
        </tr>
                <?php $__currentLoopData = $lims_product_sale_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product_sale_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php 
                                    $lims_product_data = \App\Product::find($product_sale_data->product_id);
                                    if($product_sale_data->variant_id) {
                                        $variant_data = \App\Variant::find($product_sale_data->variant_id);
                                        $product_name = $lims_product_data->name.' ['.$variant_data->name.']';
                                    }
                                    else
                                        $product_name = $lims_product_data->name;
                                ?>

               
                <tr>
               <!-- <td></td>-->
                <td width="35px" class="centered">&nbsp;<?php echo e($product_sale_data->qty); ?></td>
                
                <td width="180px"> &nbsp;&nbsp;&nbsp;<?php echo e($product_name); ?></td>
                <td width="45px"><?php 
                $dato_iva= 1.13;
                $sacandoiva = $product_sale_data->net_unit_price / $dato_iva;
                echo number_format($sacandoiva, 2, '.', '') ;
                                     
                ?> </td> <!--number_format((float)($product_sale_data->total / $product_sale_data->qty), 2, '.', '')-->
                <td width="45px">&nbsp;</td><!--Ventas <br>No sujetas-->
                <td width="45px">&nbsp;</td><!--Ventas <br>Exentas-->
                <td width="45px">
                <?php 
               $ventasafectas = round($sacandoiva, 8,PHP_ROUND_HALF_UP) * $product_sale_data->qty ;
                
               echo number_format($ventasafectas, 2, '.', '') ;
               $vn[] = $ventasafectas;
                ?>
                </td>
                </tr>
                <!--35.375 alto x cd fila, max 8 filas   47.375-->
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($lims_sale_data->shipping_cost): ?>
                <tr>
                <td width="35px" class="centered">&nbsp;</td>
                
                <td width="180px"> &nbsp;&nbsp;&nbsp; <?php echo e(trans('file.Shipping Cost')); ?></td>
                <td width="45px"><?php echo e(number_format((float)$lims_sale_data->shipping_cost / 1.13, 2, '.', '')); ?> </td>
                <td width="45px">&nbsp;</td><!--Ventas <br>No sujetas-->
                <td width="45px">&nbsp;</td><!--Ventas <br>Exentas-->
                <td width="45px"><?php echo e(number_format((float)$lims_sale_data->shipping_cost / 1.13, 2, '.', '')); ?></td>
                </tr>
                <?php endif; ?>

                <?php
                $i=0; ?>

                <?php $__currentLoopData = $lims_product_sale_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product_sale_data2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               
                    <?php
                    $i++; 
                    $alto_fila=(8-$i)*21.508;
                    
                
               /* echo $alto_fila;*/
                    ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                <tr style="min-height:<?php echo e($alto_fila); ?>px; max-height:<?php echo e($alto_fila); ?>px; height:<?php echo e($alto_fila); ?>px;">
                <!-- <td></td> -->
                <td colspan="5">&nbsp;<td>
                <!-- <td></td> -->
                </tr>

                <!--/table -->  
                </table>
                <table style="max-width:750px;margin:0 auto; padding:0px; borderspacing:0px; border-collapse: separate;" border="0px" width="750px" bgcolor="beige">
                <tr >
                <td>&nbsp;</td> 
                <td width="35px"></td>
                <td align="left"><?php echo e(str_replace("-"," ",$numberInWords)); ?>  CON <?php echo e($fraction); ?>/100 <?php echo e($general_setting->currency); ?> </td>
                <td  width="45px">&nbsp;</td><!--Sumas-->
                <td  width="45px">&nbsp;</td><!--s-->
                <td  width="45px">&nbsp;</td><!--s-->
              
                <td style="text-align:right;">
                  
                <?php 
                if($lims_sale_data->shipping_cost){
                    $envio_siniva = $lims_sale_data->shipping_cost / 1.13;
                    $total = array_sum($vn) +  $envio_siniva ;
                   
                    echo round($total,2,PHP_ROUND_HALF_UP); 
                } else
                 {
                $total = array_sum($vn);
                echo round($total,2,PHP_ROUND_HALF_UP); 
                }
                ?></td><!--s-->
                <td>&nbsp;</td>
                
                </tr>

                <tr>
                <td></td>
                <td colspan="2"></td>
                <td colspan="3"></td><!--Retencion 1%-->
                <td style="text-align:right;">
                <?php
                $ivasubtotal= $total * 0.13;
                echo number_format((float)($ivasubtotal), 2, '.', '');
                ?>
                </td><!--r-->
                <td></td>
                </tr>

                <tr>
                <td></td>
                <td valing="top" colspan="2" rowspan="4"></td><!--<?php echo e(str_replace("-"," ",$numberInWords)); ?> <?php echo e($general_setting->currency); ?>-->
                <td colspan="3"></td> <!--Subtotal-->
                <td style="text-align:right;">
                    <?php
                    $ivasubtotal2= $total * 1.13;
                    echo number_format((float)($ivasubtotal2),2, '.', '');
                 ?></td>
                <td></td>
                </tr>

                <tr>
                <td></td>
                <td colspan="3"></td><!--Ventas no Sujetas-->
                <td>&nbsp;</td><!--vn-->
                <td></td>
                </tr>

                <tr>
                <td></td>
                <td colspan="3"></td><!--Ventas Exentas-->
                <td>&nbsp;</td><!--ve-->
                <td></td>
                </tr>

                <tr>
                <td></td>
                <td colspan="3"></td><!--TOTAL number_format((float)$lims_sale_data->grand_total, 2, '.', '')   -->
                <td style="text-align:right;"><?php echo e(number_format((float)($ivasubtotal2), 2, '.', '')); ?></td>
                <td></td>
                </tr>
                
                
                
                <tr><td colspan="8">&nbsp;</td></tr>
                </table> 








           </div>
</div>

<script type="text/javascript">/*
    function auto_print() {     
        window.print()
    }
    setTimeout(auto_print, 1000);

   


</script>

</body>
</html>
