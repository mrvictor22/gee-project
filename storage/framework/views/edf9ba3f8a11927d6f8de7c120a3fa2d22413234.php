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
                        <h4><?php echo e(trans('file.Leche por ingresar')); ?></h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small><?php echo e(trans('file.The field labels marked with * are required input fields')); ?>.</small></p>
                        <?php echo Form::open(['route' => 'lecherecibida.store', 'method' => 'post', 'files' => true, 'id' => 'quotation-form']); ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo e(trans('file.Supplier')); ?> *</label>
                                            <select name="supplier_id" class="selectpicker form-control" data-live-search="true" id="supplier-id" data-live-search-style="begins" title="Select Supplier..." required>
                                                <?php $__currentLoopData = $lims_supplier_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($supplier->id); ?>"><?php echo e($supplier->name . ' (' . $supplier->company_name . ')'); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo e(trans('file.Cantidad')); ?> *</label>
                                            <input type="number" name="qty" class="form-control" step="any" required>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-4 form-group">
                                   <label><?php echo e(trans('file.Fecha Ingreso')); ?> *</label>
                                    <input type="text" name="expired_date" class="expired_date form-control">
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
    $("ul#produccion #lecherecibida-menu").addClass("active");

	$('.selectpicker').selectpicker({
    	style: 'btn-link',
	});

    $(".expired_date").val($.datepicker.formatDate('yy-mm-dd', new Date()));

    var expired_date = $('.expired_date');
    expired_date.datepicker({
     format: "yyyy-mm-dd",
     startDate: "<?php echo date('Y-m-d'); ?>",
     autoclose: true,
     todayHighlight: true     
     });

    $('[data-toggle="tooltip"]').tooltip();	

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>