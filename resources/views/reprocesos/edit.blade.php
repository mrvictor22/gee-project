@extends('layout.main') @section('content')
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible number-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif
<section class="forms">
<div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('Editar ficha de reproceso')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => ['reprocesos.update', $lims_product_data->id], 'method' => 'put', 'files' => true, 'id' => 'payment-form']) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{'Producto'}} *</label>
                                            <input type="hidden" name="producto_id_hidden" value="{{$lims_product_data->producto_id }}" />
                                            <select name="producto_id" class="selectpicker form-control" data-live-search="true" id="producto-id" >
                                                @foreach($lims_product_list as $producto)
                                                <option value="{{$producto->id}}">{{($producto->name)}}({{$producto->code}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        </div>                                   

                                         <div class="col-md-4">
                                        <div class="form-group">
                                        <label>{{'Total de Lb a reproceso'}} *</label>
                                            <input type="number" name="total_lb_reproceso" class="form-control" value="{{$lims_product_data->total_lb_reproceso}}" step="any" required>
                                             </div>
                                          </div>
                                
                                          
                                           
                                         <div class="form-group">
                                            <label>{{'Estado del proceso'}} </label>
                                            <input type="hidden" name="status_hidden" value="{{$lims_product_data->status }}" />
                                            <select required name="status" id="status-proceso" class="selectpicker form-control" data-live-search="true"  >
                                          
                                                <option value="En proceso">En proceso</option>
                                                <option value="En espera">En espera</option>
                                                <option value="Terminado">Terminado</option>
                                              
                                              </select>
                                        </div>
                                     


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
    </div>   
   

    <script type="text/javascript">
      
      $("ul#produccion").siblings('a').attr('aria-expanded','true');
      $("ul#produccion").addClass("show");
      $("ul#produccion #lecherecibida-list-menu2").addClass("active");   
  
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
  $('select[name="producto_id"]').val($('input[name="producto_id_hidden"]').val());
  $('select[name="status"]').val($('input[name="status_hidden"]').val());

 
  
  $('.selectpicker').selectpicker('refresh');
  
  </script>
@endsection