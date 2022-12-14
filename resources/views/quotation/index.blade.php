@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div> 
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif

<section>
    <div class="container-fluid">
        @if(in_array("quotes-add", $all_permission))
            <a href="{{route('quotations.create')}}" class="btn btn-info"><i class="dripicons-plus"></i> {{trans('file.Add Quotation')}}</a>
        @endif
    </div>
    <div class="table-responsive">
        <table id="quotation-table" class="table quotation-list">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>{{trans('file.Date')}}</th>
                    <th>{{trans('file.reference')}}</th>
                    <th>{{trans('file.Biller')}}</th>
                    <th>{{trans('file.customer')}}</th>
                    <th>{{trans('file.Supplier')}}</th>
                    <th>{{trans('file.Quotation Status')}}</th>
                    <th>{{trans('file.grand total')}}</th>
                    <th class="not-exported">{{trans('file.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lims_quotation_all as $key=>$quotation)
                <?php
                    if($quotation->quotation_status == 1)
                        $status = trans('file.Pending');
                    else
                        $status = trans('file.Sent');
                ?>
                <tr class="quotation-link" data-quotation='["{{date($general_setting->date_format, strtotime($quotation->created_at->toDateString()))}}", "{{$quotation->reference_no}}", "{{$status}}", "{{$quotation->biller->name}}", "{{$quotation->biller->company_name}}","{{$quotation->biller->email}}", "{{$quotation->biller->phone_number}}", "{{$quotation->biller->address}}", "{{$quotation->biller->city}}", "{{$quotation->customer->name}}", "{{$quotation->customer->phone_number}}", "{{$quotation->customer->address}}", "{{$quotation->customer->city}}", "{{$quotation->id}}", "{{$quotation->total_tax}}", "{{$quotation->total_discount}}", "{{$quotation->total_price}}", "{{$quotation->order_tax}}", "{{$quotation->order_tax_rate}}", "{{$quotation->order_discount}}", "{{$quotation->shipping_cost}}", "{{$quotation->grand_total}}", "{{$quotation->note}}", "{{$quotation->user->name}}", "{{$quotation->user->email}}","{{ $quotation->customer->name }}","{{ $quotation->customer->phone_number }}"]'>
                    <td>{{$key}}</td>
                    <td>{{ date($general_setting->date_format, strtotime($quotation->created_at->toDateString())) . ' '. $quotation->created_at->toTimeString() }}</td>
                    <td>{{ $quotation->reference_no }}</td>
                    <td>{{ $quotation->biller->name }}</td>
                    <td>{{ $quotation->customer->name }}</td>
                    @if($quotation->supplier_id)
                    <td>{{ $quotation->supplier->name }}</td>
                    @else
                    <td>N/A</td>
                    @endif
                    @if($quotation->quotation_status == 1)
                        <td><div class="badge badge-danger">{{$status}}</div></td>
                    @else
                        <td><div class="badge badge-success">{{$status}}</div></td>
                    @endif
                    <td>{{ $quotation->grand_total }}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.action')}}
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li>
                                    <button type="button" class="btn btn-link view"><i class="fa fa-eye"></i>  {{trans('file.View')}}</button>
                                </li>
                                @if(in_array("quotes-edit", $all_permission))
                                <li>
                                    <a class="btn btn-link" href="{{ route('quotations.edit', ['id' => $quotation->id]) }}"><i class="dripicons-document-edit"></i> {{trans('file.edit')}}</a></button> 
                                </li>
                                @endif
                                <li>
                                    <a class="btn btn-link" href="{{ route('quotation.create_sale', ['id' => $quotation->id]) }}"><i class="fa fa-shopping-cart"></i> {{trans('file.Create Sale')}}</a></button> 
                                </li>
                                <li>
                                    <a class="btn btn-link" href="{{ route('quotation.create_purchase', ['id' => $quotation->id]) }}"><i class="fa fa-shopping-basket"></i> {{trans('file.Create Purchase')}}</a></button> 
                                </li>
                                <li class="divider"></li>
                                @if(in_array("quotes-delete", $all_permission))
                                {{ Form::open(['route' => ['quotations.destroy', $quotation->id], 'method' => 'DELETE'] ) }}
                                <li>
                                    <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="dripicons-trash"></i> {{trans('file.delete')}}</button>
                                </li>
                                {{ Form::close() }}
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="tfoot active">
                <th></th>
                <th>{{trans('file.Total')}}</th>
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

<div id="quotation-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        <div class="container mt-3 pb-2 border-bottom">
            <div class="row">
                <div class="col-md-3">
                    <button id="print-btn" type="button" class="btn btn-default btn-sm d-print-none"><i class="dripicons-print"></i> {{trans('file.Print')}}</button>
                    {{ Form::open(['route' => 'quotation.sendmail', 'method' => 'post', 'class' => 'sendmail-form'] ) }}
                        <input type="hidden" name="quotation_id">
                        <button class="btn btn-default btn-sm d-print-none"><i class="dripicons-mail"></i> {{trans('file.Email')}}</button>
                    {{ Form::close() }}
                </div>
                <div class="col-md-6">
                    <img class="modal-title container-fluid" src="{{url('public/images/gee-logo.png')}}" style="position: absolute;float: left !important;right: 114%; top: 102%; width: 40% !important; height: auto !important;">
                    <img class="modal-title container-fluid" src="{{url('public/images/idea.png')}}" style="position: absolute;left: 56%; top: 102%;  height: auto !important;">
                </div>
                <div class="col-md-3">
                    <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close d-print-none"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>

                <div class="col-md-12 text-center" style="margin-top: 15%;">
                    <i style="font-size: 15px;">{{trans('file.Quotation Details')}}</i>
                </div>
            </div>
        </div>
          <div class="row">
              <div id="quotation-head" class="col-md-6" style="padding-left: 4%">
              </div>
              <div id="quotation-head2" class="col-md-6" style="text-align: right; padding-right: 4%">
              </div>
          </div>

          <div id="quotation-content" class="modal-body">
            </div>
            <br>
            <table class="table table-bordered product-quotation-list">
                <thead>
                    <th>#</th>
                    <th>{{trans('file.product')}}</th>
                    <th>Qty</th>
                    <th>{{trans('file.Unit Price')}}</th>
                    <th>{{trans('file.Subtotal')}}</th>
                </thead>
                <tbody>
                </tbody>
            </table>

            <div id="quotation-footer" class="modal-body">

            </div>
          <ul>
              <li>Se solicita un dep??sito de seguridad por da??os o p??rdidas al equipo de $100.00 a la
                  aceptaci??n del contrato. Este ser?? reintegrado de encontrarse todo en buen estado</li>
              <br>
              <h5>CONDICIONES</h5>
              <li>La vigencia de esta cotizaci??n es de siete d??as despu??s de la fecha enviada,
                  posterior a esta fecha se debe recotizar pues los precios pueden variar de acuerdo con
                  demandas de fechas, productos, flores, etc</li>
              <li>Pago con tarjeta de cr??dito + 5.00%</li>
              <li>T??rminos de pago: 50% anticipado una vez aprobada la cotizaci??n, por medio de
                  transferencia al No. de cuenta: 201410321 de CUENTA CORRIENTE del BANCO DE
                  AMERICA CENTRAL a Nombre de GEE El Salvador S.A de C.V o EFECTIVO y 50%
                  restante se deber??n liquidar dos d??as previos a la fecha del evento. La propuesta no
                  incluye IVA</li>
              <li>Cualquier gasto adicional que surja en el d??a del evento se deber?? liquidar ese
                  mismo d??a, en caso de gastos extras a terceros, deber??n ser liquidados por EL CLIENTE</li>
              <li>En caso de cancelaci??n de los servicios, por cualquiera de las dos partes, objeto
                  de este contrato, GEE EL SALVADOR S.A DE C.V no podr?? reembolsar ning??n pago
                  hecho a la fecha de cancelaci??n por concepto de administraci??n y de disponibilidad de
                  fecha</li>
          </ul>


      </div>

    </div>
</div>

<script type="text/javascript">

    $("ul#quotation").siblings('a').attr('aria-expanded','true');
    $("ul#quotation").addClass("show");
    $("ul#quotation #quotation-list-menu").addClass("active");
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
          newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
          newWin.document.close();
          // //setTimeout(function(){newWin.close();},10)
    });

    $('#quotation-table').DataTable( {
        "order": [],
        'language': {
            'lengthMenu': '_MENU_ {{trans("file.records per page")}}',
             "info":      '<small>{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)</small>',
            "search":  '{{trans("file.Search")}}',
            'paginate': {
                    'previous': '<i class="dripicons-chevron-left"></i>',
                    'next': '<i class="dripicons-chevron-right"></i>'
            }
        },
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0, 8]
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
                text: '{{trans("file.PDF")}}',
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
                text: '{{trans("file.CSV")}}',
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
                text: '{{trans("file.Print")}}',
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
                text: '{{trans("file.delete")}}',
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
                            
                            $.ajax({
                                type:'POST',
                                url:'quotations/deletebyselection',
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
                text: '{{trans("file.Column visibility")}}',
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

            $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
        }
    }

    if(all_permission.indexOf("quotes-delete") == -1)
        $('.buttons-delete').addClass('d-none');

    function quotationDetails(quotation){
        console.log(quotation)
        $('input[name="quotation_id"]').val(quotation[13]);
        var headdoc = '<strong> Nombre de Cliente: </strong>'+quotation[25]+'<br><strong>Numero de contacto:</strong>'+quotation[26];
        var headdoc2 = '<strong> Gee El Salvador S.A de C.V</strong><br><strong>gee.experience@gmail.com</strong> <br><strong>instagram.com/gee.sv</strong>'
        var htmltext = '<strong>{{trans("file.Date")}}: </strong>'+quotation[0]+'<br><strong>{{trans("file.reference")}}: </strong>'+quotation[1]+'<br><strong>{{trans("file.Status")}}: </strong>'+quotation[2]+'<br><strong>Nombre de Cliente: </strong>'+quotation[25];
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
                cols += '<td>' + qty[index]   + '</td>';
                cols += '<td>' + parseFloat(subtotal[index] / qty[index]).toFixed(2) + '</td>';
                var sub = parseFloat(subtotal[index])
                cols += '<td colspan=2>' + sub.toFixed(2) + '</td>';
                newRow.append(cols);
                newBody.append(newRow);
            });

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=4><strong>{{trans("file.Total")}} (no incluye IVA):</strong></td>';
            var Total_noiva = parseFloat(quotation[16])
            cols += '<td>' + Total_noiva.toFixed(2) + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=4><strong>{{trans("file.Order Tax")}}:</strong></td>';
            cols += '<td>' + quotation[17] + '(' + quotation[18] + '%)' + '</td>';
            newRow.append(cols);
            newBody.append(newRow);



            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=4><strong>{{trans("file.grand total")}}:</strong></td>';
            var Grand_tot = parseFloat(quotation[21])
            cols += '<td>' + Grand_tot.toFixed(2) + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            $("table.product-quotation-list").append(newBody);
        });
        var htmlfooter = '<p><strong>{{trans("file.Note")}}:</strong> '+quotation[22]+'</p><strong>{{trans("file.Created By")}}:</strong><br>'+quotation[23]+'<br>'+quotation[24];
        $('#quotation-head').html(headdoc);
        $('#quotation-head2').html(headdoc2);
        $('#quotation-content').html(htmltext);
        $('#quotation-footer').html(htmlfooter);
        $('#quotation-details').modal('show');
    }
</script>
@endsection