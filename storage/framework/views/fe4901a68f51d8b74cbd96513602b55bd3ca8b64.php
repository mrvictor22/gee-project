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
                        <p class="italic"><small>Seleccione el producto a producir, al almacenar el registro le redireccionara a la pantalla
                        principal de la linea de produccion con el calculo completo de el costo de producción*</small></p>
                        <?php echo Form::open(['route' => 'lineaproduccion.store', 'method' => 'post', 'files' => true, 'id' => 'quotation-form']); ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                                                         
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo e(trans('Receta de producto')); ?> *</label>
                                           
                                            <select name="product_id" class="selectpicker form-control" data-live-search="true" id="product_id"  title="Select Product..." required onchange='cambioEmpresa();'>
                                                <?php $__currentLoopData = $lims_supplier_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                
                                                <option value="<?php echo e($supplier->id); ?> "><?php echo e($supplier->name . ' Cod:(' . $supplier->code . ')'); ?> </option>
                                                
                                                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                         <!--input type="text" name="id_producto" class="form-control" step="any" id="showId"-->
                                                     </select>
                                                     

                                                 </div>
                                             </div>
                                             <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo e(trans('Producto final para venta')); ?> *</label>
                                           
                                            <select name="product_id_venta" class="selectpicker form-control" data-live-search="true" id="product_id"  title="Select Product..." required onchange='cambioEmpresa();'>
                                                <?php $__currentLoopData = $lims_supplier_list2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                
                                                <option value="<?php echo e($supplier2->id); ?> "><?php echo e($supplier2->name . ' Cod:(' . $supplier2->code . ')'); ?> </option>
                                                
                                                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                         <!--input type="text" name="id_producto" class="form-control" step="any" id="showId"-->
                                                     </select>
                                                     

                                                 </div>
                                             </div>
                                               
                                                     

                                                     
                                         <div class="col-md-4">
                                             <div class="form-group">
                                        <label><?php echo e('Libras a producir'); ?>*</strong> </label>
                                        <input type="number" name="libras_a_prod" class="form-control" step='0.01'>                                                                              
                                             </div>
                                                      </div>


                                </div>
                            
                               
		                       
		                        <div class="row">
                                <div class="col-md-4">
                                                     <div class="form-group">
                                                     <label><?php echo e('Leche usar'); ?> </strong> </label>
                                                         <input type="number" name="leche_usar"  class="form-control" step='0.01'>                                                                              
                                                     </div>
                                                 </div>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo e("Acidez de la leche"); ?> </strong> </label>
                                        <input type="number" name="acidez" class="form-control" step='0.01'>                                                                              
                                    </div>
                                     </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo e('Peso de la leche'); ?></label>
                                            <input type="number" name="peso" class="form-control" step='0.01'>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo e('Temperatura de la leche'); ?></label>
                                            <input type="number" name="temperatura" class="form-control" step='0.01'>
                                        </div>
                                    </div>
                                    

                                     
                               
                                </div>
                                <div class="card-header d-flex align-items-center">
                                        <h5><?php echo e(trans('file.Costo')); ?> </h5>
                                        </div>
                                
                              
                                <div class="row">
                               
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo e(trans('file.ntrabajadores')); ?> </strong> </label>
                                        <input type="number" name="ntrabajadores" class="form-control" step="any">
                                                                             
                                    </div>
                                </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Otros Costos</label>
                                            <input type="number" name="otros_costos" class="form-control" step="any">
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                    <div class="form-group">
                                            <label>Nota de la produccion</label>
                                            <textarea rows="3" class="form-control" name="nota_prod"></textarea>
                                        </div>
                                    </div>

                                                                 
                                        
                                    <hr/>
                                   
                                </div>       
                                <div class="card-header d-flex align-items-center">
                                     <h5><?php echo e(trans('file.Estado')); ?> </h5>
                                    </div>                          
                              
                               
                                
                                <div class="row">
                                	<div class="col-md-4">
                                		<div class="form-group">
                                			<label><?php echo e(trans('file.Estado')); ?></label> 
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
                                            <input type="number" name="pesof" class="form-control" step="any">
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

	$('.selectpicker').selectpicker({
    	style: 'btn-link',
	});
    

    $('[data-toggle="tooltip"]').tooltip();

    function cambioEmpresa()

{
    
    var id_producto = document.getElementById('showId').value=document.getElementById('product_id').value;
    


}






</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>