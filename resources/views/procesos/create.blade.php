@extends('layout.main') @section('content')
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif

<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">

                    
                        <h4>{{'Entrada de producto a procesos'}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => 'procesos.store', 'method' => 'post', 'files' => true, 'id' => 'quotation-form']) !!}
                        
                        <div class="row">
                                        <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Producto a procesar *</label>
                                            <select required name="producto_id" id="producto_id" class="selectpicker form-control" data-live-search="true"  title="Seleccione producto">
                                           @foreach($lims_product_list as $producto)
                                                <option value="{{$producto->id}}">{{($producto->name)}}({{$producto->code}})</option>
                                                $producto->
                                               @endforeach
                                              </select>
                                        </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Producto destino para venta*</label>
                                            <select required name="producto_destino_id" id="producto_destino_id" class="selectpicker form-control" data-live-search="true"  title="Seleccione producto">
                                           @foreach($lims_product_list as $producto)
                                                <option value="{{$producto->id}}">{{($producto->name)}}({{$producto->code}})</option>
                                                $producto->
                                               @endforeach
                                              </select>
                                        </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{'Peso inicial'}} *</label>
                                            <input type="number" name="peso_inicial" class="form-control" step="any" required>
                                        </div>
                                    </div>        
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{'Peso Final'}} </label>
                                            <input type="number" name="peso_final" class="form-control" step="any" >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                    
                                       <br>
                                       <br>
                                       <h4>{{'Embolsado'}}</h4>
                                       
                                    </div>
                                    
                                    <div class="col-md-4">
                                    <div class="form-group">
                                    
                                            <label>{{'N° bolsas a utilizar'}} </label>
                                            <input type="number" name="bolsas_utilizar" class="form-control" step="any" required id="monto" onkeyup="multi();"> 
                                        </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{'N° bolsas desperdiciadas'}}</label>
                                            <input type="number" name="bolsas_desperdiciadas" class="form-control" step="any" required>
                                        </div>
                                        </div>        
                                        <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{'Costo por bolsa'}}</label>
                                            <input type="number" name="costo_enbolsado" class="form-control" step='0.01'  id="monto" onkeyup="multi();" />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                    <br>
                                    <h4>{{'Etiquetado'}}</h4>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                    
                                    <label>{{'N° etiquetas a utilizar'}} </label>
                                    <input type="number" name="etiquetas_utilizar" class="form-control" step="any" required>
                                   </div>
                                   </div>
                                
                                      <div class="col-md-4">
                                     <div class="form-group">
                                    <label>{{'N° etiquetas desperdiciadas'}}</label>
                                    <input type="number" name="etiquetas_desperdiciadas" class="form-control" step="any" >
                                         </div>
                                        </div>        
                                      <div class="col-md-4">
                                         <div class="form-group">
                                         <label>{{'Costo por etiqueta'}}</label>
                                         <input type="number" name="costo_etiquetas" class="form-control" step='0.01'  onchange="sumar(this.value);" />
                                         </div>
                                         </div>
                                         <div class="col-md-12">
                                    <br>
                                    <h4>{{'Sobrantes'}}</h4>
                                    </div>
                                 
                                    <div class="col-md-4">
                                     <div class="form-group">
                                    <label>{{'Total de sobrantes(Lb)'}}</label>
                                    <input type="number" name="total_sobrantes" class="form-control" step='0.01' >
                                         </div>
                                        </div>        
                                      <div class="col-md-4">
                                         <div class="form-group">
                                         <label>{{'Costo total del proceso'}}</label>
                                         <input type="number" name="costo_final_venta" class="form-control"  step='0.01' id="spTotal">
                                         
                                         </div>
                                         </div> 
                                         <div class="col-md-4">
                                         <div class="form-group">
                                            <label>{{'Estado del proceso'}} </label>
                                            <select required name="status" id="status-proceso" class="selectpicker form-control" data-live-search="true"  >
                                          
                                                <option value="En proceso">En proceso</option>
                                                <option value="En espera">En espera</option>
                                                <option value="Terminado">Terminado</option>
                                              
                                              </select>
                                        </div>
                                        </div>
                               



                            </div>
                            
                       
                    </div>
                    <div class="form-group">
                                    <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary" id="submit-button">
                                </div>
                                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
   <!--<div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{trans('file.Supplier')}} *</label>
                                            <select name="supplier_id" class="selectpicker form-control" data-live-search="true" id="supplier-id" data-live-search-style="begins" title="Select Supplier..." required>
                                                @foreach($lims_supplier_list as $supplier)
                                                <option value="{{$supplier->id}}">{{$supplier->name . ' (' . $supplier->company_name . ')'}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{trans('file.Cantidad')}} *</label>
                                            <input type="number" name="qty" class="form-control" step="any" required>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-4 form-group">
                                   <label>{{trans('file.Fecha Ingreso')}} *</label>
                                    <input type="text" name="expired_date" class="expired_date form-control">
                                     </div>    -->
   
<script type="text/javascript">

    $("ul#produccion").siblings('a').attr('aria-expanded','true');
    $("ul#produccion").addClass("show");
    $("ul#produccion #lecherecibida-list-menu1").addClass("active");

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

/*Search del producto*/
var lims_product_array = [];
var product_code = [];
var product_name = [];
var product_qty = [];
var product_type = [];
var product_id = [];
var product_list = [];
var qty_list = [];

$('select[name="warehouse_id"]').on('change', function() {
    var id = $(this).val();
    $.get('getproduct/' + id, function(data) {
        lims_product_array = [];
        product_code = data[0];
        product_name = data[1];
        product_qty = data[2];
        product_type = data[3];
        product_id = data[4];
        product_list = data[5];
        qty_list = data[6];
        $.each(product_code, function(index) {
            lims_product_array.push(product_code[index] + ' (' + product_name[index] + ')');
        });
    });
});

$('#lims_productcodeSearch').on('input', function(){
    /*var customer_id = $('#customer_id').val();*/
    var warehouse_id = $('#warehouse_id').val();
    temp_data = $('#lims_productcodeSearch').val();
   /* if(!customer_id){
        $('#lims_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
        alert('Please select Customer!');
    }*/
        if(!warehouse_id){
        $('#lims_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
        alert('Please select Warehouse!');
    }

});

function productSearch(data) {
    $.ajax({
        type: 'GET',
        url: 'lims_product_search',
        data: {
            data: data
        },
        success: function(data) {
            var flag = 1;
            $(".product-code").each(function(i) {
                if ($(this).val() == data[1]) {
                    rowindex = i;
                    var qty = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val()) + 1;
                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(qty);
                    checkQuantity(String(qty), true);
                    flag = 0;
                }
            });
            $("input[name='product_code_name']").val('');
            if(flag){
                var newRow = $("<tr>");
                var cols = '';
                temp_unit_name = (data[6]).split(',');
                cols += '<td>' + data[0] + '<button type="button" class="edit-product btn btn-link" data-toggle="modal" data-target="#editModal"> <i class="dripicons-document-edit"></i></button></td>';
                cols += '<td>' + data[1] + '</td>';
                cols += '<td><input type="number" class="form-control qty" name="qty[]" value="1" step="any" required/></td>';
                cols += '<td class="net_unit_price"></td>';
                cols += '<td class="discount">0.00</td>';
                cols += '<td class="tax"></td>';
                cols += '<td class="sub-total"></td>';
                cols += '<td><button type="button" class="ibtnDel btn btn-md btn-danger">{{trans("file.delete")}}</button></td>';
                cols += '<input type="hidden" class="product-code" name="product_code[]" value="' + data[1] + '"/>';
                cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[9] + '"/>';
                cols += '<input type="hidden" class="sale-unit" name="sale_unit[]" value="' + temp_unit_name[0] + '"/>';
                cols += '<input type="hidden" class="net_unit_price" name="net_unit_price[]" />';
                cols += '<input type="hidden" class="discount-value" name="discount[]" />';
                cols += '<input type="hidden" class="tax-rate" name="tax_rate[]" value="' + data[3] + '"/>';
                cols += '<input type="hidden" class="tax-value" name="tax[]" />';
                cols += '<input type="hidden" class="subtotal-value" name="subtotal[]" />';

                newRow.append(cols);
                $("table.order-list tbody").append(newRow);

                product_price.push(parseFloat(data[2]) + parseFloat(data[2] * customer_group_rate));
                product_discount.push('0.00');
                tax_rate.push(parseFloat(data[3]));
                tax_name.push(data[4]);
                tax_method.push(data[5]);
                unit_name.push(data[6]);
                unit_operator.push(data[7]);
                unit_operation_value.push(data[8]);
                rowindex = newRow.index();
                checkQuantity(1, true);
            }
        }
    });
}
var lims_productcodeSearch = $('#lims_productcodeSearch');

lims_productcodeSearch.autocomplete({
    source: function(request, response) {
        var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
        response($.grep(lims_product_array, function(item) {
            return matcher.test(item);
        }));
    },
    response: function(event, ui) {
        if (ui.content.length == 1) {
            var data = ui.content[0].value;
            $(this).autocomplete( "close" );
            productSearch(data);
        };
    },
    select: function(event, ui) {
        var data = ui.item.value;
        productSearch(data);
    }
});

$("#myTable").on('input', '.qty', function() {
    rowindex = $(this).closest('tr').index();
    if($(this).val() < 1 && $(this).val() != '') {
      $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(1);
      alert("Quantity can't be less than 1");
    }
    checkQuantity($(this).val(), true);
});

/*function sumar (valor) {
    var total = 0;	
  valor = parseFloat(valor); // Convertir el valor a un decimal (número).
	
    total = document.getElementById('spTotal').value;
	
    // Aquí valido si hay un valor previo, si no hay datos, le pongo un cero "0".
    total = (total == null || total == undefined || total == "") ? 0 : total;
	
    /* Esta es la suma. */
  /*  total = (parseFloat(total) +parseFloat(valor));
	
    // Colocar el resultado de la suma en el control "span".
    document.getElementById('spTotal').value = total;
}*/


/*function multi(){
    var total = 1;
    var change= false; //
    $(".monto").each(function(){
        if (!isNaN(parseFloat($(this).val()))) {
            change= true;
            total *= parseFloat($(this).val());
        }
    });
    // Si se modifico el valor , retornamos la multiplicación
    // caso contrario 0
    total = (change)? total:0;
    document.getElementById('Costo').innerHTML = total;
}
*/

</script>
@endsection