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
            line-height: 15px;
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
        td,th {padding: 4px 0;width: 50%;}

        table {width: 100%;}
        tfoot tr th:first-child {text-align: left;}

        .centered {
            text-align: center;
            align-content: center;
        }
        small{font-size:10px;}

        @media  print {
            * {
                font-size:14px;
                line-height: 15px;
            }
            td,th {padding: 4px 0;}
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
        </table>
        <br>
    </div>
        
    <div id="receipt-data">
        
        <div class="centered">
      <!--      <?php if($general_setting->site_logo): ?>
                <img src="<?php echo e(url('public/logo', $general_setting->site_logo)); ?>" height="42" width="42" style="margin:10px 0;filter: brightness(0);">
            <?php endif; ?> -->
            
            <h2>Productos Lácteos </h2>
           
            Orden de compra
            
        </div>
        <div class="centered">

            
               <h2> Cliente: <?php echo e($lims_customer_data->name); ?></h2>
                 <p>Fecha: <?php echo e($lims_sale_data->created_at); ?> </p>
        
            
        </div>
        
        <table>
            <tbody>
            
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
                <tr><td colspan="2"><?php echo e($product_name); ?><br><?php echo e($product_sale_data->qty); ?> x <?php echo e(number_format((float)($product_sale_data->total / $product_sale_data->qty), 2, '.', '')); ?></td>
                    <td style="text-align:right;vertical-align:bottom"><?php echo e(number_format((float)$product_sale_data->total, 2, '.', '')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
               
                <?php if($lims_sale_data->order_tax): ?>
                <tr>
                    <th colspan="2"><?php echo e(trans('file.Order Tax')); ?></th>
                    <th style="text-align:right"><?php echo e(number_format((float)$lims_sale_data->order_tax, 2, '.', '')); ?></th>
                </tr>
                <?php endif; ?>
                <?php if($lims_sale_data->order_discount): ?>
                <tr>
                    <th colspan="2"><?php echo e(trans('file.Order Discount')); ?></th>
                    <th style="text-align:right"><?php echo e(number_format((float)$lims_sale_data->order_discount, 2, '.', '')); ?></th>
                </tr>
                <?php endif; ?>
                <?php if($lims_sale_data->coupon_discount): ?>
                <tr>
                    <th colspan="2"><?php echo e(trans('file.Coupon Discount')); ?></th>
                    <th style="text-align:right"><?php echo e(number_format((float)$lims_sale_data->coupon_discount, 2, '.', '')); ?></th>
                </tr>
                <?php endif; ?>
                <?php if($lims_sale_data->shipping_cost): ?>
                <tr>
                    <th colspan="2"><?php echo e(trans('file.Shipping Cost')); ?></th>
                    <th style="text-align:right"><?php echo e(number_format((float)$lims_sale_data->shipping_cost, 2, '.', '')); ?></th>
                </tr>
                <?php endif; ?>
                 <tr>
                    <th colspan="2"><?php echo e(trans('file.Total')); ?></th>
                    <th style="text-align:right"><?php echo e(number_format((float)$lims_sale_data->grand_total, 2, '.', '')); ?></th>
                </tr>
              
                <tr>
                   
                    <th class="centered" colspan="3"><?php echo e(str_replace("-"," ",$numberInWords)); ?>  CON <?php echo e($fraction); ?>/100 <?php echo e($general_setting->currency); ?></th>
                  
                </tr>
            </tfoot>
        </table>
        <table>
            <tbody>
                <?php $__currentLoopData = $lims_payment_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr style="background-color:#ddd;">
                <td style="padding: 1px;width:30%"> <b><?php echo e(trans('file.Paid By')); ?>: <?php echo e($payment_data->paying_method); ?></td>
                    <td style="padding: 1px;width:40%"><b><?php echo e(trans('file.Amount')); ?>: $<?php echo e(number_format((float)$payment_data->amount + $payment_data->change, 2, '.', '')); ?></td>
                    <td style="padding: 1px;width:30%"><b><?php echo e(trans('file.Change')); ?>: $<?php echo e(number_format((float)$payment_data->change, 2, '.', '')); ?></td>
                </tr>
                <tr>
                <td class="centered" colspan="3"><?php echo e(trans('file.Thank you for shopping with us. Please come again')); ?></td>
                </tr>
                <tr>
                <td class="centered" colspan="3">Este no es un comprobante fiscal</td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <!-- <div class="centered" style="margin:30px 0 50px">
            <small><?php echo e(trans('file.Invoice Generated By')); ?> <?php echo e($general_setting->site_title); ?>.
            <?php echo e(trans('file.Developed By')); ?> LionCoders</strong></small>
        </div> -->
    </div>
</div>

<script type="text/javascript">
    function auto_print() {     
        window.print()
    }
    setTimeout(auto_print, 1000);
</script>

</body>
</html>
