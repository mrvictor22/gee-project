 <?php $__env->startSection('content'); ?>
<?php if(session()->has('message')): ?>
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo session()->get('message'); ?></div> 
<?php endif; ?>
<?php if(session()->has('not_permitted')): ?>
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('not_permitted')); ?></div> 
<?php endif; ?>

<section>
<div class="card-body">
<p class="italic"><small>*Antes de ingresar un producto a produccion asegurate que el producto final para venta tenga al menos 1 unidad en el almacen de venta, si no lo posee, registralo en el modulo de Compras>>Agregar compra y agrega 1 unidad a ese producto o al grupo de productos que se produciran </small></p>
</div>
    <div class="container-fluid">
        <?php if(in_array("quotes-add", $all_permission)): ?>
            <a href="<?php echo e(route('lineaproduccion.create')); ?>" class="btn btn-info"><i class="dripicons-plus"></i> <?php echo e(trans('file.Agregar')); ?></a>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table id="quotation-table" class="table quotation-list">
            <thead>
                <tr>                    
                    <th class="not-exported"></th>      
                           
                    <th><?php echo e(trans('file.ProdenProd')); ?></th>   
                    <th><?php echo e(trans('Costo de la producción')); ?></th>   
                    <th>Otros Costos</th>   
                    <th>Costo final</th> 
                    <th><?php echo e(trans('Libras a producir')); ?></th>   
                    <th><?php echo e(trans('Peso final')); ?></th>   
                     
                    <th><?php echo e(trans('file.Fecha Ingreso')); ?></th>                 
                    <th><?php echo e(trans('file.Estado')); ?></th>
                    <th class="not-exported"><?php echo e(trans('file.action')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $lims_quotation_all; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$quotation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    if($quotation->status == 1)
                        $status = trans('file.Espera');
                    elseif($quotation->status == 2)
                        $status = trans('file.Terminado');
                    elseif($quotation->status == 3)
                        $status = trans('En producción');
                    else
                        $status = trans('file.NoPasa');
                ?>
                <tr class="quotation-link" data-quotation='["<?php echo e(date($general_setting->date_format, strtotime($quotation->created_at->toDateString()))); ?>", "<?php echo e($quotation->product_id); ?>", "<?php echo e($status); ?>", "<?php echo e($quotation->product_id_venta); ?>", "<?php echo e($quotation->leche_usar); ?>","<?php echo e($quotation->libras_a_prod); ?>", "<?php echo e($quotation->acidez); ?>", "<?php echo e($quotation->peso); ?>", "<?php echo e($quotation->temperatura); ?>", "<?php echo e($quotation->ntrabajadores); ?>", "<?php echo e($quotation->otros_costos); ?>", "<?php echo e($quotation->nota_prod); ?>", "<?php echo e($quotation->costoin); ?>", "<?php echo e($quotation->id); ?>", "<?php echo e($quotation->costo); ?>", "<?php echo e($quotation->pesof); ?>"]'>
                    <td><?php echo e($key); ?></td>
                                 
                    <?php if($quotation->product_id): ?>
                    <td><?php echo e($quotation->product->name); ?></td>
                    <?php else: ?>
                    <td>N/A</td>
                    <?php endif; ?> 
                    <td><?php echo e($quotation->costoin); ?></td>  
                    <td><?php echo e($quotation->otros_costos); ?></td>  
                    <td><?php echo e($quotation->costo); ?></td>  
                    <td><?php echo e($quotation->libras_a_prod); ?></td>  
                    <td><?php echo e($quotation->pesof); ?></td>  
                   
                    <td><?php echo e(date($general_setting->date_format, strtotime($quotation->created_at->toDateString()))); ?></td>                 
                    <?php if($quotation->status == 1): ?>
                        <td><div class="badge badge-secondary"><?php echo e($status); ?></div></td>
                    <?php elseif($quotation->status == 2): ?>
                        <td><div class="badge badge-success"><?php echo e($status); ?></div></td>
                    <?php elseif($quotation->status == 3): ?>
                        <td><div class="badge badge-primary"><?php echo e($status); ?></div></td>
                    <?php else: ?>
                        <td><div class="badge badge-danger"><?php echo e($status); ?></div></td>
                    <?php endif; ?>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo e(trans('file.action')); ?>

                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                               
                                <?php if(in_array("quotes-edit", $all_permission)): ?>
                                <li>
                                    <a class="btn btn-link" href="<?php echo e(route('lineaproduccion.edit', ['id' => $quotation->id])); ?>"><i class="dripicons-document-edit"></i> <?php echo e(trans('file.edit')); ?></a></button> 
                                </li>
                                <?php endif; ?>
                                
                                
                                <li class="divider"></li>
                                <?php if(in_array("quotes-delete", $all_permission)): ?>
                                <?php echo e(Form::open(['route' => ['lineaproduccion.destroy', $quotation->id], 'method' => 'DELETE'] )); ?>

                                <li>
                                    <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="dripicons-trash"></i> <?php echo e(trans('file.delete')); ?></button>
                                </li>
                                <?php echo e(Form::close()); ?>

                                <?php endif; ?>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot class="tfoot active">
                <th></th>
                <th>Totales</th><!--   <th><?php echo e(trans('file.Total')); ?></th> --> 
                <th></th>
                <th></th> 
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
               
            </tfoot>
        </table>
    </div>
</section>

<!--div id="quotation-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        <div class="container mt-3 pb-2 border-bottom">
            <div class="row">
                <div class="col-md-3">
                    <button id="print-btn" type="button" class="btn btn-default btn-sm d-print-none"><i class="dripicons-print"></i> <?php echo e(trans('file.Print')); ?></button>
                    <?php echo e(Form::open(['route' => 'quotation.sendmail', 'method' => 'post', 'class' => 'sendmail-form'] )); ?>

                        <input type="hidden" name="quotation_id">
                        <button class="btn btn-default btn-sm d-print-none"><i class="dripicons-mail"></i> <?php echo e(trans('file.Email')); ?></button>
                    <?php echo e(Form::close()); ?>

                </div>
                <div class="col-md-6">
                    <h3 id="exampleModalLabel" class="modal-title text-center container-fluid"><?php echo e($general_setting->site_title); ?></h3>
                </div>
                <div class="col-md-3">
                    <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close d-print-none"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="col-md-12 text-center">
                    <i style="font-size: 15px;"><?php echo e(trans('file.Quotation Details')); ?></i>
                </div>
            </div>
        </div>
            <div id="quotation-content" class="modal-body">
            </div>
            <br>
            <table class="table table-bordered product-quotation-list">
                <thead>
                    <th>#</th>
                    <th><?php echo e(trans('file.product')); ?></th>
                    <th>Qty</th>
                    <th><?php echo e(trans('file.Unit Price')); ?></th>
                    <th><?php echo e(trans('file.Tax')); ?></th>
                    <th><?php echo e(trans('file.Discount')); ?></th>
                    <th><?php echo e(trans('file.Subtotal')); ?></th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="quotation-footer" class="modal-body"></div>
      </div>
    </div>
</div-->

<script type="text/javascript">

    $("ul#produccion").siblings('a').attr('aria-expanded','true');
    $("ul#produccion").addClass("show");
    $("ul#produccion #lineaproduccion-menu").addClass("active");   
    var all_permission = <?php echo json_encode($all_permission) ?>;
    var quotation_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

	function confirmDelete() {
        if (confirm("Are you sure want to delete?")) {
            return true;
        }
        return false;
    }

    $("tr.quotation-link td:not(:first-child, :last-child)").on("click", function(){
        var quotation = $(this).parent().data('quotation');
        quotationDetails(quotation);
    });

    $(".view").on("click", function(){
        var quotation = $(this).parent().parent().parent().parent().parent().data('quotation');
        quotationDetails(quotation);
    });

    $("#print-btn").on("click", function(){
          var divToPrint=document.getElementById('quotation-details');
          var newWin=window.open('','Print-Window');
          newWin.document.open();
          newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media  print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
          newWin.document.close();
          setTimeout(function(){newWin.close();},10);
    });

    $('#quotation-table').DataTable( {
        "order": [],
        'language': {
            'lengthMenu': '_MENU_ <?php echo e(trans("file.records per page")); ?>',
             "info":      '<small><?php echo e(trans("file.Showing")); ?> _START_ - _END_ (_TOTAL_)</small>',
            "search":  '<?php echo e(trans("file.Search")); ?>',
            'paginate': {
                    'previous': '<i class="dripicons-chevron-left"></i>',
                    'next': '<i class="dripicons-chevron-right"></i>'
            }
        },
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0, 9]
            },
            {
                'render': function(data, type, row, meta){
                    if(type === 'display'){
                        data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                    }

                   return data;
                },
                'checkboxes': {
                   'selectRow': true,
                   'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                },
                'targets': [0]
            }
        ],
        'select': { style: 'multi',  selector: 'td:first-child'},
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                text: '<?php echo e(trans("file.PDF")); ?>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
                },
                footer:true
            },
            {
                extend: 'csv',
                text: '<?php echo e(trans("file.CSV")); ?>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
                },
                footer:true
            },
            {
                extend: 'print',
                text: '<?php echo e(trans("file.Print")); ?>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
                },
                footer:true
            },
            {
                text: '<?php echo e(trans("file.delete")); ?>',
                className: 'buttons-delete',
                action: function ( e, dt, node, config ) {
                    if(user_verified == '1') {
                        quotation_id.length = 0;
                        $(':checkbox:checked').each(function(i){
                            if(i){
                                var quotation = $(this).closest('tr').data('quotation');
                                quotation_id[i-1] = quotation[13];
                            }
                        });
                        if(quotation_id.length && confirm("Are you sure want to delete?")) {
                            console.log(quotation_id);
                            $.ajax({
                                type:'POST',
                                url:'lineaproduccion/deletebyselection',
                                
                                data:{
                                    quotationIdArray: quotation_id
                                },
                                success:function(data){
                                    alert(data);
                                }
                            });
                            dt.rows({ page: 'current', selected: true }).remove().draw(false);
                        }
                        else if(!quotation_id.length)
                            alert('Nothing is selected!');
                    }
                    else
                        alert('This feature is disable for demo!');
                }
            },
            {
                extend: 'colvis',
                text: '<?php echo e(trans("file.Column visibility")); ?>',
                columns: ':gt(0)'
            },
        ],
        drawCallback: function () {
            var api = this.api();
            datatable_sum(api, false);
        }
    } );

    function datatable_sum(dt_selector, is_calling_first) {
        if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
            var rows = dt_selector.rows( '.selected' ).indexes();

           $( dt_selector.column( 2 ).footer() ).html(dt_selector.cells( rows, 2, { page: 'current' } ).data().sum().toFixed(2));
           $( dt_selector.column( 3 ).footer() ).html(dt_selector.cells( rows, 3, { page: 'current' } ).data().sum().toFixed(2));
           $( dt_selector.column( 4 ).footer() ).html(dt_selector.cells( rows, 4, { page: 'current' } ).data().sum().toFixed(2));
           $( dt_selector.column( 5 ).footer() ).html(dt_selector.cells( rows, 5, { page: 'current' } ).data().sum().toFixed(2));
           $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 2 ).footer() ).html(dt_selector.cells( rows, 2 , { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 3 ).footer() ).html(dt_selector.cells( rows, 3 , { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 4 ).footer() ).html(dt_selector.cells( rows, 4 , { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 5 ).footer() ).html(dt_selector.cells( rows, 5 , { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6 , { page: 'current' } ).data().sum().toFixed(2));
        }
    }

    if(all_permission.indexOf("quotes-delete") == -1)
        $('.buttons-delete').addClass('d-none');

    function quotationDetails(quotation){
        $('input[name="quotation_id"]').val(quotation[13]);
        var htmltext = '<strong><?php echo e(trans("file.Date")); ?>: </strong>'+quotation[0]+'<br><strong><?php echo e(trans("file.reference")); ?>: </strong>'+quotation[1]+'<br><strong><?php echo e(trans("file.Status")); ?>: </strong>'+quotation[2]+'<br><br><div class="row"><div class="col-md-6"><strong><?php echo e(trans("file.From")); ?>:</strong><br>'+quotation[3]+'<br>'+quotation[4]+'<br>'+quotation[5]+'<br>'+quotation[6]+'<br>'+quotation[7]+'<br>'+quotation[8]+'</div><div class="col-md-6"><div class="float-right"><strong><?php echo e(trans("file.To")); ?>:</strong><br>'+quotation[9]+'<br>'+quotation[10]+'<br>'+quotation[11]+'<br>'+quotation[12]+'</div></div></div>';
        $.get('quotations/product_quotation/' + quotation[13], function(data){
            $(".product-quotation-list tbody").remove();
            var name_code = data[0];
            var qty = data[1];
            var unit_code = data[2];
            var tax = data[3];
            var tax_rate = data[4];
            var discount = data[5];
            var subtotal = data[6];
            var newBody = $("<tbody>");
            $.each(name_code, function(index){
                var newRow = $("<tr>");
                var cols = '';
                cols += '<td><strong>' + (index+1) + '</strong></td>';
                cols += '<td>' + name_code[index] + '</td>';
                cols += '<td>' + qty[index] + ' ' + unit_code[index] + '</td>';
                cols += '<td>' + parseFloat(subtotal[index] / qty[index]).toFixed(2) + '</td>';
                cols += '<td>' + tax[index] + '(' + tax_rate[index] + '%)' + '</td>';
                cols += '<td>' + discount[index] + '</td>';
                cols += '<td>' + subtotal[index] + '</td>';
                newRow.append(cols);
                newBody.append(newRow);
            });

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=4><strong><?php echo e(trans("file.Total")); ?>:</strong></td>';
            cols += '<td>' + quotation[14] + '</td>';
            cols += '<td>' + quotation[15] + '</td>';
            cols += '<td>' + quotation[16] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong><?php echo e(trans("file.Order Tax")); ?>:</strong></td>';
            cols += '<td>' + quotation[17] + '(' + quotation[18] + '%)' + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong><?php echo e(trans("file.Order Discount")); ?>:</strong></td>';
            cols += '<td>' + quotation[19] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong><?php echo e(trans("file.Shipping Cost")); ?>:</strong></td>';
            cols += '<td>' + quotation[20] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong><?php echo e(trans("file.grand total")); ?>:</strong></td>';
            cols += '<td>' + quotation[21] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            $("table.product-quotation-list").append(newBody);
        });
        var htmlfooter = '<p><strong><?php echo e(trans("file.Note")); ?>:</strong> '+quotation[22]+'</p><strong><?php echo e(trans("file.Created By")); ?>:</strong><br>'+quotation[23]+'<br>'+quotation[24];
        $('#quotation-content').html(htmltext);
        $('#quotation-footer').html(htmlfooter);
        $('#quotation-details').modal('show');
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>