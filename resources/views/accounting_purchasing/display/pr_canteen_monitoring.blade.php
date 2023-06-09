@extends('layouts.display')
@section('stylesheets')
<link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
<style type="text/css">

table.table-bordered{
  border:1px solid rgb(150,150,150);
}
table.table-bordered > thead > tr > th{
  border:1px solid rgb(54, 59, 56) !important;
  text-align: center;
  background-color: #212121;  
  color:white;
}
table.table-bordered > tbody > tr > td{
  border:1px solid rgb(54, 59, 56);
  background-color: #212121;
  color: white;
  vertical-align: middle;
  text-align: center;
  padding:6px;
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

hr { background-color: red; height: 1px; border: 0; }
#loading, #error { display: none; }

</style>
@endsection
@section('header')
<section class="content-header">
  <h1>
    <span class="text-purple">PR Monitoring & Control</span>
  </h1>
  <br>
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
      </div>

      <div class="col-md-12">
        
          <div class="col-md-12" style="margin-top: 5px; padding: 0 !important">
              <div id="chart" style="width: 100%"></div>
          </div>

          <div class="col-md-12" style="margin-top: 5px;background-color: #000;text-align: center;background-color: #1a237e">
              <span style="font-size: 24px;font-weight: bold;color: white;">Progress Approval PR</span>
          </div>
          <table class="table table-bordered" style="margin-top: 5px; width: 100%">
            <thead style="background-color: rgb(255,255,255); color: rgb(0,0,0); font-size: 12px;font-weight: bold">
              <tr>
                <th style="width: 15%; padding: 8px;vertical-align: middle;font-size: 20px;background-color: #3f51b5">No PR</th>
                <th style="width: 15%; padding: 8px;vertical-align: middle;font-size: 20px;background-color: #3f51b5">Submission Date</th>
                <th style="width: 15%; padding: 8px;vertical-align: middle;font-size: 20px;background-color: #3f51b5">Staff</th>
                <th style="width: 15%; padding: 8px;vertical-align: middle;font-size: 20px;background-color: #3f51b5">Manager</th>
                <th style="width: 15%; padding: 8px;vertical-align: middle;font-size: 20px;background-color: #3f51b5">GM</th>
                <th style="width: 15%; padding: 8px;vertical-align: middle;font-size: 20px;background-color: #3f51b5">Received By Purchasing</th>
              </tr>
            </thead>
            <tbody id="tabelisi">
            </tbody>
            <tfoot>
            </tfoot>
          </table>


          <div class="col-md-12" style="margin-top: 5px; padding:0 !important">
              <div id="chartundone" style="width: 100%"></div>
          </div>

           <div class="col-md-8 col-md-offset-2" style="margin-top: 5px; padding: 0 !important">
            <table id="tabelmonitor" class="table table-bordered" style="margin-top: 20px; width: 100%">
              <thead style="background-color: rgb(255,255,255); color: rgb(0,0,0); font-size: 12px;font-weight: bold">
                <tr>
                  <th style="width: 10%; padding: 5;vertical-align: middle;font-size: 16px;color: black;background-color: #f57f17">No PR</th>
                  <th style="width: 10%; padding: 5;vertical-align: middle;border-left:3px solid #000 !important;font-size: 16px;color: black; background-color: #f57f17">Kode Item</th>
                  <th style="width: 15%; padding: 5;vertical-align: middle;border-left:3px solid #000 !important;font-size: 16px;color: black; background-color: #f57f17">Deskripsi</th>
                  <th style="width: 10%; padding: 5;vertical-align: middle;border-left:3px solid #000 !important;font-size: 16px;color: black; background-color: #f57f17">Request Date</th>
                </tr>
              </thead>
              <tbody id="tabelisipo_undone">
              </tbody>
              <tfoot>
              </tfoot>
            </table>
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
                    <th>Nomor PR</th>
                    <th>Submission Date</th>
                    <th>User</th>
                    <th>Nomor Budget</th>
                    <th>Att</th>
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

   <div class="modal fade" id="modalPO">
    <div class="modal-dialog" style="width:1250px;">
      <div class="modal-content">
        <div class="modal-header">
          <h4 style="float: right;" id="modal-title"></h4>
          <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCTS INDONESIA</b></h4>
          <br><h4 class="modal-title" id="judul_table_po"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <table id="tabelPO" class="table table-striped table-bordered table-hover" style="width: 100%;"> 
                <thead style="background-color: rgba(126,86,134,.7);">
                  <tr>
                    <th>Nomor PR</th>
                    <th>Kode Item</th>
                    <th>Deskripsi</th>
                    <th>Request Date</th>
                    <th>Mata Uang</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th style="background-color:#2196f3 !important">Last Order</th>
                    <th style="background-color:#2196f3 !important">Last Vendor</th>
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
<script src="{{ url("js/highcharts-3d.js")}}"></script>
<script src="{{ url("js/exporting.js")}}"></script>
<script src="{{ url("js/export-data.js")}}"></script>

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
    $('body').toggleClass("sidebar-collapse");
    
    $('#myModal').on('hidden.bs.modal', function () {
      $('#example2').DataTable().clear();
    });

    $('.select2').select2();

    drawChart();
    setInterval(drawChart, 120000);
  });

  var audio_error = new Audio('{{ url("sounds/error.mp3") }}');

  $('.datepicker').datepicker({
    autoclose: true,
    format: "dd-mm-yyyy",
    todayHighlight: true,
  });

  function drawChart() {
    fetchTable();
    var tglfrom = $('#tglfrom').val();
    var tglto = $('#tglto').val();

    var data = {
      tglfrom: tglfrom,
      tglto: tglto
    };

    $.get('{{ url("fetch/canteen/purchase_requisition/monitoring") }}', data, function(result, status, xhr) {
      if(xhr.status == 200){
        if(result.status){

          var tgl = [], jml = [], dept = [], jml_dept = [], not_sign = [], sign = [], no_pr = [], reff_number = [], belum_po = [], sudah_po = [], belum_po_inv = [], sudah_po_inv = [];

          $.each(result.datas, function(key, value) {
            tgl.push(value.week_date);
            // jml.push(value.jumlah);
            not_sign.push(parseInt(value.jumlah_belum));
            sign.push(parseInt(value.jumlah_sudah));
          })

          $.each(result.data_pr_belum_po, function(key, value) {
            if (value.belum_po != 0) {
              no_pr.push(value.no_pr);
              belum_po.push(parseInt(value.belum_po));
              sudah_po.push(parseInt(value.sudah_po));              
            }
          })

          $('#chart').highcharts({
            chart: {
              type: 'column'
            },
            title: {
              text: 'PR Monitoring By Date',
              style: {
                fontSize: '24px',
                fontWeight: 'bold'
              }
            },
            subtitle: {
              text: 'On '+result.year+' Last 30 Days',
              style: {
                fontSize: '0.6vw',
                fontWeight: 'bold'
              }
            },
            xAxis: {
              type: 'category',
              categories: tgl,
              lineWidth:2,
              lineColor:'#9e9e9e',
              gridLineWidth: 1
            },
            yAxis: {
              lineWidth:2,
              lineColor:'#fff',
              type: 'linear',
                title: {
                  text: 'Total PR'
                },
              tickInterval: 1,  
              stackLabels: {
                  enabled: true,
                  style: {
                      fontWeight: 'bold',
                      color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                  }
              }
            },
            legend: {
              enabled:true,
              reversed: true,
              itemStyle:{
                color: "white",
                fontSize: "12px",
                fontWeight: "bold",

              },
            },
            plotOptions: {
              series: {
                cursor: 'pointer',
                point: {
                  events: {
                    click: function () {
                      ShowModalPch(this.category,this.series.name,result.tglfrom,result.tglto);
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
                name: 'PR Incompleted',
                color: '#ff6666',
                data: not_sign
              },
              {
                name: 'PR Completed',
                color: '#00a65a',
                data: sign
              }
            ]
          })

          $('#chartundone').highcharts({
            chart: {
              type: 'column',
              height: 350
            },
            title: {
              text: 'Outstanding PR Belum PO (Per PR)',
              style: {
                fontSize: '24px',
                fontWeight: 'bold'
              }
            },
            xAxis: {
              type: 'category',
              categories: no_pr,
              lineWidth:2,
              lineColor:'#9e9e9e',
              gridLineWidth: 1
            },
            yAxis: {
              lineWidth:2,
              lineColor:'#fff',
              type: 'linear',
              title: {
                enabled:false
              },
              tickInterval: 3,  
              stackLabels: {
                  enabled: true,
                  style: {
                      fontWeight: 'bold',
                      color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                  }
              }
            },
            legend: {
              enabled:true,
              reversed: true,
              itemStyle:{
                color: "white",
                fontSize: "12px",
                fontWeight: "bold",

              },
            },
            plotOptions: {
              series: {
                cursor: 'pointer',
                point: {
                  events: {
                    click: function () {
                      ShowModalPO(this.category,this.series.name,result.datefrom,result.dateto);
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
                name: 'Belum PO',
                color: '#ff6666', //ff6666
                data: belum_po
              },
              {
                name: 'Sudah PO',
                color: '#00a65a',
                data: sudah_po
              }
            ]
          })
        }
      }
    })
  }

  

  function fetchTable(){

    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();

    var data = {
      datefrom: datefrom,
      dateto: dateto
    };

    $.get('{{ url("canteen/purchase_requisition/table") }}', data, function(result, status, xhr){
      if(xhr.status == 200){
        if(result.status){

          $('#tabelmonitor').DataTable().clear();
          $('#tabelmonitor').DataTable().destroy();

          $("#tabelisi").find("td").remove();  
          $('#tabelisi').html("");
          
          var table = "";
          var user = "";
          var manager = "";
          var dgm = "";
          var gm = "";
          var pch = "";


          $.each(result.datas, function(key, value) {

          var emp_name = value.emp_name;
          var username = emp_name.split(' ').slice(0,2).join(' ');
          var coloruser = "";

          if (value.manager_name != null) {
            var manager_name = value.manager_name;
            var managername = manager_name.split(' ').slice(0,2).join(' ');
          }
          var colormanager = "";

          if (value.dgm != null) {
            var dgm_name = value.dgm;
            var dgmname = dgm_name.split(' ').slice(0,2).join(' ');
          }

          var colordgm = "";

          if (value.gm != null) {
            var gm_name = value.gm;
            var gmname = gm_name.split(' ').slice(0,2).join(' ');
          }

          var colorgm = "";

          var colorpch = "";
          var d = 0;
          var e = 0;

            //CPAR
            var urldetail = '{{ url("canteen/purchase_requisition") }}';
            var urlreport = '{{ url("canteen/purchase_requisition/report/") }}';
            var urlverifikasi = '{{ url("canteen/purchase_requisition/verifikasi/") }}';
            var urlcheck = '{{ url("canteen/purchase_requisition/check/") }}';


            if (value.posisi != "user") {
              user = '<a href="'+urlreport+'/'+value.id+'"><span class="label label-success">'+username+'</span></a>';
              coloruser = 'style="background-color:#00a65a"';
            }
            else {
              if (d == 0) {  
                  user = '<a href="'+urldetail+'"><span class="label label-danger zoom">'+username+'</span></a>';
                  coloruser = 'style="background-color:#dd4b39"';                    
                  d = 1;
                } else {
                  user = '';
                }
            }

              //jika manager
              if (value.manager_name != null) {
                //manager
                if (value.approvalm == "Approved") {
                    if (value.posisi == "manager") {
                        if (d == 0) {  
                            manager = '<a href="'+urlverifikasi+'/'+value.id+'"><span class="label label-danger">'+managername+'</span></a>';   
                            colormanager = 'style="background-color:#dd4b39"';                  
                            d = 1;
                          } else {
                            manager = '';
                          }
                    }
                    else{
                        manager = '<a href="'+urlreport+'/'+value.id+'"><span class="label label-success">'+managername+'</span></a>';
                        colormanager = 'style="background-color:#00a65a"'; 
                    }
                }
                else{
                  if (d == 0) {  
                    manager = '<a href="'+urlverifikasi+'/'+value.id+'"><span class="label label-danger">'+managername+'</span></a>'; 
                    colormanager = 'style="background-color:#dd4b39"';                  
                    d = 1;
                  } else {
                    manager = '';
                  }
                }
              }
              else{
                manager = '<a href="'+urlverifikasi+'/'+value.id+'"><span class="label label-warning">None</span></a>';
                colormanager = 'style="background-color:#f39c12"'; 
              }

              //GM
              if (value.gm != null) {
                if (value.approvalgm == "Approved") {
                    if (value.posisi == "gm") {
                        if (d == 0) {  
                          gm = '<a href="'+urlverifikasi+'/'+value.id+'"><span class="label label-danger">'+gmname+'</span></a>';
                          colorgm = 'style="background-color:#dd4b39"'; 
                          d = 1;
                        } else {
                          gm = '';
                        }
                    } else {
                      gm = '<a href="'+urlreport+'/'+value.id+'"><span class="label label-success">'+gmname+'</span></a>'; 
                      colorgm = 'style="background-color:#00a65a"'; 
                    }
                } 

                else {
                  if (d == 0) {  
                    gm = '<a href="'+urlverifikasi+'/'+value.id+'"><span class="label label-danger">'+gmname+'</span></a>';
                    colorgm = 'style="background-color:#dd4b39"'; 
                    d = 1;
                  } else {
                    gm = '';
                  }
                }
              }
              else{
                gm = '<span style="color:white">None</span>'; 
                colorgm = 'style="background-color:#424242"';
              }

              //receive
              if (value.receive_date != null) {
                  if (value.status == "approval_acc") {
                      if (d == 0) {  
                        pch = '<a href="'+urlcheck+'/'+value.id+'"><span class="label label-danger">Purchasing</span></a>'; 
                        colorpch = 'style="background-color:#dd4b39"';
                        d = 1;
                      } else {
                        pch = '';
                      }
                  } else {
                      pch = '<a href="'+urlreport+'/'+value.id+'"><span class="label label-success">'+value.receive_date+'</span></a>'; 
                      colorpch = 'style="background-color:#00a65a"';       
                  }
              }
              else{
                if (d == 0) {  
                  pch = '<a href="'+urlcheck+'/'+value.id+'"><span class="label label-danger">Purchasing</span></a>'; 
                  colorpch = 'style="background-color:#dd4b39"';
                  d = 1;
                } else {
                  pch = '';
                }
              }

            table += '<tr>';
            table += '<td>'+value.no_pr+'</td>';
            table += '<td>'+value.submission_date+'</td>';
            table += '<td '+coloruser+'>'+user+'</td>';  
            table += '<td '+colormanager+'>'+manager+'</td>';
            table += '<td '+colorgm+'>'+gm+'</td>';
            table += '<td '+colorpch+'>'+pch+'</td>';
            table += '</tr>';


          })

          $('#tabelisi').append(table);


          $("#tabelisipo_undone").find("td").remove();  
          $('#tabelisipo_undone').html("");
          
          var table_belum_po = "";

          $.each(result.data_pr_belum_po, function(key, value) {
            table_belum_po += '<tr>';
            if (value.no_pr == "null") {
              table_belum_po += '<td></td>';
            }
            else{
              table_belum_po += '<td>'+value.no_pr+'</td>';
            }
            table_belum_po += '<td style="border-left:3px solid #000">'+value.item_code+'</td>';
            table_belum_po += '<td style="border-left:3px solid #000">'+value.item_desc+'</td>';
            table_belum_po += '<td style="border-left:3px solid #000">'+value.item_request_date+'</td>';
            // table_belum_po += '<td style="border-left:3px solid #000">'+value.receive_date+'</td>';
            table_belum_po += '</tr>';
          })

          $('#tabelisipo_undone').append(table_belum_po);

          $('#tabelmonitor').DataTable( {
            'dom': 'Bfrtip',
            'responsive':true,
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': false,
            'info': true,
            'autoWidth': true,
            "sPaginationType": "full_numbers",
            "bJQueryUI": true,
            "bAutoWidth": false,
            "processing": true
          } );

        }
      }
    })
  }

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  function ShowModalPch(tanggal, status, tglfrom, tglto) {
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
          "url" : "{{ url("canteen/purchase_requisition/detail") }}",
          "data" : {
            tanggal : tanggal,
            status : status,
            tglfrom : tglfrom,
            tglto : tglto
          }
        },
      "columns": [
          { "data": "submission_date" },
          { "data": "emp_name" },
          { "data": "no_budget" },
          { "data": "file" },
          { "data": "status" },
          { "data": "action", "width": "15%"}
        ]    });

    $('#judul_table').append().empty();
    $('#judul_table').append('<center><b>'+status+' Tanggal '+tanggal+'</center></b>');
    
  }

  function ShowModalPO(pr, status, datefrom, dateto) {
    tabel = $('#tabelPO').DataTable();
    tabel.destroy();

    $("#modalPO").modal("show");

    var table = $('#tabelPO').DataTable({
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
          "url" : "{{ url("canteen/purchase_requisition/detailPO") }}",
          "data" : {
            pr : pr,
            status : status,
            datefrom : datefrom,
            dateto : dateto
          }
        },
      "columns": [
          { "data": "no_pr", "width": "5%" },
          { "data": "item_code", "width": "5%" },
          { "data": "item_desc", "width": "20%" },
          { "data": "item_request_date", "width": "5%" },
          { "data": "item_currency", "width": "5%" },
          { "data": "item_price", "width": "5%" },
          { "data": "item_qty", "width": "5%" },
          { "data": "item_amount", "width": "5%" },
          { "data": "last_order", "width": "5%"},
          { "data": "last_vendor", "width": "10%"}
        ]    });

    $('#judul_table_po').append().empty();
    $('#judul_table_po').append('<center><b>PR '+pr+' '+status+' </center></b>');
    
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