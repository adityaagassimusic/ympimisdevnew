@extends('layouts.master')
@section('stylesheets')
<link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
<style type="text/css">
	thead input {
		width: 100%;
		padding: 3px;
		box-sizing: border-box;
	}
	#listTableBody > tr:hover {
		cursor: pointer;
		background-color: #7dfa8c;
	}
	table.table-bordered{
		border:1px solid black;
		vertical-align: middle;
	}
	table.table-bordered > thead > tr > th{
		border:1px solid black;
		vertical-align: middle;
	}
	table.table-bordered > tbody > tr > td{
		border:1px solid rgb(150,150,150);
		vertical-align: middle;

	}
	#loading { display: none; }
</style>
@stop

@section('header')
<section class="content-header">
	<h1>
		{{ $title }} <span class="text-purple"> {{ $title_jp }} </span>
	</h1>
	<ol class="breadcrumb">
	</ol>
</section>
@stop
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
	<div id="loading" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8;">
		<div>
			<center>
				<span style="font-size: 3vw; text-align: center;"><i class="fa fa-spin fa-hourglass-half"></i></span>
			</center>
		</div>
	</div>
	<div class="box">
		<div class="box-header">
			<input type="hidden" value="{{csrf_token()}}" name="_token" />
			<form method="GET" action="{{ url("export/invoice/tanda_terima") }}">
				<div class="col-md-2" style="padding: 0">
					<div class="form-group">
						<label>Date From</label>
						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input type="text" class="form-control pull-right datepicker" id="datefrom" name="datefrom">
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label>Date To</label>
						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input type="text" class="form-control pull-right datepicker" id="dateto" name="dateto">
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<div class="col-md-4" style="padding-right: 0;">
							<label style="color: white;"> x</label>
							<button type="submit" class="btn btn-primary form-control"><i class="fa fa-download"></i> Export List Tanda Terima</button>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
							<label style="color: white;"> x</label>
							<a class="btn btn-success pull-right" style="width: 100%" onclick="newData('new')"><i class="fa fa-plus"></i> &nbsp;Buat Tanda Terima</a>
					</div>
				</div>
			</form>
		</div>
		<div class="box-body">
			<div class="row">
				
			</div>

			<table id="listTable" class="table table-bordered table-striped table-hover">
				<thead style="background-color: rgba(126,86,134,.7);">
					<tr>
						<th>#</th>
						<th>Invoice Date</th>
						<th>Supplier</th>
						<th>Invoice No</th>
						<th>Surat Jalan</th>
						<th>PO Number</th>
						<th>Currency</th>
						<th>Amount</th>
						<th>Due Date</th>
						<th>File</th>
						<th>Status</th>
						<th>Buyer</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody id="listTableBody">
				</tbody>
				<tfoot>
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</section>

<div class="modal fade" id="modalNew">
	<div class="modal-dialog" style="width: 80%">
		<div class="modal-content">
			<div class="modal-header" style="padding-top: 0;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: 10px">
					<span aria-hidden="true">&times;</span>
				</button>
				<center><h3 style="font-weight: bold; padding: 3px;" id="modalNewTitle"></h3></center>
				<div class="row">
					<input type="hidden" id="id_edit">
					<div class="col-md-6">

						<div class="col-md-12" style="margin-bottom: 5px;">
							<label for="invoice_date" class="col-sm-3 control-label">Invoice Date<span class="text-red">*</span></label>
							<div class="col-sm-9">
								<div class="input-group date">
									<div class="input-group-addon">	
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control pull-right datepicker" id="invoice_date" name="invoice_date" value="<?php echo date('Y-m-d') ?>" placeholder="Invoice Date">
								</div>
							</div>
						</div>
						<div class="col-md-12" style="margin-bottom: 5px;" id="div_supplier">
							<label for="supplier_code" class="col-sm-3 control-label">Supplier Name<span class="text-red">*</span></label>
							<div class="col-sm-9">
								<select class="form-control select4" id="supplier_code" name="supplier_code" data-placeholder='Choose Supplier Name' style="width: 100%" onchange="getSupplier(this)">
									<option value="">&nbsp;</option>
									@foreach($vendor as $ven)
									<option value="{{$ven->vendor_code}}">{{$ven->vendor_code}} - {{$ven->supplier_name}}</option>
									@endforeach
								</select>
								<input type="hidden" class="form-control" id="supplier_name" name="supplier_name" readonly="">
							</div>
						</div>
						<!-- <div class="col-md-12" style="margin-bottom: 5px;">
							<label for="kwitansi" class="col-sm-3 control-label">Kwitansi</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="kwitansi" name="kwitansi" placeholder="Kwitansi">
							</div>
						</div> -->
						<div class="col-md-12" style="margin-bottom: 5px;">
							<label for="invoice_no" class="col-sm-3 control-label">Invoice No<span class="text-red">*</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control pull-right" id="invoice_no" name="invoice_no" placeholder="Invoice Number">							
							</div>
						</div>
						<div class="col-md-12" style="margin-bottom: 5px;">
							<label for="surat_jalan" class="col-sm-3 control-label">Surat Jalan</label>
							<div class="col-sm-9">
							

								<!-- <input type="text"  class="form-control" id="surat_jalan" name="surat_jalan" placeholder="Surat Jalan"> -->

								<!-- onchange="pilihSJ(this)" -->

	                            <select class="form-control select4" data-placeholder="Surat Jalan" name="surat_jalan" id="surat_jalan" style="width: 100% height: 35px;" required="" onchange="fillSuratJalan(value)">
	                            </select>
							</div>
						</div>
						<div class="col-md-12" style="margin-bottom: 5px;">
							<label for="bap" class="col-sm-3 control-label">BAP</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="bap" name="bap" placeholder="Masukkan Berita Acara Pemeriksaan">
							</div>
						</div>
						<div class="col-md-12" style="margin-bottom: 5px;">
							<label for="npwp" class="col-sm-3 control-label">NPWP</label>
							<div class="col-sm-9">
								<input type="text" class="form-control pull-right" id="npwp" name="npwp" placeholder="Nomor Pokok Wajib Pajak">
							</div>
						</div>
						<div class="col-md-12" style="margin-bottom: 5px;">
							<label for="faktur_pajak" class="col-sm-3 control-label">Faktur Pajak</label>
							<div class="col-sm-9">
								<input type="text" class="form-control pull-right" id="faktur_pajak" name="faktur_pajak"  placeholder="Faktur Pajak">
							</div>
						</div>
						
					</div>
					<div class="col-md-6">
						<!-- <div class="col-md-12" style="margin-bottom: 5px;">
							<label for="po_number" class="col-sm-3 control-label">PO Number<span class="text-red">*</span></label>
							<div class="col-sm-9">
							
								<input type="text"  class="form-control" id="po_number" name="po_number" placeholder="PO Number">
							</div>
						</div> -->
						<div class="col-md-12" style="margin-bottom: 5px;">
							<label for="payment_term" class="col-sm-3 control-label">Payment Term<span class="text-red">*</span></label>
							<div class="col-sm-9">
								<select class="form-control select4" id="payment_term" name="payment_term" data-placeholder='Pilih Metode Pembayaran' style="width: 100%">
									<option value="">&nbsp;</option>
									@foreach($payment_term as $pt)
									<option value="{{$pt->payment_term}}">{{$pt->payment_term}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-12" style="margin-bottom: 5px">
							<label for="Currency" class="col-sm-3 control-label">Currency<span class="text-red">*</span></label>
							<div class="col-sm-9">
								<select class="form-control select4" id="currency" name="currency" data-placeholder='Currency' style="width: 100%">
									<option value="USD">USD</option>
									<option value="IDR">IDR</option>
									<option value="JPY">JPY</option>
									<option value="EUR">EUR</option>
								</select>
							</div>
						</div>
						<div class="col-md-12" style="margin-bottom: 5px;">
							<label for="amount" class="col-sm-3 control-label">Amount<span class="text-red">*</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="amount" name="amount" placeholder="Total Amount" style="text-align:right" >
								<input type="hidden" class="form-control" id="mirai_amount" name="mirai_amount" placeholder="Total Amount" style="text-align:right" >
							</div>
						</div>
						<div class="col-md-12" style="margin-bottom: 5px">
							<label for="do_date" class="col-sm-3 control-label">DO Date<span class="text-red">*</span></label>
							<div class="col-sm-9">
								<div class="input-group date">
									<div class="input-group-addon">	
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control pull-right datepicker" id="do_date" name="do_date" placeholder="Document Date">
								</div>
							</div>
						</div>
						<!-- <div class="col-md-12" style="margin-bottom: 5px;">
							<label for="detail" class="col-sm-3 control-label">Detail</label>
							<div class="col-sm-9">
								<textarea class="form-control" id="detail" name="detail" placeholder="Enter detail"></textarea>
							</div>
						</div> -->
						<div class="col-md-12" style="margin-bottom: 5px">
							<label for="do_date" class="col-sm-3 control-label">Due Date<span class="text-red">*</span></label>
							<div class="col-sm-9">
								<div class="input-group date">
									<div class="input-group-addon">	
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control pull-right datepicker" id="due_date" name="due_date" placeholder="Due Date">
								</div>
							</div>
						</div>
						<!-- <div class="col-md-12" style="margin-bottom: 5px">
							<label for="do_date" class="col-sm-3 control-label">Transfer Date<span class="text-red">*</span></label>
							<div class="col-sm-9">
								<div class="input-group date">
									<div class="input-group-addon">	
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control pull-right datepicker" id="transfer_date" name="transfer_date" placeholder="Transfer Date">
								</div>
							</div>
						</div> -->
						<div class="col-md-12" style="margin-bottom: 5px">
							<label for="do_date" class="col-sm-3 control-label">Distribution Date</label>
							<div class="col-sm-9">
								<div class="input-group date">
									<div class="input-group-addon">	
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control pull-right datepicker" id="distribution_date" name="distribution_date" placeholder="Distribution Date">
								</div>
							</div>
						</div>

						<div class="col-md-12" style="margin-bottom: 5px">
							<label for="file" class="col-sm-3 control-label">File Invoice<span class="text-red">*</span></label>
							<div class="col-sm-9">
								<input type="file" id="file_attach" name="file_attach" accept="application/pdf">
							</div>
						</div>

					</div>
					<div class="col-xs-12" style="margin-top: 3%;">
						<div class="col-xs-6">
							<p style="font-size: 1.2vw;">List Surat Jalan</p>
	                        <div class="box box-primary">
	                            <div class="box-body">
	                                <table class="table table-hover table-bordered table-striped" id="tableListSuratJalan">
	                                    <thead style="background-color: rgba(126,86,134,.7);">
	                                        <tr>
	                                            <th style="width: 1%; text-align: center;">No</th>
	                                            <th style="width: 7%; text-align: center;">Surat Jalan</th>
	                                            <th style="width: 2%; text-align: center;">Delete</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody id="tableBodyListSuratJalan">
	                                    </tbody>
	                                </table>
	                            </div>
	                        </div>
						</div>
						<div class="col-xs-6">
							<p style="font-size: 1.2vw;">List PO</p>
	                        <div class="box box-primary">
	                            <div class="box-body">
	                                <table class="table table-hover table-bordered table-striped" id="tableListPO">
	                                    <thead style="background-color: rgba(126,86,134,.7);">
	                                        <tr>
	                                            <th style="width: 1%; text-align: center;">No</th>
	                                            <th style="width: 9%; text-align: center;">PO Number</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody id="tableBodyListPO">
	                                    </tbody>
	                                </table>
	                            </div>
	                        </div>
						</div>

						<div class="col-xs-12">
							<p style="font-size: 1.2vw;">Detail Item</p>
	                        <div class="box box-primary">
	                            <div class="box-body">
	                                <table class="table table-hover table-bordered table-striped" id="tableListItem">
	                                    <thead style="background-color: rgba(126,86,134,.7);">
	                                        <tr>
	                                            <th style="width: 1%; text-align: center;">No</th>
	                                            <th style="width: 8%; text-align: center;">Detail Item</th>
	                                            <th style="width: 1%; text-align: center;">Harga</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody id="tableBodyListitem">
	                                    </tbody>
	                                </table>
	                            </div>
	                        </div>
						</div>
                        
                    </div>
						<!-- <div class="col-md-12" style="margin-bottom: 5px;">
							<label for="remark" class="col-sm-3 control-label">Remark<span class="text-red">*</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="remark" name="remark" placeholder="Enter Remark">
							</div>
						</div> -->
					<div class="col-md-12">
						<a class="btn btn-success pull-right" onclick="SaveInvoice('new')" style="width: 100%; font-weight: bold; font-size: 1.5vw;" id="newButton">CREATE</a>
						<a class="btn btn-info pull-right" onclick="SaveInvoice('update')" style="width: 100%; font-weight: bold; font-size: 1.5vw;" id="updateButton">UPDATE</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-danger fade" id="modalDeleteInvoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Konfirmasi Hapus Data</h4>
				</div>
				<div class="modal-body">
					Apakah anda yakin ingin menghapus invoice Ini ?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<a id="a" name="modalButton" href="" type="button"  onclick="deletePR(this.id)" class="btn btn-danger">Delete</a>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('scripts')
<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
<script src="{{ url("js/dataTables.buttons.min.js")}}"></script>
<script src="{{ url("js/buttons.flash.min.js")}}"></script>
<script src="{{ url("js/jszip.min.js")}}"></script>
<script src="{{ url("js/vfs_fonts.js")}}"></script>
<script src="{{ url("js/buttons.html5.min.js")}}"></script>
<script src="{{ url("js/buttons.print.min.js")}}"></script>
<script src="{{ url("js/jquery.tagsinput.min.js") }}"></script>
<script>
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var sj_number = null;
	var po_number_all = null;
	var vendor_all = null;
	var payment_term_all = null;
	var no_sj = 1;
	var no_po = 1;
	var no_item = 1;
    var amount = 0;
    var ids = 1;
    var selected_surat_jalan = [];
    var selected_po_detail = [];
    var selected_item_detail = [];

	jQuery(document).ready(function() {
		fetchTable();
		sj_number = null;
		po_number = null;
    	$('body').toggleClass("sidebar-collapse");

		fetchYMES();
	});

	$('.datepicker').datepicker({
			autoclose: true,
			format: "yyyy-mm-dd",
			todayHighlight: true,	
		});

	$('.select2').select2({
		dropdownAutoWidth : true,
		allowClear: true
	});

	$(function () {
		$('.select4').select2({
			allowClear:true,
			dropdownAutoWidth : true,
			dropdownParent: $("#div_supplier"),
		});

	})

	// $("#amount").keyup(function(){
	// 	var output = parseFloat($('#amount').val()); 
	// 	var output2 = output.toLocaleString();
	// 	$('#amount').val(output2);
  	// });

	var audio_error = new Audio('{{ url("sounds/error.mp3") }}');
	var audio_ok = new Audio('{{ url("sounds/sukses.mp3") }}');

	function newData(id){

		if(id == 'new'){
			$('#modalNewTitle').text('Buat Tanda Terima');
			$('#newButton').show();
			$('#updateButton').hide();
			clearNew();
			$('#modalNew').modal('show');
			// $('#currency').val('IDR').trigger('change');
            selected_surat_jalan = [];
            selected_po_detail = [];
            selected_item_detail = [];
            selected_po_detail_all = [];
            selected_item_detail_all = [];

            $('#tableListSuratJalan').hide();
            $('#tableListPO').hide();
		}
		else{
			$('#newButton').hide();
			$('#updateButton').show();
			var data = {
				id:id
			}
			$.get('{{ url("invoice/tanda_terima_detail") }}', data, function(result, status, xhr){
				if(result.status){

					$('#supplier_code').html('');
					// $('#surat_jalan').html('');
					// $('#po_number').html('');
					$('#payment_term').html('');
					$('#currency').html('');

					var supplier_code = "";
					// var surat_jalan = "";
					// var po_number = "";
					var payment_term = "";
					var currency = "";

					$('#invoice_date').val(result.invoice.invoice_date);

					$.each(result.vendor, function(key, value){
						if(value.vendor_code == result.invoice.supplier_code){
							supplier_code += '<option value="'+value.vendor_code+'" selected>'+value.vendor_code+' - '+value.supplier_name+'</option>';
						}
						else{
							supplier_code += '<option value="'+value.vendor_code+'">'+value.vendor_code+' - '+value.supplier_name+'</option>';
						}
					});

					$('#supplier_code').append(supplier_code);

					$('#supplier_name').val(result.invoice.supplier_name);
					$('#invoice_no').val(result.invoice.invoice_no);
					// $('#kwitansi').val(result.invoice.kwitansi);

					// $.each(result.surat_jalan, function(key, value){
					// 	if(value.invoice_no == result.invoice.surat_jalan){
					// 		surat_jalan += '<option value="'+value.invoice_no+'" selected>'+value.invoice_no+'</option>';
					// 	}
					// 	else{
					// 		surat_jalan += '<option value="'+value.invoice_no+'">'+value.invoice_no+'</option>';
					// 	}
					// });


					// $('#surat_jalan').append(surat_jalan);
					$('#surat_jalan').attr('readonly','true');
					$('#po_number').attr('readonly','true');
					
					$('#bap').val(result.invoice.bap);
					$('#npwp').val(result.invoice.npwp);
					$('#faktur_pajak').val(result.invoice.faktur_pajak);

					// $.each(result.no_po, function(key, value){
					// 	if(value.no_po_sap == result.invoice.po_number){
					// 		po_number += '<option value="'+value.no_po_sap+'" selected>'+value.no_po_sap+'</option>';
					// 	}
					// 	else{
					// 		po_number += '<option value="'+value.no_po_sap+'">'+value.no_po_sap+'</option>';
					// 	}
					// });

					// $('#po_number').append(po_number);
					// $('#po_number').val(result.invoice.po_number);


					$.each(result.payment_term, function(key, value){
						if(value.payment_term == result.invoice.payment_term){
							payment_term += '<option value="'+value.payment_term+'" selected>'+value.payment_term+'</option>';
						}
						else{
							payment_term += '<option value="'+value.payment_term+'">'+value.payment_term+'</option>';
						}
					});

					$('#payment_term').append(payment_term);

					if(result.invoice.currency == "USD"){
						currency += '<option value="USD" selected>USD</option>';
						currency += '<option value="IDR">IDR</option>';
						currency += '<option value="JPY">JPY</option>';
					}
					else if (result.invoice.currency == "IDR"){
						currency += '<option value="USD">USD</option>';
						currency += '<option value="IDR" selected>IDR</option>';
						currency += '<option value="JPY">JPY</option>';
					}
					else if (result.invoice.currency == "JPY"){
						currency += '<option value="USD">USD</option>';
						currency += '<option value="IDR">IDR</option>';
						currency += '<option value="JPY" selected>JPY</option>';
					}

					$('#currency').append(currency);

					$('#amount').val(result.invoice.amount);
					$('#do_date').val(result.invoice.do_date);
					$('#due_date').val(result.invoice.due_date);
					$('#distribution_date').val(result.invoice.distribution_date);

					$('#id_edit').val(result.invoice.id);
					$('#modalNewTitle').text('Update Tanda Terima');
					$('#loading').hide();
					$('#modalNew').modal('show');

				}
				else{
					openErrorGritter('Error', result.message);
					$('#loading').hide();
					audio_error.play();
				}
			});
		}
	}

	function SaveInvoice(id){
		$('#loading').show();

		if(id == 'new'){
			 // || $('#po_number').val() == ""

			if($("#invoice_date").val() == "" || $('#supplier_code').val() == null || $('#invoice_no').val() == "" || $('#payment_term').val() == "" || $('#currency').val() == "" || $('#amount').val() == "" || $('#do_date').val() == "" || $('#due_date').val() == ""){
				
				$('#loading').hide();
				openErrorGritter('Error', "Please fill field with (*) sign.");
				return false;
			}

			if ($('#file_attach').val() == "") {
				openErrorGritter('Error', "Invoice Harus Dilampirkan");
				$('#loading').hide();
				return false;
			}

			if (selected_po_detail.length <= 0 || selected_surat_jalan.length <= 0) {
                openErrorGritter('Error!', 'Fill all data');
                return false;
            }

			var formData = new FormData();
			formData.append('category', 'Billing');
			formData.append('invoice_date', $("#invoice_date").val());
			formData.append('supplier_code', $("#supplier_code").val());
			formData.append('supplier_name', $("#supplier_name").val());
			// formData.append('kwitansi', $("#kwitansi").val());
			formData.append('invoice_no', $("#invoice_no").val());
			// formData.append('surat_jalan', $("#surat_jalan").val());
			formData.append('surat_jalan', selected_surat_jalan);

			formData.append('bap', $("#bap").val());
			formData.append('npwp', $("#npwp").val());
			formData.append('faktur_pajak', $("#faktur_pajak").val());
			formData.append('po_number', selected_po_detail);
			formData.append('detail_item', selected_item_detail);
			// formData.append('po_number', $("#po_number").val());
			formData.append('payment_term', $("#payment_term").val());
			formData.append('currency', $("#currency").val());
			formData.append('amount', $("#amount").val());
			formData.append('mirai_amount', $("#mirai_amount").val());
			formData.append('do_date', $("#do_date").val());
			formData.append('due_date', $("#due_date").val());
			formData.append('distribution_date', $("#distribution_date").val());
			formData.append('file_attach', $('#file_attach').prop('files')[0]);

			$.ajax({
				url:"{{ url('create/invoice/tanda_terima') }}",
				method:"POST",
				data:formData,
				dataType:'JSON',
				contentType: false,
				cache: false,
				processData: false,
				success:function(data)
				{
					if (data.status) {
						openSuccessGritter('Success', data.message);
						audio_ok.play();
						$('#loading').hide();
						$('#modalNew').modal('hide');
						ids = 1;
						clearNew();
						fetchTable();
					}else{
						openErrorGritter('Error!',data.message);
						$('#loading').hide();
						audio_error.play();
					}

				}
			});
		}
		else{
			// || $('#po_number').val() == ""
			if($("#invoice_date").val() == "" || $('#supplier_code').val() == "" || $('#invoice_no').val() == ""  || $('#payment_term').val() == "" || $('#currency').val() == "" || $('#amount').val() == "" || $('#do_date').val() == "" || $('#due_date').val() == ""){
				
				$('#loading').hide();
				openErrorGritter('Error', "Please fill field with (*) sign.");
				return false;
			}

			var formData = new FormData();
			formData.append('id_edit', $("#id_edit").val());
			formData.append('invoice_date', $("#invoice_date").val());
			formData.append('supplier_code', $("#supplier_code").val());
			formData.append('supplier_name', $("#supplier_name").val());
			// formData.append('kwitansi', $("#kwitansi").val());
			formData.append('invoice_no', $("#invoice_no").val());
			// formData.append('surat_jalan', $("#surat_jalan").val());
			// formData.append('surat_jalan', selected_surat_jalan);			
			formData.append('bap', $("#bap").val());
			formData.append('npwp', $("#npwp").val());
			formData.append('faktur_pajak', $("#faktur_pajak").val());

			// formData.append('po_number', $("#po_number").val());
			// formData.append('po_number', selected_po_detail);
			formData.append('payment_term', $("#payment_term").val());
			formData.append('currency', $("#currency").val());
			formData.append('amount', $("#amount").val());
			formData.append('do_date', $("#do_date").val());
			formData.append('due_date', $("#due_date").val());
			formData.append('distribution_date', $("#distribution_date").val());
			formData.append('file_attach', $('#file_attach').prop('files')[0]);

			$.ajax({
				url:"{{ url('edit/invoice/tanda_terima') }}",
				method:"POST",
				data:formData,
				dataType:'JSON',
				contentType: false,
				cache: false,
				processData: false,
				success:function(data)
				{
					if (data.status) {
						openSuccessGritter('Success', data.message);
						audio_ok.play();
						$('#loading').hide();
						$('#modalNew').modal('hide');
						clearNew();
						fetchTable();
					}else{
						openErrorGritter('Error!',data.message);
						$('#loading').hide();
						audio_error.play();
					}

				}
			});
		}
	}

	function clearNew(){
		no_sj = 1;
		no_po = 1;
		no_item = 1;
	    amount = 0;
	    ids = 1;
		$('#id_edit').val('');
		$("#invoice_date").val('');
		$("#supplier_code").val('').trigger('change');
		$("#supplier_name").val('');
		$('#invoice_no').val('');
		// $('#kwitansi').val('');
		$('#surat_jalan').val('');
		$('#bap').val("");
		$('#npwp').val("");
		$('#faktur_pajak').val('');
		// $("#po_number").val('');
		$("#payment_term").val('').trigger('change');
		$("#currency").val('').trigger('change');
		$('#amount').val('');
		$('#mirai_amount').val('');
		$('#do_date').val('');
		$('#due_date').val('');
		$('#distribution_date').val('');
        $('#tableBodyListSuratJalan').html("");
        $('#tableBodyListPO').html("");
        $('#tableBodyListItem').html("");
        $('#file_attach').val('');
	}

	function fetchYMES() {

		var category = "{{$category}}";
		
		var data = {
			id:category
		}

		$.get('{{ url("fetch/billing/tanda_terima_ymes") }}',data, function(result, status, xhr) {
			if(result.status){
				sj_number = result.surat_jalan;
				po_number_all = result.purchase_order;
			}
		})
	}

	function fetchTable(){
		$('#loading').show();

		$.get('{{ url("fetch/billing/tanda_terima") }}', function(result, status, xhr){
			if(result.status){
				$('#listTable').DataTable().clear();
				$('#listTable').DataTable().destroy();				
				$('#listTableBody').html("");
				var listTableBody = "";
				var count_all = 0;

				vendor_all = result.vendor;
				payment_term_all = result.payment_term;

				$.each(result.invoice, function(key, value){

                    if (value.payment_status == null) {
						listTableBody += '<tr>';
						listTableBody += '<td onclick="newData(\''+value.id+'\')" style="width:0.1%;">'+parseInt(key+1)+'</td>';
						listTableBody += '<td onclick="newData(\''+value.id+'\')" style="width:1%;">'+getFormattedDate(new Date(value.invoice_date))+'</td>';
						listTableBody += '<td onclick="newData(\''+value.id+'\')" style="width:3%;">'+value.supplier_code+' - '+value.supplier_name+'</td>';
						listTableBody += '<td onclick="newData(\''+value.id+'\')" style="width:2%;">'+value.invoice_no+'</td>';
						listTableBody += '<td onclick="newData(\''+value.id+'\')" style="width:1%;">'+value.surat_jalan.split(',').join('<br>')+'</td>';
						listTableBody += '<td onclick="newData(\''+value.id+'\')" style="width:1%;">'+value.po_number.split(',').join('<br>')+'</td>';
						listTableBody += '<td onclick="newData(\''+value.id+'\')" style="width:0.1%;">'+value.currency+'</td>';
						if (value.currency == "USD" || value.currency == "EUR") {
							listTableBody += '<td onclick="newData(\''+value.id+'\')" style="width:1%;text-align:right">'+parseFloat(value.amount).toFixed(2) +'</td>';
						}
						else{
							listTableBody += '<td onclick="newData(\''+value.id+'\')" style="width:1%;text-align:right">'+parseFloat(value.amount).toLocaleString('de-DE') +'</td>';
						}
						listTableBody += '<td onclick="newData(\''+value.id+'\')" style="width:1%;">'+getFormattedDate(new Date(value.due_date))+'</td>';

						if (value.file == null) {
	                        listTableBody += '<td style="width:1%;">';
	                        listTableBody += '</td>';
	                    }else{
	                        listTableBody += '<td style="width:1%;">';
	                        listTableBody += '<a href="{{url('files/invoice')}}/'+value.file+'" target="_blank" class="fa fa-paperclip"></a>';
	                        listTableBody += '</td>';
	                    }

	                    listTableBody += '<td onclick="newData(\''+value.id+'\')" style="width:1%;background-color: rgb(254, 204, 254);">';
	                    listTableBody += 'Not Payment';
	                    listTableBody += '</td>';
		                

						listTableBody += '<td onclick="newData(\''+value.id+'\')" style="width:3%;">'+value.created_name+'</td>';
						listTableBody += '<td style="width:4%;"><center><button class="btn btn-md btn-warning" onclick="newData(\''+value.id+'\')"><i class="fa fa-eye"></i> </button>  <a class="btn btn-md btn-danger" target="_blank" href="{{ url("invoice/report") }}/'+value.id+'"><i class="fa fa-file-pdf-o"></i> </a>&nbsp;<a href="javascript:void(0)" class="btn btn-mg btn-danger" onClick="deleteConfirmationInv('+value.id+')" data-toggle="modal" data-target="#modalDeleteInvoice" title="Delete Invoice"><i class="fa fa-trash"></i></a></center></td>';
						listTableBody += '</tr>';
                    }

					else{
						listTableBody += '<tr>';
						listTableBody += '<td style="width:0.1%;">'+parseInt(key+1)+'</td>';
						listTableBody += '<td style="width:1%;">'+getFormattedDate(new Date(value.invoice_date))+'</td>';
						listTableBody += '<td style="width:3%;">'+value.supplier_code+' - '+value.supplier_name+'</td>';
						listTableBody += '<td style="width:2%;">'+value.invoice_no+'</td>';
						listTableBody += '<td style="width:1%;">'+value.surat_jalan.split(',').join('<br>')+'</td>';
						listTableBody += '<td style="width:1%;">'+value.po_number.split(',').join('<br>')+'</td>';
						listTableBody += '<td style="width:0.1%;">'+value.currency+'</td>';
						if (value.currency == "USD" || value.currency == "EUR") {
							listTableBody += '<td style="width:1%;text-align:right">'+parseFloat(value.amount).toFixed(2) +'</td>';
						}
						else{
							listTableBody += '<td style="width:1%;text-align:right">'+parseFloat(value.amount).toLocaleString('de-DE') +'</td>';
						}
						listTableBody += '<td style="width:1%;">'+getFormattedDate(new Date(value.due_date))+'</td>';

						if (value.file == null) {
	                        listTableBody += '<td style="width:1%;">';
	                        listTableBody += '</td>';
	                    }else{
	                        listTableBody += '<td style="width:1%;">';
	                        listTableBody += '<a href="{{url('files/invoice')}}/'+value.file+'" target="_blank" class="fa fa-paperclip"></a>';
	                        listTableBody += '</td>';
	                    }

	                    listTableBody += '<td style="width:1%;background-color: #ccffff;">';
	                    listTableBody += 'Payment Requested';
	                    listTableBody += '</td>';

						listTableBody += '<td style="width:3%;">'+value.created_name+'</td>';
						listTableBody += '<td style="width:2%;"><center><a class="btn btn-md btn-danger" target="_blank" href="{{ url("invoice/report") }}/'+value.id+'"><i class="fa fa-file-pdf-o"></i> </a></center></td>';
						listTableBody += '</tr>';

	                }


					count_all += 1;
				});

				$('#count_all').text(count_all);


				$('#listTableBody').append(listTableBody);

				$('#listTable tfoot th').each( function () {
					var title = $(this).text();
					$(this).html( '<input style="text-align: center;" type="text" placeholder="Search '+title+'" size="8"/>' );
				} );

				var table = $('#listTable').DataTable({
					'dom': 'Bfrtip',
					'responsive':true,
					'lengthMenu': [
					[ 10, 25, 50, -1 ],
					[ '10 rows', '25 rows', '50 rows', 'Show all' ]
					],
					'buttons': {
						buttons:[
						{
							extend: 'pageLength',
							className: 'btn btn-default',
						},
						{
							extend: 'copy',
							className: 'btn btn-success',
							text: '<i class="fa fa-copy"></i> Copy',
							exportOptions: {
								columns: ':not(.notexport)'
							}
						},
						{
							extend: 'excel',
							className: 'btn btn-info',
							text: '<i class="fa fa-file-excel-o"></i> Excel',
							exportOptions: {
								columns: ':not(.notexport)'
							}
						},
						{
							extend: 'print',
							className: 'btn btn-warning',
							text: '<i class="fa fa-print"></i> Print',
							exportOptions: {
								columns: ':not(.notexport)'
							}
						},
						]
					},
					'paging': true,
					'lengthChange': true,
					'pageLength': 20,
					'searching': true,
					'ordering': true,
					'order': [],
					'info': true,
					'autoWidth': true,
					"sPaginationType": "full_numbers",
					"bJQueryUI": true,
					"bAutoWidth": false,
					"processing": true,
					initComplete: function() {
                    this.api()
                        .columns([1,2,3,8,10])
                        .every(function(dd) {
                            var column = this;
                            var theadname = $("#tableFinished th").eq([dd]).text();
                            var select = $(
                                    '<select style="width:100%"><option value="" style="font-size:11px;">All</option></select>'
                                )
                                .appendTo($(column.footer()).empty())
                                .on('change', function() {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                    column.search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });
                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function(d, j) {
                                    var vals = d;
                                    if ($("#tableFinished th").eq([dd]).text() == 'Category') {
                                        vals = d.split(' ')[0];
                                    }
                                    select.append('<option style="font-size:11px;" value="' +
                                        d + '">' + vals + '</option>');
                                });
                        });
                	},
				});

				table.columns().every( function () {
					var that = this;

					$( 'input', this.footer() ).on( 'keyup change', function () {
						if ( that.search() !== this.value ) {
							that
							.search( this.value )
							.draw();
						}
					} );
				} );

				$('#listTable tfoot tr').appendTo('#listTable thead');

				$('#loading').hide();

			}
			else{
				audio_error.play();
				openErrorGritter('Error', result.message);
				$('#loading').hide();
			}
		});
	}

	function getSupplier(elem){

			// $.ajax({
			// 	url: "{{ route('admin.pogetsupplier') }}?supplier_code="+elem.value,
			// 	method: 'GET',
			// 	success: function(data) {
			// 		var json = data,
			// 		obj = JSON.parse(json);
			// 		$('#supplier_name').val(obj.name);
			// 	} 
			// });
			surat_jalan = "";
	        supplier_name = "";
	        payment_list = "";

	        var isi = elem.value;

	        $("#payment_term").html('');
	        payment_list += '<option value=""></option>';

	        $("#surat_jalan").html('');
	        surat_jalan += '<option value=""></option>';
	        
	        // console.log(sj_number);
	        // console.log(vendor_all);

	        for(var i = 0; i < vendor_all.length;i++){
	        	// payment_list += '<option value="'+vendor_all[i].supplier_duration+'">'+vendor_all[i].supplier_duration+'</option>';

	        	if (vendor_all[i].vendor_code == isi ) {
	        		payment_list += '<option value="'+vendor_all[i].supplier_duration+'" selected>'+vendor_all[i].supplier_duration+'</option>';
	        	}
	        	
	        }

	        for(var i = 0; i < payment_term_all.length;i++){
	        	payment_list += '<option value="'+payment_term_all[i].payment_term+'">'+payment_term_all[i].payment_term+'</option>';
	        }

	        var sj_text = [];

	        for(var i = 0; i < sj_number.length;i++){

	            if (isi === '') {
	                surat_jalan += '<option value=""></option>';
	            }else{
	                if (sj_number[i].supplier_code == isi) {
	                	if(sj_text.indexOf(sj_number[i].doc_text) === -1){
				          	sj_text[sj_text.length] = sj_number[i].doc_text;

	                    	supplier_name = sj_number[i].supplier_name;
	                    	surat_jalan += '<option value="'+sj_number[i].doc_text+'">'+sj_number[i].doc_text+'</option>';
				        }
	                }

	            }
	        }
	        $("#surat_jalan").append(surat_jalan);
	        $("#payment_term").append(payment_list);
	        $('#supplier_name').val(supplier_name);
	}

	function fillSuratJalan(sj) {

            if (sj.length > 0) {
                if (!selected_surat_jalan.includes(sj)) {
                    // for (var i = 0; i < sj_number.length; i++) {
                    //     if (sj_number[i].doc_text == sj) {

                    //         if (selected_surat_jalan.length > 0) {

                    //         }
                    //     }
                    // }

                    // var label = '<span style="margin-left: 1%;" class="label label-primary" id="label-' + eo_number + '">' +
                    //     eo_number + '</span>';
                    // $('#selected_surat_jalan').append(label);
                    var tableData = '';
	        		var sj_text = [];

                    for (var i = 0; i < sj_number.length; i++) {
						if(sj_text.indexOf(sj_number[i].doc_text) === -1){
				          	sj_text[sj_text.length] = sj_number[i].doc_text;
 							if (sj_number[i].doc_text == sj) {
	                            tableData += '<tr id="stock_'+sj_number[i].doc_text+'">';

	                            tableData += '<td style="text-align: center;">';
	                            tableData += no_sj++;
	                            tableData += '</td>';

	                            tableData += '<td style="text-align: left;">';
	                            tableData += sj_number[i].doc_text;
	                            tableData += '</td>';

	                            tableData += '<td style="text-align: center;">';
	                            tableData += '<button id="tes" class="btn btn-danger btn-xs" ';
	                            tableData += 'onclick="delete_tanda_terima(\''+sj_number[i].doc_text+'\',this,'+ids+')">';
	                            tableData += '<i class="fa fa-trash"></i></button></td>';
	                            tableData += '</tr>';

	                    		selected_surat_jalan.push(sj_number[i].doc_text);
	                        }
				        }
                       
                    }

                    var tableDataPO = '';
				    var amount_total = 0;

				    var supp_code = $("#supplier_code").val();

                    for (var i = 0; i < po_number_all.length; i++) {
                    	if (po_number_all[i].supplier_code == supp_code) {
	                        if (po_number_all[i].doc_text == sj) {
	                        	if (po_number_all[i].po_rq_menber != null || po_number_all[i].po_no != null) {
	                        		amount_total = parseFloat(po_number_all[i].net_price) / parseFloat(po_number_all[i].price_unit) * parseFloat(po_number_all[i].une_qty)

	                        		if (po_number_all[i].sap_move_type == "101") {
			                        	amount += amount_total;                        			
	                        		}else if(po_number_all[i].sap_move_type == "102"){
			                        	amount -= amount_total;
	                        		}else{
	                        			amount += amount_total;
	                        		}

					                if (!selected_po_detail.includes(po_number_all[i].po_rq_menber)) {
	                            		tableDataPO += '<tr class="stock_detail_'+ids+'">';

			                            tableDataPO += '<td style="text-align: center;">';
			                            tableDataPO += no_po++;
			                            tableDataPO += '</td>';

			                            tableDataPO += '<td style="text-align: left;">';
			                            if (po_number_all[i].po_rq_menber != null) {
			                            	tableDataPO += po_number_all[i].po_rq_menber;
			                            }else{
			                            	tableDataPO += po_number_all[i].po_no;
			                            }
			                            tableDataPO += '</td>';

			                            tableDataPO += '</tr>';


			                            if (po_number_all[i].po_rq_menber != null) {
				                            selected_po_detail_all.push({
				                                'po_number': po_number_all[i].po_rq_menber,
				                                'surat_jalan': po_number_all[i].doc_text
				                            });
				                    		selected_po_detail.push(po_number_all[i].po_rq_menber);
			                            }else{

			                            	 selected_po_detail_all.push({
				                                'po_number': po_number_all[i].po_no,
				                                'surat_jalan': po_number_all[i].doc_text
				                            });
				                    		selected_po_detail.push(po_number_all[i].po_no);
			                            }

		                    			$('#currency').val(po_number_all[i].currency).trigger('change');
		                    			$('#do_date').val(po_number_all[i].post_date);
		                    		}
	                        	}
	                        }
	                    }
                    }

                    var tableDataItem = '';
				    var supp_code = $("#supplier_code").val();

                    for (var i = 0; i < po_number_all.length; i++) {
                    	if (po_number_all[i].supplier_code == supp_code) {
	                        if (po_number_all[i].doc_text == sj) {
	                        	if (po_number_all[i].po_rq_menber != null || po_number_all[i].po_no != null) {
	                        		var amount_item = 0;

	                        		amount_item2 = parseFloat(po_number_all[i].net_price) / parseFloat(po_number_all[i].price_unit) * parseFloat(po_number_all[i].une_qty)

	                        		if (po_number_all[i].sap_move_type == "101") {
			                        	amount_item += amount_item2;                        			
	                        		}else if(po_number_all[i].sap_move_type == "102"){
			                        	amount_item -= amount_item2;
	                        		}else{
	                        			amount_item += amount_item2;
	                        		}

					                if (!selected_item_detail.includes(po_number_all[i].item_name)) {
	                            		tableDataItem += '<tr class="item_detail_'+ids+'">';

			                            tableDataItem += '<td style="text-align: center;">';
			                            tableDataItem += no_item++;
			                            tableDataItem += '</td>';

			                            tableDataItem += '<td style="text-align: left;">';
			                            tableDataItem += po_number_all[i].item_name;
			                            tableDataItem += '</td>';

			                            tableDataItem += '<td style="text-align: left;">'; 
			                            tableDataItem += amount_item;
			                            tableDataItem += '</td>';

			                            tableDataItem += '</tr>';

			                            selected_item_detail_all.push({
				                            'po_number': po_number_all[i].po_no,
				                            'surat_jalan': po_number_all[i].doc_text,
				                            'item_name' : po_number_all[i].item_name
				                        });
				                    	selected_item_detail.push(po_number_all[i].item_name);
			                            
		                    		}
	                        	}
	                        }
	                    }
                    }


                    $('#amount').val(amount.toFixed(2));
                    $('#mirai_amount').val(amount.toFixed(2));

                    $('#tableBodyListSuratJalan').append(tableData);
                    $('#tableBodyListPO').append(tableDataPO);
                    $('#tableBodyListitem').append(tableDataItem);
                    ids++;
                    
                    // $("#surat_jalan").prop('selectedIndex', 0).change();

                } else {
                    // $("#surat_jalan").prop('selectedIndex', 0).change();
                    openErrorGritter('Error!', 'Surat Jalan Already selected');
                    return false;
                }

                if (selected_surat_jalan.length > 0) {
                    $('#tableListSuratJalan').show();
                } else {
                    $('#tableListSuratJalan').hide();
                }

                if (selected_po_detail.length > 0) {
                    $('#tableListPO').show();
                } else {
                    $('#tableListPO').hide();
                }

            }

        }

        function delete_tanda_terima(surat_jalan,elem,id) {

            // Remove Detail
            $(elem).closest('tr').remove();

            for (var i = (selected_po_detail_all.length - 1); i >= 0; i--) {
                if (selected_po_detail_all[i].surat_jalan == surat_jalan) {
                    selected_po_detail_all.splice(i, 1);
                    selected_po_detail.splice(i, 1);

                    selected_item_detail_all.splice(i, 1);
                    selected_item_detail.splice(i, 1);
                }
            }

            for (var i = (selected_surat_jalan.length - 1); i >= 0; i--) {
                if (selected_surat_jalan[i] == surat_jalan) {
                    selected_surat_jalan.splice(i, 1);
                }
            }

            no_sj--;
    		amount = 0;
            $('#amount').val(amount.toFixed(2));
            $('#mirai_amount').val(amount.toFixed(2));


            // $("#stock_" + surat_jalan).remove();
            $(".stock_detail_" + id).remove();
            $(".item_detail_" + id).remove();
        }

    function deleteConfirmationInv(id) {
		$('[name=modalButton]').attr("id",id);
	}

    function deletePR(id){
			var data = {
				id:id,
			}

			$("#loading").show();

			$.post('{{ url("delete/tanda_terima") }}', data, function(result, status, xhr){
				if (result.status == true) {
					openSuccessGritter("Success","Data Berhasil Dihapus");
					$("#loading").hide();
					setTimeout(function(){  window.location.reload() }, 2500);
				}
				else{
					openErrorGritter("Success","Data Gagal Dihapus");
				}
			});
	}

    function getFormattedDate(date) {
		  var year = date.getFullYear();

		  var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
			  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
			];

		  var month = date.getMonth();

		  var day = date.getDate().toString();
		  day = day.length > 1 ? day : '0' + day;
		  
		  return day + '-' + monthNames[month] + '-' + year;
	}

	function CurrencyFormat(n) {
		 return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
	}


	function openSuccessGritter(title, message){
		jQuery.gritter.add({
			title: title,
			text: message,
			class_name: 'growl-success',
			image: '{{ url("images/image-screen.png") }}',
			sticky: false,
			time: '3000'
		});
	}

	function openErrorGritter(title, message) {
		jQuery.gritter.add({
			title: title,
			text: message,
			class_name: 'growl-danger',
			image: '{{ url("images/image-stop.png") }}',
			sticky: false,
			time: '3000'
		});
	}

</script>
@endsection

