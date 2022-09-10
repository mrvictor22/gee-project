 <?php $__env->startSection('content'); ?>
<?php if(session()->has('not_permitted')): ?>
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('not_permitted')); ?></div> 
<?php endif; ?>
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><?php echo e(trans('file.Entrada')); ?></h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small><?php echo e(trans('file.The field labels marked with * are required input fields')); ?>.</small></p>
                        <?php echo Form::open(['route' => ['lineaproduccion.update', $lims_quotation_data->id], 'method' => 'put', 'files' => true, 'id' => 'payment-form']); ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">                                                                      
                               
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label><?php echo e(trans('Receta de producto')); ?> *</label>
                                            <input type="hidden" name="product_id_hidden" value="<?php echo e($lims_quotation_data->product_id); ?>" />
                                            <select name="product_id" class="selectpicker form-control" data-live-search="true" id="product_id"  title="Select Product..." required>
                                                <?php $__currentLoopData = $lims_supplier_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($supplier->id); ?>"><?php echo e($supplier->name . ' (' . $supplier->code . ')'); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo e(trans('Producto final para venta')); ?> *</label>
                                            <input type="hidden" name="product_id_venta_hidden" value="<?php echo e($lims_quotation_data->product_id_venta); ?>" />
                                            <select name="product_id_venta" class="selectpicker form-control" data-live-search="true" id="product_id_venta"  title="Select Product..." >
                                                <?php $__currentLoopData = $lims_supplier_list2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($supplier2->id); ?>"><?php echo e($supplier2->name . ' (' . $supplier2->code . ')'); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                                 </div>
                                             </div>
                                    
                                    <div class="col-md-4">
                                    <div class="form-group">
                                    <label><?php echo e('Libras a producir'); ?> </strong> </label>
                                        <input type="number" name="libras_a_prod" value="<?php echo e($lims_quotation_data->libras_a_prod); ?>" class="form-control" step='0.01'>                                                                              
                                    </div>
                                </div>
                                

                                </div>                                 
                                                          
		                       
		                        <div class="row">
                                         
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label><?php echo e('Leche usar'); ?> </strong> </label>
                                        <input type="number" name="leche_usar" value="<?php echo e($lims_quotation_data->leche_usar); ?>" class="form-control" step='0.01'>                                                                              
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label><?php echo e("Acidez de la leche"); ?> </strong> </label>
                                        <input type="number" name="acidez" value="<?php echo e($lims_quotation_data->acidez); ?>" class="form-control" step='0.01'>                                                                              
                                    </div>
                                </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label><?php echo e('Peso de la leche'); ?></label>
                                            <input type="number" name="peso" class="form-control" value="<?php echo e($lims_quotation_data->peso); ?>" step='0.01'>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label><?php echo e('Temperatura de la leche'); ?></label>
                                            <input type="number" name="temperatura" class="form-control" value="<?php echo e($lims_quotation_data->temperatura); ?>" step='0.01'>
                                        </div>
                                    </div>
                               
                            
                                

                                 <!--<div class="card-header d-flex align-items-center">
                                   <h5><?php echo e(trans('file.Costo')); ?> </h5>
                                 </div>-->
                               
                                </div>

                             <hr/>      
                               <div class="card-header d-flex align-items-center">
                                   <h5><?php echo e(trans('file.Costo')); ?> </h5>
                                 </div>
                                <div class="row">
                               
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo e(trans('file.ntrabajadores')); ?> </strong> </label>
                                        <input type="number" name="ntrabajadores" value="<?php echo e($lims_quotation_data->ntrabajadores); ?>" class="form-control" step="any">
                                                                             
                                    </div>
                                </div> 
                                   
                                <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Otros Costos</label>
                                            <input type="number" value="<?php echo e($lims_quotation_data->otros_costos); ?>"  name="otros_costos" class="form-control" step="any">
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                    <div class="form-group">
                                            <label>Nota de la produccion</label>
                                            <textarea rows="3" class="form-control" name="nota_prod" ><?php echo e($lims_quotation_data->nota_prod); ?></textarea>
                                        </div>
                                    </div>
                                                                  

                                   
                                   
                                </div>                                
                                <div class="card-header d-flex align-items-center">
                                     <h5><?php echo e(trans('file.Estado')); ?> </h5>
                                    </div> 
                                <hr/>
                                
                                <div class="row">
                                	<div class="col-md-4">
                                		<div class="form-group">
                                			<label><?php echo e(trans('file.Estado')); ?></label> 
                                            <input type="hidden" name="status_hidden" value="<?php echo e($lims_quotation_data->status); ?>" />
                                			<select class="form-control" name="status">
                                			    <option value="1"><?php echo e(trans('file.Espera')); ?></option>
                                				<option value="2"><?php echo e(trans('file.Terminado')); ?></option>
                                				<option value="3"><?php echo e(trans('En producción')); ?></option>
                                                <option value="4"><?php echo e(trans('file.NoPasa')); ?></option>
                                			</select>
                                		</div>
                                	</div>
                                    

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo e(trans('file.Pesof')); ?></label>
                                            <input type="number" name="pesof" class="form-control" value="<?php echo e($lims_quotation_data->pesof); ?>" step='0.01'>
                                        </div>
                                    </div>
                                	
                                </div>
                           
                                <div class="form-group">
                                    <input type="submit" value="<?php echo e(trans('file.submit')); ?>" class="btn btn-primary" id="submit-button">
                                </div>
                            </div>
                        </div>
                        <?php echo Form::close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>   
   

<script type="text/javascript">
      
    $("ul#produccion").siblings('a').attr('aria-expanded','true');
    $("ul#produccion").addClass("show");
    $("ul#produccion #lineaproduccion-menu").addClass("active");   

var rownumber = $('table.order-list tbody tr:last').index();

var expired_date = $('.expired_date');
    expired_date.datepicker({
     format: "yyyy-mm-dd",
     startDate: "<?php echo date('Y-m-d'); ?>",
     autoclose: true,
     todayHighlight: true     
     });

$('.selectpicker').selectpicker({
    style: 'btn-link',
});

$('[data-toggle="tooltip"]').tooltip();

//assigning value
$('select[name="product_id"]').val($('input[name="product_id_hidden"]').val());
$('select[name="product_id_venta"]').val($('input[name="product_id_venta_hidden"]').val());

$('.selectpicker').selectpicker('refresh');

//assigning value
$('select[name="status"]').val($('input[name="status_hidden"]').val());

$('.selectpicker').selectpicker('refresh');

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>