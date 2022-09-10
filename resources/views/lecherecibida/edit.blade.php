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
                        <h4>{{trans('file.Leche por ingresar')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => ['lecherecibida.update', $lims_quotation_data->id], 'method' => 'put', 'files' => true, 'id' => 'payment-form']) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{trans('file.Supplier')}} *</label>
                                            <input type="hidden" name="supplier_id_hidden" value="{{$lims_quotation_data->supplier_id}}" />
                                            <select name="supplier_id" class="selectpicker form-control" data-live-search="true" id="supplier-id" data-live-search-style="begins" title="Select supplier..." required>
                                                @foreach($lims_supplier_list as $supplier)
                                                <option value="{{$supplier->id}}">{{$supplier->name . ' (' . $supplier->company_name . ')'}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>                                   

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{trans('file.Cantidad')}} *</label>
                                            <input type="number" name="qty" class="form-control" value="{{$lims_quotation_data->qty}}" step="any" required>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-4 form-group">
                                    <label>{{trans('file.Fecha Ingreso')}} *</label>
                                    <input type="text" name="expired_date" class="expired_date form-control" value="{{$lims_quotation_data->created_at->toDateString()}}" required>
                                     </div>   


                                </div>

                                <div class="form-group">
                                    <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary" id="submit-button">
                                </div>                              
                                
                                    
                                </div>                    
                               
 
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>   
   

<script type="text/javascript">
      
    $("ul#produccion").siblings('a').attr('aria-expanded','true');
    $("ul#produccion").addClass("show");
    $("ul#produccion #lecherecibida-menu").addClass("active");   

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
$('select[name="supplier_id"]').val($('input[name="supplier_id_hidden"]').val());

$('.selectpicker').selectpicker('refresh');

</script>
@endsection