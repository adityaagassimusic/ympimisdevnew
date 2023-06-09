@extends('layouts.display')
@section('stylesheets')
<link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
<style type="text/css">

table.table-bordered{
  border:1px solid rgb(150,150,150);
}
table.table-bordered > thead > tr > th{
    border:1px solid rgb(54, 59, 56) !important;
    background-color: #212121;
    text-align: center;
    vertical-align: middle;
    color:white;
}
table.table-bordered > tbody > tr > td{
  border:1px solid rgb(54, 59, 56);
    background-color: null;
    color: white;
    vertical-align: middle;
    padding: 2px 5px 2px 5px;
    text-align: center;
}
table.table-condensed > thead > tr > th{   
  color: black
}
table.table-bordered > tfoot > tr > th{
  border:1px solid rgb(150,150,150);
  padding:0;
}
table.table-bordered > tbody > tr > td > p{
  color: #abfbff;
}

table.table-striped > thead > tr > th{
  border:1px solid black !important;
  text-align: center;
  background-color: rgba(126,86,134,.7) !important;  
}

table.table-striped > tbody > tr > td{
  border: 1px solid #eeeeee !important;
  border-collapse: collapse;
  color: black;
  padding: 3px;
  vertical-align: middle;
  text-align: center;
  background-color: white;
}

thead input {
  width: 100%;
  padding: 3px;
  box-sizing: border-box;
}
thead>tr>th{
  text-align:center;
}
tfoot>tr>th{
  text-align:center;
}
td:hover {
  overflow: visible;
}
table > thead > tr > th{
  border:2px solid #f4f4f4;
  color: white;
}
#tabelmonitor{
  font-size: 0.83vw;
}

.dataTables_length {
    color: white;
  }

  .dataTables_filter {
    color: white;
  }

.zoom{
   -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  -webkit-animation: zoomin 5s ease-in infinite;
  animation: zoomin 5s ease-in infinite;
  transition: all .5s ease-in-out;
  overflow: hidden;
}
@-webkit-keyframes zoomin {
  0% {transform: scale(0.7);}
  50% {transform: scale(1);}
  100% {transform: scale(0.7);}
}
@keyframes zoomin {
  0% {transform: scale(0.7);}   
  50% {transform: scale(1);}
  100% {transform: scale(0.7);}
} /*End of Zoom in Keyframes */

/* Zoom out Keyframes */
@-webkit-keyframes zoomout {
  0% {transform: scale(0);}
  50% {transform: scale(0.5);}
  100% {transform: scale(0);}
}
@keyframes zoomout {
    0% {transform: scale(0);}
  50% {transform: scale(0.5);}
  100% {transform: scale(0);}
}/*End of Zoom out Keyframes */


#loading, #error { display: none; }

</style>
@endsection
@section('header')
<section class="content-header">
  <h1>
    <span class="text-purple">Grafik</span>
    <small>Berdasarkan Tanggal<span class="text-purple"> </span></small>
  </h1>
  <ol class="breadcrumb" id="last_update">
  </ol>
</section>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content" style="padding-top: 0; padding-bottom: 0">
    <div class="row">
      <div class="col-md-12" style="padding: 1px !important">
        <div class="col-xs-2">
          <div class="input-group date">
            <div class="input-group-addon bg-green" style="border: none;">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" class="form-control datepicker" id="datefrom" placeholder="Select Date From">
          </div>
        </div>
        <div class="col-xs-2">
          <div class="input-group date">
            <div class="input-group-addon bg-green" style="border: none;">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" class="form-control datepicker" id="dateto" placeholder="Select Date To">
          </div>
        </div>
        <div class="col-xs-2">
          <div class="input-group">
            <div class="input-group-addon bg-blue">
              <i class="fa fa-search"></i>
            </div>
            <select class="form-control select2" multiple="multiple" id="status" data-placeholder="Pilih Status" style="border-color: #605ca8" >
                <option value="cpar">CPAR</option>
                <option value="car">Penanganan</option>
                <option value="verif">Verifikasi</option>
                <option value="commended">Revisi</option>
                <option value="rejected">Ditolak</option>
                <option value="close">Close</option>
            </select>
          </div>
        </div>

        <div class="col-xs-1">
          <button class="btn btn-success" onclick="drawChart()">Update Chart</button>
        </div>

      </div>

      
      
      <div class="col-md-7" style="margin-top: 5px; padding-right: 0;padding-left: 10px">
          <div id="chart" style="width: 99%; height: 300px;"></div>
      </div>

      <div class="col-md-5" style="margin-top: 5px; padding-right: 0;padding-left: 10px">
          <div id="chart_kategori" style="width: 99%; height: 300px;"></div>
      </div>
      
      <div class="col-md-12" style="padding-right: 0;padding-left: 10px">
          <table id="tabelmonitor" class="table table-bordered" style="margin-top: 5px; width: 99%">
            <thead style="background-color: rgb(255,255,255); color: rgb(0,0,0); font-size: 12px;font-weight: bold">
              <tr>
                <th style="width: 5%;vertical-align: middle;;font-size: 16px;">Kategori</th>
                <th style="width: 5%;vertical-align: middle;border-left:3px solid #357a38 !important;font-size: 16px;">Tanggal</th>
                <th style="width: 10%;vertical-align: middle;border-left:3px solid #357a38 !important;font-size: 16px;">Lokasi</th>
                <th style="width: 30%;vertical-align: middle;border-left:3px solid #357a38 !important;font-size: 16px;">Permasalahan</th>
                <th style="width: 7%;vertical-align: middle;border-left:3px solid #357a38 !important;font-size: 16px;">CPAR</th>
                <th style="width: 7%;vertical-align: middle;border-left:3px solid #357a38 !important;font-size: 16px;">Standarisasi</th>
                <th style="width: 7%;vertical-align: middle;border-left:3px solid #357a38 !important;font-size: 16px;">Penanganan</th>
                <th style="width: 7%;vertical-align: middle;border-left:3px solid #357a38 !important;font-size: 16px;background-color:#448aff;">Verification</th>
              </tr>
            </thead>
            <tbody id="tabelisi">
            </tbody>
            <tfoot>
            </tfoot>
          </table>
      </div>

      <div class="col-md-12" style="margin-top: 5px; padding-right: 0;padding-left: 10px">
          <div id="chart_klausul" style="width: 99%; height: 300px;"></div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="myModal">
    <div class="modal-dialog" style="width:1250px;">
      <div class="modal-content">
        <div class="modal-header">
          <h4 style="float: right;" id="modal-title"></h4>
          <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCTS INDONESIA</b></h4>
          <br><h4 class="modal-title" id="judul_table"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <table id="example2" class="table table-striped table-bordered table-hover" style="width: 100%;"> 
                <thead style="background-color: rgba(126,86,134,.7);">
                  <tr>
                    <th>Tanggal</th>
                    <th>Auditor</th>
                    <th>Auditee</th>
                    <th>Lokasi</th>
                    <th>Permasalahan</th>
                    <!-- <th>Foto</th> -->
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="myModalKategori">
    <div class="modal-dialog" style="width:1250px;">
      <div class="modal-content">
        <div class="modal-header">
          <h4 style="float: right;" id="modal-title"></h4>
          <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCTS INDONESIA</b></h4>
          <br><h4 class="modal-title" id="judul_table_kategori"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <table id="example3" class="table table-striped table-bordered table-hover" style="width: 100%;"> 
                <thead style="background-color: rgba(126,86,134,.7);">
                  <tr>
                   <!--  <th>Nomor</th> 
                    <th>Kategori</th>  -->
                    <th>Tanggal</th>
                    <th>Auditor</th>
                    <th>Auditee</th>
                    <th>Lokasi</th>
                    <th>Permasalahan</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="myModalKlausul">
    <div class="modal-dialog" style="width:1250px;">
      <div class="modal-content">
        <div class="modal-header">
          <h4 style="float: right;" id="modal-title"></h4>
          <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCTS INDONESIA</b></h4>
          <br><h4 class="modal-title" id="judul_table_klausul"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <table id="example4" class="table table-striped table-bordered table-hover" style="width: 100%;"> 
                <thead style="background-color: rgba(126,86,134,.7);">
                  <tr>
                    <th>Tanggal</th>
                    <th>Auditor</th>
                    <th>Auditee</th>
                    <th>Lokasi</th>
                    <th>Permasalahan</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Foto</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
        </div>
      </div>
    </div>
  </div>

</section>
@endsection

@section('scripts')
<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
<script src="{{ url("js/highcharts.js")}}"></script>
<script src="{{ url("js/exporting.js")}}"></script>
<script src="{{ url("js/export-data.js")}}"></script>
<script src="{{ url("js/accessibility.js")}}"></script>
<script src="{{ url("js/drilldown.js")}}"></script>

<script src="{{ url("js/dataTables.buttons.min.js")}}"></script>
<script src="{{ url("js/buttons.flash.min.js")}}"></script>
<script src="{{ url("js/jszip.min.js")}}"></script>
<script src="{{ url("js/vfs_fonts.js")}}"></script>
<script src="{{ url("js/buttons.html5.min.js")}}"></script>
<script src="{{ url("js/buttons.print.min.js")}}"></script>
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  jQuery(document).ready(function() {
    $('.select2').select2();
    drawChart();
    fetchTable();
    setInterval(fetchTable, 300000);
  });

  $('.datepicker').datepicker({
    autoclose: true,
    format: "yyyy-mm-dd",
    todayHighlight: true,
  });

  function drawChart() {    
    fetchTable();

    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();
    var status = $('#status').val();

    var data = {
      datefrom: datefrom,
      dateto: dateto,
      status: status,
    };

    $.get('{{ url("fetch/audit_iso/monitoring") }}', data, function(result, status, xhr) {
        if(result.status){

          var month = []; 
          var tgl = [];
          var bulan = [];
          var tahun = [];
          var cpar = [];
          var penanganan = [];
          var verif = [];
          var close = [];
          var reject = [];
          var revised = [];


          var auditor_jenis = [];
          var jumlah = [];

          var klausul = [];
          var good = [];
          var not_good = [];
          var none = [];

          $.each(result.datas, function(key, value) {
            // tgl.push(value.tanggal);

            bulan.push(value.period);
            tahun.push(value.tahun_period);

            cpar.push({y: parseInt(value.cpar),key:value.tahun_period});
            penanganan.push({y: parseInt(value.car),key:value.tahun_period});
            verif.push({y: parseInt(value.verification),key:value.tahun_period});
            close.push({y: parseInt(value.close),key:value.tahun_period});
            reject.push({y: parseInt(value.rejected),key:value.tahun_period});
            revised.push({y: parseInt(value.revised),key:value.tahun_period});
          });

          $.each(result.data_kategori, function(key, value) {
            auditor_jenis.push(value.auditor_jenis);
            jumlah.push(parseInt(value.jumlah));
          });

          $.each(result.data_klausul, function(key, value) {
            klausul.push(value.klausul);
            good.push(parseInt(value.good));
            not_good.push(parseInt(value.not_good));
            none.push(parseInt(value.none));
          })

          $('#chart').highcharts({
            chart: {
              type: 'column'
            },
            title: {
              text: 'Audit ISO 45001'
            },
            xAxis: {
              categories: bulan,
              type: 'category',
              gridLineWidth: 0,
              gridLineColor: 'RGB(204,255,255)',
              lineWidth:1,
              lineColor:'#9e9e9e',
              labels: {
                formatter: function (e) {
                  return 'Periode '+ this.value +' '+tahun[(this.pos)];
                }
              },
            },
            yAxis: {
              lineWidth:2,
              lineColor:'#fff',
              type: 'linear',
                title: {
                  text: 'Total Temuan'
                },
              tickInterval: 2,  
              stackLabels: {
                  enabled: true,
                  style: {
                      fontWeight: 'bold',
                      color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                  }
              }
            },
            legend: {
              borderWidth: 1,
              shadow: false,
              reversed: false,
              itemStyle:{
                color: "white",
                fontSize: "12px",
                fontWeight: "bold",

              }
            },
            plotOptions: {
              series: {
                cursor: 'pointer',
                point: {
                  events: {
                    click: function () {
                      ShowModal(this.category,this.series.name,this.options.key);
                    }
                  }
                },
                borderWidth: 0,
                dataLabels: {
                  enabled: false,
                  format: '{point.y}'
                }
              },
              column: {
                  color:  Highcharts.ColorString,
                  stacking: 'normal',
                  borderRadius: 1,
                  dataLabels: {
                      enabled: true
                  }
              }
            },
            credits: {
              enabled: false
            },

            tooltip: {
              formatter:function(){
                return this.series.name+' : ' + this.y;
              }
            },
            series: [
              {
                  name: 'CPAR',
                  data: cpar,
                  color: '#ff6666',
              },
              {
                  name: 'Reject',
                  data: reject,
                  color: '#0000ff',
              },
              {
                  name: 'Revised',
                  data: revised,
                  color: '#00ffff',
              },
              {
                  name: 'Verifikasi',
                  data: verif,
                  color : '#448aff' //f5f500
              },
              {
                  name: 'Car',
                  data: penanganan,
                  color : '#f0ad4e' //f5f500
              },
              {
                  name: 'Close',
                  data: close,
                  color : '#5cb85c' //00f57f
              }
            ]
          });

          $('#chart_kategori').highcharts({
            chart: {
              type: 'column'
            },
            title: {
              text: 'Audit ISO 45001 By Kategori'
            },
            xAxis: {
              categories: auditor_jenis,
              type: 'category',
              gridLineWidth: 0,
              gridLineColor: 'RGB(204,255,255)',
              lineWidth:1,
              lineColor:'#9e9e9e',
              labels: {
                formatter: function (e) {
                  return this.value;
                }
              },
            },
            yAxis: {
              lineWidth:2,
              lineColor:'#fff',
              type: 'linear',
                title: {
                  text: 'Total Temuan'
                },
              tickInterval: 2,  
              stackLabels: {
                  enabled: true,
                  style: {
                      fontWeight: 'bold',
                      color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                  }
              }
            },
            legend: {
              enabled:false
            },
            plotOptions: {
              series: {
                cursor: 'pointer',
                point: {
                  events: {
                    click: function () {
                      ShowModalKategori(this.category);
                    }
                  }
                },
                borderWidth: 0,
                dataLabels: {
                  enabled: false,
                  format: '{point.y}'
                }
              },
              column: {
                  color:  Highcharts.ColorString,
                  borderRadius: 1,
                  dataLabels: {
                      enabled: true
                  }
              }
            },
            credits: {
              enabled: false
            },

            tooltip: {
              formatter:function(){
                return this.series.name+' : ' + this.y;
              }
            },
            series: [
              {
                  name: 'Jumlah',
                  data: jumlah,
                  colorByPoint: true,
                  color: '#9e9e9e',
              },
            ]
          });

          $('#chart_klausul').highcharts({
            chart: {
              type: 'column'
            },
            title: {
              text: 'Report Audit By Klausul'
            },
            xAxis: {
              type: 'category',
              categories: klausul,
              lineWidth:2,
              lineColor:'#9e9e9e',
              gridLineWidth: 1,
              labels: {
                style: {
                    fontWeight:'Bold'
                  }
              }
            },
            yAxis: {
              lineWidth:2,
              lineColor:'#fff',
              type: 'linear',
                title: {
                  text: 'Total Temuan'
                },
              tickInterval: 2,  
              stackLabels: {
                  enabled: true,
                  style: {
                      fontWeight: 'bold',
                      color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                  }
              }
            },
            legend: {
              align: 'right',
              x: -30,
              verticalAlign: 'top',
              y: 10,
              floating: true,
              borderWidth: 1,
              shadow: false,
              reversed: true,
              itemStyle:{
                color: "white",
                fontSize: "12px",
                fontWeight: "bold",

              }
            },
            plotOptions: {
              series: {
                cursor: 'pointer',
                point: {
                  events: {
                    click: function () {
                      ShowModalKlausul(this.category,this.series.name);
                    }
                  }
                },
                borderWidth: 0,
                dataLabels: {
                  enabled: false,
                  format: '{point.y}'
                }
              },
              column: {
                  color:  Highcharts.ColorString,
                  stacking: 'normal',
                  borderRadius: 1,
                  dataLabels: {
                      enabled: true
                  }
              }
            },
            credits: {
              enabled: false
            },

            tooltip: {
              formatter:function(){
                return this.series.name+' : ' + this.y;
              }
            },
            series: [
              {
                  name: 'None',
                  data: none,
                  color : '#00ffff' //00f57f
              },
              {
                  name: 'Not Good',
                  data: not_good,
                  color : '#ff6666' //00f57f
              },
              {
                  name: 'Good',
                  data: good,
                  color : '#5cb85c' //00f57f
              }
            ]
          })
        } else{
          alert('Attempt to retrieve data failed');
        }
    })
  }

  function ShowModal(bulan, status, tahun) {


    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();
    console.log(datefrom);

    tabel = $('#example2').DataTable();
    tabel.destroy();

    $("#myModal").modal("show");

    var table = $('#example2').DataTable({
      'dom': 'Bfrtip',
      'responsive': true,
      'lengthMenu': [
      [ 10, 25, 50, -1 ],
      [ '10 rows', '25 rows', '50 rows', 'Show all' ]
      ],
      'buttons': {
        buttons:[
        {
          extend: 'pageLength',
          className: 'btn btn-default',
          // text: '<i class="fa fa-print"></i> Show',
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
      'searching': true,
      'ordering': true,
      'order': [],
      'info': true,
      'autoWidth': true,
      "sPaginationType": "full_numbers",
      "bJQueryUI": true,
      "bAutoWidth": false,
      "processing": true,
      "serverSide": true,
      "ajax": {
          "type" : "get",
          "url" : "{{ url("index/audit_iso/detail") }}",
          "data" : {
            bulan : bulan,
            status : status,
            tahun : tahun,
            datefrom : datefrom,
            dateto : dateto,
          }
        },
      "columns": [
          // { "data": "audit_no", "width": "5%"},
          // { "data": "auditor_kategori", "width": "10%"},
          { "data": "auditor_date" , "width": "5%"},
          { "data": "auditor_name" , "width": "5%"},
          { "data": "auditee_name" , "width": "5%"},
          { "data": "auditor_lokasi" , "width": "5%"},
          { "data": "auditor_permasalahan" , "width": "5%"},
          { "data": "status", "width": "7%"},
          { "data": "action", "width": "5%"}
        ]    });

    $('#judul_table').append().empty();
    $('#judul_table').append('<center><b>List Audit ISO Periode '+bulan+' '+tahun+'</b></center>'); 
  }

  function ShowModalKategori(kategori) {


    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();


    tabel = $('#example3').DataTable();
    tabel.destroy();

    $("#myModalKategori").modal("show");

    var table = $('#example3').DataTable({
      'dom': 'Bfrtip',
      'responsive': true,
      'lengthMenu': [
      [ 10, 25, 50, -1 ],
      [ '10 rows', '25 rows', '50 rows', 'Show all' ]
      ],
      'buttons': {
        buttons:[
        {
          extend: 'pageLength',
          className: 'btn btn-default',
          // text: '<i class="fa fa-print"></i> Show',
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
      'searching': true,
      'ordering': true,
      'order': [],
      'info': true,
      'autoWidth': true,
      "sPaginationType": "full_numbers",
      "bJQueryUI": true,
      "bAutoWidth": false,
      "processing": true,
      "serverSide": true,
      "ajax": {
          "type" : "get",
          "url" : "{{ url("index/audit_iso/detail_kategori") }}",
          "data" : {
            kategori : kategori,
            datefrom : datefrom,
            dateto : dateto,
          }
        },
      "columns": [
          // { "data": "audit_no", "width": "5%"},
          // { "data": "auditor_kategori", "width": "10%"},
          { "data": "auditor_date" , "width": "5%"},
          { "data": "auditor_name" , "width": "5%"},
          { "data": "auditee_name" , "width": "5%"},
          { "data": "auditor_lokasi" , "width": "5%"},
          { "data": "auditor_permasalahan" , "width": "5%"},
          { "data": "status", "width": "7%"},
          { "data": "action", "width": "5%"}
        ]    });

    $('#judul_table_kategori').append().empty();
    $('#judul_table_kategori').append('<center><b>List Audit ISO Kategori '+kategori+'</b></center>'); 
  }

  function ShowModalKlausul(klausul, status) {

    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();

    tabel = $('#example4').DataTable();
    tabel.destroy();

    $("#myModalKlausul").modal("show");

    var table = $('#example4').DataTable({
      'dom': 'Bfrtip',
      'responsive': true,
      'lengthMenu': [
      [ 10, 25, 50, -1 ],
      [ '10 rows', '25 rows', '50 rows', 'Show all' ]
      ],
      'buttons': {
        buttons:[
        {
          extend: 'pageLength',
          className: 'btn btn-default',
          // text: '<i class="fa fa-print"></i> Show',
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
      'searching': true,
      'ordering': true,
      'order': [],
      'info': true,
      'autoWidth': true,
      "sPaginationType": "full_numbers",
      "bJQueryUI": true,
      "bAutoWidth": false,
      "processing": true,
      "serverSide": true,
      "ajax": {
          "type" : "get",
          "url" : "{{ url("index/audit_iso/detail_klausul") }}",
          "data" : {
            klausul : klausul,
            status : status,
            datefrom : datefrom,
            dateto : dateto,
          }
        },
      "columns": [
          { "data": "tanggal", "width": "5%"},
          { "data": "auditor_name", "width": "5%"},
          { "data": "auditee_name" , "width": "5%"},
          { "data": "lokasi" , "width": "5%"},
          { "data": "point_judul" , "width": "10%"},
          { "data": "status", "width": "7%"},
          { "data": "note", "width": "10%"},
          { "data": "foto", "width": "15%"},
        ]    });

    $('#judul_table_klausul').append().empty();
    $('#judul_table_klausul').append('<center><b>List Audit ISO klausul '+klausul+'</b></center>'); 
  }



  function fetchTable(){

    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();
    var status = $('#status').val();

    var data = {
      datefrom: datefrom,
      dateto: dateto,
      status: status
    };

    $.get('{{ url("index/audit_iso/table") }}', data, function(result, status, xhr){
      if(result.status){


          $('#tabelmonitor').DataTable().clear();
          $('#tabelmonitor').DataTable().destroy();

          $("#tabelisi").find("td").remove();  
          $('#tabelisi').html("");
          var table = "";
          var statusauditor = "";
          var statusstd = "";
          var statusauditee = "";
          var statusverif = "";
          var reject = "";

          $.each(result.datas, function(key, value) {


            var nama_auditor = value.auditor_name;
            var nama_auditor2 = nama_auditor.split(' ').slice(0,2).join(' ');

            var nama_auditee = value.auditee_name;
            var nama_auditee2 = nama_auditee.split(' ').slice(0,2).join(' ');


            var color = "";
            var d = 0;

            var urldetail = '{{ url("index/audit_iso/detail/") }}';
            var urlverifikasi = '{{ url("index/audit_iso/verifikasistd/") }}';
            var urlresponse = '{{ url("index/audit_iso/response/") }}';
            var urlprint = '{{ url("index/audit_iso/print/") }}';
            
            var urldetailcar = '{{ url("index/audit_iso/response/") }}';

            if (value.posisi != "auditor") {
              statusauditor = '<a href="'+urlprint+'/'+value.id+'"><span class="label label-success">'+nama_auditor2+'</span></a>';
              color = 'style="background-color:green"';
            }

            else {
              if (d == 0) {  
                  statusauditor = '<a href="'+urldetail+'/'+value.id+'"><span class="label label-danger">'+nama_auditor2+'</span></a>';
                  color = 'style="background-color:red"';                    
                  d = 1;
                } else {
                  statusauditor = '';
                }
            }

            if (value.posisi == "std" && value.posisi != "auditor") {
              if (d == 0) {
                statusstd = '<a href="'+urlverifikasi+'/'+value.id+'"><span class="label label-danger"> Verifikasi Standarisasi </span></a>';   
                color = 'style="background-color:red"';                  
                d = 1;
              } else {
                statusstd = '';
              }
            }
            else{
              if (d == 0) {
                statusstd = '<a href="'+urlprint+'/'+value.id+'"><span class="label label-success"> Verifikasi Standarisasi </span></a>';
                color = 'style="background-color:green"';                 
              }
              else{
                statusstd = '';
              }

            }

            if (value.posisi == "auditee" && value.status == "car") {
              if (d == 0) {
                statusauditee = '<a href="'+urlresponse+'/'+value.id+'"><span class="label label-danger">'+nama_auditee2+'</span></a>';   
                color = 'style="background-color:red"';                  
                d = 1;
              } else {
                statusauditee = '';
              }
            }
            else{
               if (d == 0) {
                  statusauditee = '<a href="'+urlprint+'/'+value.id+'"><span class="label label-success">'+nama_auditee2+'</span></a>';
                  color = 'style="background-color:green"';                
                }
                else{
                  statusauditee = '';
                } 
            }

            if (value.posisi == "auditor_final" && value.status == "verif") {
              if (d == 0) {
                statusverif = '<a href="'+urldetail+'/'+value.id+'"><span class="label label-danger">'+nama_auditor2+'</span></a>';   
                color = 'style="background-color:red"';                  
                d = 1;
              } else {
                statusverif = '';
              }
            }
            else{
                if (d == 0) {
                  statusverif = '<a href="'+urlprint+'/'+value.id+'"><span class="label label-success">'+nama_auditor2+'</span></a>';
                  color = 'style="background-color:green"';                
                }
                else{
                  statusverif = '';
                } 
            }

            // '+value.audit_no+' - 

            table += '<tr>';
            table += '<td>'+value.auditor_kategori+'</td>';
            table += '<td style="border-left:3px solid #357a38"><span class="label label-warning">'+value.auditor_date+'</span></td>';
            table += '<td style="border-left:3px solid #357a38">'+value.auditor_lokasi+'</td>'
            table += '<td style="border-left:3px solid #357a38">'+value.auditor_permasalahan+'</td>';;
            table += '<td style="border-left:3px solid #357a38">'+statusauditor+'</td>';
            table += '<td style="border-left:3px solid #357a38">'+statusstd+'</td>';
            table += '<td style="border-left:3px solid #357a38">'+statusauditee+'</td>';
            table += '<td style="border-left:3px solid #357a38">'+statusverif+'</td>';
            table += '</tr>';
          })

          $('#tabelisi').append(table);

          $('#tabelmonitor').DataTable({
          'responsive':true,
          'paging': true,
          'lengthChange': false,
          'pageLength': 25,
          'searching': true,
          'ordering': true,
          'order': [],
          'info': false,
          'autoWidth': true,
          "sPaginationType": "full_numbers",
          "bJQueryUI": true,
          "bAutoWidth": false,
          "processing": true
        });
      }
    })
  }

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  Highcharts.createElement('link', {
      href: '{{ url("fonts/UnicaOne.css")}}',
      rel: 'stylesheet',
      type: 'text/css'
    }, null, document.getElementsByTagName('head')[0]);

    Highcharts.theme = {
      colors: ['#2b908f', '#90ee7e', '#f45b5b', '#7798BF', '#aaeeee', '#ff0066',
      '#eeaaee', '#55BF3B', '#DF5353', '#7798BF', '#aaeeee'],
      chart: {
        backgroundColor: {
          linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
          stops: [
          [0, '#2a2a2b']
          ]
        },
        style: {
          fontFamily: 'sans-serif'
        },
        plotBorderColor: '#606063'
      },
      title: {
        style: {
          color: '#E0E0E3',
          textTransform: 'uppercase',
          fontSize: '20px'
        }
      },
      subtitle: {
        style: {
          color: '#E0E0E3',
          textTransform: 'uppercase'
        }
      },
      xAxis: {
        gridLineColor: '#707073',
        labels: {
          style: {
            color: '#E0E0E3'
          }
        },
        lineColor: '#707073',
        minorGridLineColor: '#505053',
        tickColor: '#707073',
        title: {
          style: {
            color: '#A0A0A3'

          }
        }
      },
      yAxis: {
        gridLineColor: '#707073',
        labels: {
          style: {
            color: '#E0E0E3'
          }
        },
        lineColor: '#707073',
        minorGridLineColor: '#505053',
        tickColor: '#707073',
        tickWidth: 1,
        title: {
          style: {
            color: '#A0A0A3'
          }
        }
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.85)',
        style: {
          color: '#F0F0F0'
        }
      },
      plotOptions: {
        series: {
          dataLabels: {
            color: 'white'
          },
          marker: {
            lineColor: '#333'
          }
        },
        boxplot: {
          fillColor: '#505053'
        },
        candlestick: {
          lineColor: 'white'
        },
        errorbar: {
          color: 'white'
        }
      },
      legend: {
        // itemStyle: {
        //   color: '#E0E0E3'
        // },
        // itemHoverStyle: {
        //   color: '#FFF'
        // },
        // itemHiddenStyle: {
        //   color: '#606063'
        // }
      },
      credits: {
        style: {
          color: '#666'
        }
      },
      labels: {
        style: {
          color: '#707073'
        }
      },

      drilldown: {
        activeAxisLabelStyle: {
          color: '#F0F0F3'
        },
        activeDataLabelStyle: {
          color: '#F0F0F3'
        }
      },

      navigation: {
        buttonOptions: {
          symbolStroke: '#DDDDDD',
          theme: {
            fill: '#505053'
          }
        }
      },

      rangeSelector: {
        buttonTheme: {
          fill: '#505053',
          stroke: '#000000',
          style: {
            color: '#CCC'
          },
          states: {
            hover: {
              fill: '#707073',
              stroke: '#000000',
              style: {
                color: 'white'
              }
            },
            select: {
              fill: '#000003',
              stroke: '#000000',
              style: {
                color: 'white'
              }
            }
          }
        },
        inputBoxBorderColor: '#505053',
        inputStyle: {
          backgroundColor: '#333',
          color: 'silver'
        },
        labelStyle: {
          color: 'silver'
        }
      },

      navigator: {
        handles: {
          backgroundColor: '#666',
          borderColor: '#AAA'
        },
        outlineColor: '#CCC',
        maskFill: 'rgba(255,255,255,0.1)',
        series: {
          color: '#7798BF',
          lineColor: '#A6C7ED'
        },
        xAxis: {
          gridLineColor: '#505053'
        }
      },

      scrollbar: {
        barBackgroundColor: '#808083',
        barBorderColor: '#808083',
        buttonArrowColor: '#CCC',
        buttonBackgroundColor: '#606063',
        buttonBorderColor: '#606063',
        rifleColor: '#FFF',
        trackBackgroundColor: '#404043',
        trackBorderColor: '#404043'
      },

      legendBackgroundColor: 'rgba(0, 0, 0, 0.5)',
      background2: '#505053',
      dataLabelsColor: '#B0B0B3',
      textColor: '#C0C0C0',
      contrastTextColor: '#F0F0F3',
      maskColor: 'rgba(255,255,255,0.3)'
    };
    Highcharts.setOptions(Highcharts.theme);
        
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
@stop