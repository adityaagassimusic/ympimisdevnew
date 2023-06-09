@extends('layouts.display')
@section('stylesheets')
<link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
<style type="text/css">

	.container {width: auto;}
  .content-header>h1 {font-size: 3.5em; font-family: sans-serif; font-weight: bold; color: black;}
  body, .content-wrapper {background-color: #000;}
  .temp-widget {border-radius: 4px; color: black;overflow: auto; border: 1px solid purple; font-size: 0.75em; width: 40px; letter-spacing: 1.1px;}
  .temp-widget-refrigerator {border-radius: 15px 15px 0px 0px; overflow: auto; border: 1px solid white; font-size: 0.75em; width: 25px; letter-spacing: 1.1px;}
  #main-body {overflow: auto;}
  #custom-title {position: absolute; top: 40px; left: 40px; font-size: 1.5em; border: 1px solid black; border-radius: 5px; padding: 10px; background-color: rgba(0, 255, 0, 0.4);}

  .morecontent span {
    display: none;
  }
  
  .morelink {
    display: block;
  }


#title {
    position: absolute;
    top: 10px;
    left: 980px;
    font-size: 1.5em;
    border: 1px solid black;
    border-radius: 5px;
    padding: 10px;
    background-color: rgba(255, 255, 0, 0.9);
}

.dataTable > thead > tr > th[class*="sort"]:after{
 content: "" !important;
}

#queueTable.dataTable {
 margin-top: 0px!important;
}
#loading, #error { display: none; }

#parent { 
 position: relative; 
     /*width: 720px; 
     height:500px;*/
     margin-right: auto;
     margin-left: auto; 
     /*border: solid 1px red; */
     font-size: 24px; 
     text-align: center; 
   }

   .square {
    opacity: 0.8;
  }

  .squarex {
    border-radius: 4px;
    overflow: auto;
    border: 1px solid white;
    font-size: 0.75em;
    width: 35px;
    letter-spacing: 1.1px;
  }


  thead>tr>th{
    text-align:center;
  }
  .content-wrapper{
    padding-top: 0 !important;
  }

  .dot {
    height: 5%;
    width: 5%;
    position: absolute;
    z-index: 10;
  }


  .text {
    /*color: white;*/
    font-size: 1.2vw;
    font-weight: bold;
    display: inline-block;
    vertical-align: middle;
  }

  .text2 {
    color: white;
    font-size: 1.6vw;
    font-weight: bold;
  }

  .table-bordered > thead > tr > th {
    border: 1px solid #eee;
  }

  .table-bordered > thead > tr > td {
    border: 1px solid #eee;
  }

  .table-bordered > tbody > tr > td {
    border: 1px solid #eee;
  }

  .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th{
    padding: 0;
  }

  .sedang {
    -webkit-animation: sedang 1s infinite;  /* Safari 4+ */
    -moz-animation: sedang 1s infinite;  /* Fx 5+ */
    -o-animation: sedang 1s infinite;  /* Opera 12+ */
    animation: sedang 1s infinite;  /* IE 10+, Fx 29+ */
  }


  .squarex {
    border-radius: 4px;
    overflow: auto;
    border: 1px solid white;
    font-size: 0.75em;
    width: 15vw;
    letter-spacing: 1.1px;
  }

  @-webkit-keyframes sedang {
    0%, 49% {
      background: #e57373;
    }
    50%, 100% {
      background-color: #ffccff;
    }
  }

  .content{
    padding: 0;
  }


  .widget
  {
    position: relative;
    width: 20vw;
    height: 8vw;
    /*margin: 150px auto;*/
    background-color: #fcfdfd;
    border-radius: 9px;
    padding: 1vw;
    padding-right: 30px;
    box-shadow: 0px 31px 35px -26px #080c21;
  }

  .widget .left-panel
  {
    
  }

  .widget .tanggal
  {
    font-size: 1vw;
    font-weight: bold;
    color: rgba(0,0,0,0.5);
  }

  .widget .kota
  {
    font-size: 1.5vw;
    font-weight: bold;
    text-transform: uppercase;
    padding-top: 5px;
    color: rgba(0,0,0,0.7);
  }

  .widget .right-panel .temp
  {
    /*font-size: 81px;*/
    color: rgba(0,0,0,0.9);
    font-weight: 100;
    margin-left: 1.8vw;
  }

  .widget .panel
  {
    display: inline-block;
    background-color: transparent;
    box-shadow: none;
  }

  .widget .right-panel
  {
    position: absolute;
    float: right;
    top: 0;
    margin-top: 25px;
  }

@import url(https://fonts.googleapis.com/css?family=Poppins);

a {
  text-decoration: none;
  color: #00A0B0;
}
/*.showbox {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
}*/

/*.credit {
  position: absolute;
  width: 100%;
  text-align: center;
  bottom: 0;
  margin: 0 0 10px 0;
}*/

.div-icons {
  position: absolute;
  display: block;
  width: 620px;
  text-align: center;
  top: 15%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.icon {
/*  margin: 15px 25px;*/
  width: 6vw;
  height: 5vw;
}

.sunny-body path {
  fill: #EDC951;
}

.sunny-long-ray {
  transform-origin: 50% 50%;
  animation: spin 9s linear infinite;
}

.sunny-long-ray path {
  fill: #EDC951;
}

.sunny-short-ray {
  transform-origin: 50% 50%;
  animation: spin 9s linear infinite;
}

.sunny-short-ray path {
  fill: #EDC951;
}

.cloud-offset path {
  fill: #222;
}

.main-cloud path {
  fill: #00A0B0;
}

.small-cloud path {
  fill: #00A0B0;
  animation: flyby 6s linear infinite;
}

.rain-cloud path {
  fill: #00A0B0;
  animation: rain-cloud-color 6s ease infinite;
}

.rain-drops path {
  fill: #00A0B0;
  opacity: 0;
}

.rain-drops path:nth-child(1) {
  animation: rain-drop 1.2s linear infinite;
}

.rain-drops path:nth-child(2) {
  animation: rain-drop 1.2s linear infinite 0.4s;
}

.rain-drops path:nth-child(3) {
  animation: rain-drop 1.2s linear infinite 0.8s;
}

.snow-cloud path {
  fill: #ccc;
}

.snowflakes path {
  transform-origin: 50% 50%;
  fill: #ccc;
  opacity: 0;
}

.snowflakes path:nth-child(1) {
  animation: snow-drop 1.2s linear infinite;
}

.snowflakes path:nth-child(2) {
  animation: snow-drop 1.2s linear infinite 0.4s;
}

.snowflakes path:nth-child(3) {
  animation: snow-drop 1.2s linear infinite 0.8s;
}

.wind-string path {
  stroke: #ccc;
  stroke-linecap: round;
  stroke-width: 7px;
  animation: wind-blow 3s linear infinite;
}

.rainbows path {
  stroke-linecap: round;
  animation: rainbow 4.5s linear infinite;
}

.rainbows path:nth-child(1) {
  stroke: #BD1E52;
  stroke-width: 6px;
}

.rainbows path:nth-child(2) {
  stroke: #E88024;
  stroke-width: 8px;
}

.rainbows path:nth-child(3) {
  stroke: #F8CB10;
  stroke-width: 6px;
}

.rainbows path:nth-child(4) {
  stroke: #899C3B;
  stroke-width: 14px;
}

@keyframes spin {
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

@keyframes flyby {
  0% {
    -webkit-transform: translate(0px, 0px);
    transform: translate(0px, 0px);
    opacity: 0;
  }
  30% {
    -webkit-transform: translate(39px, 0px);
    transform: translate(39px, 0px);
    opacity: 1;
  }
  70% {
    -webkit-transform: translate(91px, 0px);
    transform: translate(91px, 0px);
    opacity: 1;
  }
  100% {
    -webkit-transform: translate(130px, 0px);
    transform: translate(130px, 0px);
    opacity: 0;
  }
}

@keyframes rain-cloud-color {
  100%,
  0% {
    fill: #666;
  }
  20% {
    fill: #555;
  }
  21.5% {
    fill: #999;
  }
  25% {
    fill: #555;
  }
  27.5% {
    fill: #999;
  }
  30% {
    fill: #555;
  }
  40% {
    fill: #999;
  }
  90% {
    fill: #555;
  }
}

@keyframes rain-drop {
  0% {
    -webkit-transform: translate(0px, -60px);
    transform: translate(0px, -60px);
    opacity: 0;
  }
  30% {
    -webkit-transform: translate(0px, -36px);
    transform: translate(0px, -36px);
    opacity: 1;
  }
  80% {
    -webkit-transform: translate(0px, 4px);
    transform: translate(0px, 4px);
    opacity: 1;
  }
  100% {
    -webkit-transform: translate(0px, 20px);
    transform: translate(0px, 20px);
    opacity: 0;
  }
}

@keyframes snow-drop {
  0% {
    -webkit-transform: translate(0px, -60px) rotate(0deg);
    // transform: translate(0px, -60px) rotate(0deg);
    opacity: 0;
  }
  30% {
    -webkit-transform: translate(0px, -36px) rotate(108deg);
    // transform: translate(0px, -36px) rotate(108deg);
    opacity: 1;
  }
  80% {
    -webkit-transform: translate(0px, 4px) rotate(288deg);
    // transform: translate(0px, 4px) rotate(288deg);
    opacity: 1;
  }
  100% {
    -webkit-transform: translate(0px, 20px) rotate(360deg);
    // transform: translate(0px, 20px) rotate(360deg);
    opacity: 0;
  }
}

@keyframes wind-blow {
  0% {
    stroke-dasharray: 5 300;
    stroke-dashoffset: -200;
    opacity: 1;
  }
  50% {
    stroke-dasharray: 300 300;
    stroke-dashoffset: -100;
    opacity: 1;
  }
  90% {
    stroke-dasharray: 50 300;
    stroke-dashoffset: -20;
    opacity: 0.7;
  }
  100% {
    stroke-dasharray: 20 300;
    stroke-dashoffset: 0;
    opacity: 0.2;
  }
}

@keyframes rainbow {
  0% {
    stroke-dasharray: 10 210;
    stroke-dashoffset: 0;
    opacity: 0;
  }
  30% {
    stroke-dasharray: 210 210;
    stroke-dashoffset: 0;
    opacity: 1;
  }
  70% {
    stroke-dasharray: 210 210;
    stroke-dashoffset: 0;
    opacity: 1;
  }
  100% {
    stroke-dasharray: 0 210;
    stroke-dashoffset: -210;
    opacity: 0;
  }
}

</style>
@stop
@section('header')
<section class="content-header" style="padding-top: 0; padding-bottom: 0;">

</section>
@endsection
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
  <div class="row">
    <!-- <div class="col-xs-2 pull-right"> -->
    <!-- </div> -->

 <!--      <div class="squarex text-center" style="background-color: rgba(125, 250, 140, 0.8);position: absolute; top: 305px; left: 240px;">
        <div id="temp_Tanpo" class="temperature" style="padding: 0px 2px;">1</div>
      </div> -->

      <div id="main-body">
        <img src="{{url("images/ympi_map_fix.png")}}" alt="My logo" style="opacity: 0.8" width="1400px">
        <a title="Temperature Tanpo" onclick="modalSuhu('Tanpo')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-green-active" style="position: absolute; top: 610px; left: 495px;">
            <div style="padding: 0px 4px;" id="value_temp_Tanpo">0</div>
          </div>
        </a>

        <a title="Humidity Tanpo" onclick="modalSuhu('Tanpo')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-yellow-active" style="position: absolute; top: 630px; left: 495px;">
            <div style="padding: 0px 4px;" id="value_hum_Tanpo">0</div>
          </div>
        </a>

        <a title="Temperature 3D" onclick="modalSuhu('3D')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-green-active" style="position: absolute; top: 530px; left: 495px;">
            <div style="padding: 0px 4px;" id="value_temp_3D">0</div>
          </div>
        </a>

        <a title="Humidity 3D" onclick="modalSuhu('3D')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-yellow-active" style="position: absolute; top: 550px; left: 495px;">
            <div style="padding: 0px 4px;" id="value_hum_3D">0</div>
          </div>
        </a>

        <a title="Temperature Assembly Utara" onclick="modalSuhu('Assembly_Utara')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-green-active" style="position: absolute; top: 470px; left: 255px;">
            <div style="padding: 0px 4px;" id="value_temp_Assembly_Utara">0</div>
          </div>
        </a>

        <a title="Humidity Assembly Utara" onclick="modalSuhu('Assembly_Utara')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-yellow-active" style="position: absolute; top: 490px; left: 255px;">
            <div style="padding: 0px 4px;" id="value_hum_Assembly_Utara">0</div>
          </div>
        </a>

        <a title="Temperature Assembly Selatan" onclick="modalSuhu('Assembly_Selatan')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-green-active" style="position: absolute; top: 470px; left: 345px;">
            <div style="padding: 0px 4px;" id="value_temp_Assembly_Selatan">0</div>
          </div>
        </a>

        <a title="Humidity Assembly Selatan" onclick="modalSuhu('Assembly_Selatan')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-yellow-active" style="position: absolute; top: 490px; left: 345px;">
            <div style="padding: 0px 4px;" id="value_hum_Assembly_Selatan">0</div>
          </div>
        </a>

        <a title="Temperature Stock Room" onclick="modalSuhu('Stock_Room')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-green-active" style="position: absolute; top: 440px; left: 170px;">
            <div style="padding: 0px 4px;" id="value_temp_Stock_Room">0</div>
          </div>
        </a>

        <a title="Humidity Stock Room" onclick="modalSuhu('Stock_Room')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-yellow-active" style="position: absolute; top: 460px; left: 170px;">
            <div style="padding: 0px 4px;" id="value_hum_Stock_Room">0</div>
          </div>
        </a>

        <a title="Temperature Clean Room" onclick="modalSuhu('Clean_Room')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-green-active" style="position: absolute; top: 490px; left: 87px;">
            <div style="padding: 0px 4px;" id="value_temp_Clean_Room">0</div>
          </div>
        </a>

        <a title="Humidity Clean Room" onclick="modalSuhu('Clean_Room')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-yellow-active" style="position: absolute; top: 510px; left: 87px;">
            <div style="padding: 0px 4px;" id="value_hum_Clean_Room">0</div>
          </div>
        </a>

        <a title="Temperature Warehouse lt1" onclick="modalSuhu('Warehouse_lt1')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-green-active" style="position: absolute; top: 430px; left: 917px;">
            <div style="padding: 0px 4px;" id="value_temp_warehouse_lt1">0</div>
          </div>
        </a>

        <a title="Humidity Warehouse lt1" onclick="modalSuhu('Warehouse_lt1')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-yellow-active" style="position: absolute; top: 450px; left: 917px;">
            <div style="padding: 0px 4px;" id="value_hum_warehouse_lt1">0</div>
          </div>
        </a>

        <a title="Temperature Warehouse lt2" onclick="modalSuhu('Warehouse_lt2')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-green-active" style="position: absolute; top: 430px; left: 787px;">
            <div style="padding: 0px 4px;" id="value_temp_warehouse_lt2">0</div>
          </div>
        </a>

        <a title="Humidity Warehouse lt2" onclick="modalSuhu('Warehouse_lt2')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-yellow-active" style="position: absolute; top: 450px; left: 787px;">
            <div style="padding: 0px 4px;" id="value_hum_warehouse_lt2">0</div>
          </div>
        </a>

        <a title="Temperature Seasoning CL" onclick="modalSuhu('Seasoning_cl')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-green-active" style="position: absolute; top: 590px; left: 545px;">
            <div style="padding: 0px 4px;" id="value_temp_seasoning_cl">0</div>
          </div>
        </a>

        <a title="Humidity Seasoning CL" onclick="modalSuhu('Seasoning_cl')" style="cursor:pointer;">
          <div class="temp-widget text-center bg-yellow-active" style="position: absolute; top: 610px; left: 545px;">
            <div style="padding: 0px 4px;" id="value_hum_seasoning_cl">0</div>
          </div>
        </a>


      </div>

    <div class="widget" style="position: absolute; top: 120px; left:1000px;">
            <div class="left-panel panel">
                <div class="tanggal" id="tanggal_cuaca">
                </div>
                <div class="kota" id="lokasi_cuaca">
                </div>
            </div>
            <div class="right-panel panel">
               <div class="temp">
                   <!-- <img src="https://codefrog.tech/cp/wp/ts.png" alt="" width="60"> -->
                   <!-- 27&deg; -->
                   <svg class="icon icon-sunny" viewBox="0 0 220 220" style="display: none;" id="cerah">
            <!--       Short rays -->
            <g class="sunny-short-ray">
              <path fill="#43647E" d="M111.961,65.447l-0.014-8.394c-0.003-1.617-1.318-2.927-2.935-2.925c-1.616,0.003-2.924,1.318-2.924,2.935
                l0.014,8.474C108.064,65.375,110.021,65.354,111.961,65.447z"/>
              <path fill="#43647E" d="M75.396,81.343c1.257-1.484,2.607-2.9,4.063-4.221l-5.938-5.918c-1.146-1.142-3-1.14-4.143,0.005
                c-1.142,1.146-1.139,3.001,0.008,4.142L75.396,81.343z"/>
              <path fill="#43647E" d="M163.276,112.648c0.388-0.001,0.756-0.078,1.094-0.213c1.074-0.437,1.83-1.492,1.83-2.721
                c-0.004-1.617-1.315-2.927-2.933-2.925l-8.478,0.015c0.164,1.96,0.186,3.917,0.091,5.856L163.276,112.648z"/>
              <path fill="#43647E" d="M143.207,80.158l5.918-5.937c1.144-1.146,1.14-3-0.005-4.142c-1.147-1.143-3.001-1.14-4.143,0.004
                l-5.992,6.013C140.471,77.353,141.884,78.704,143.207,80.158z"/>
              <path fill="#43647E" d="M56.353,108.382c-1.619,0.002-2.928,1.317-2.924,2.935c0.004,1.615,1.318,2.925,2.934,2.923l8.473-0.014
                c-0.16-1.963-0.182-3.917-0.088-5.858L56.353,108.382z"/>
              <path fill="#43647E" d="M144.234,139.686c-1.258,1.484-2.609,2.899-4.063,4.223l5.939,5.918c0.857,0.855,2.111,1.068,3.167,0.639
                c0.354-0.143,0.687-0.357,0.974-0.646c1.143-1.145,1.139-3-0.006-4.141L144.234,139.686z"/>
              <path fill="#43647E" d="M107.669,155.582l0.013,8.395c0.003,1.617,1.317,2.928,2.934,2.922c0.388,0,0.755-0.074,1.093-0.213
                c1.077-0.434,1.834-1.488,1.83-2.719l-0.014-8.475C111.564,155.654,109.608,155.676,107.669,155.582z"/>
              <path fill="#43647E" d="M76.421,140.871l-5.917,5.938c-1.142,1.144-1.141,2.999,0.006,4.142c0.857,0.855,2.112,1.068,3.17,0.641
                c0.354-0.144,0.687-0.361,0.972-0.646l5.991-6.012C79.159,143.676,77.743,142.326,76.421,140.871z"/>
            </g>
            <!--       Long rays -->
            <g class="sunny-long-ray">
              <path fill="#43647E" d="M138.495,51.723c0.936-2.209-0.096-4.761-2.307-5.697c-2.211-0.938-4.763,0.096-5.697,2.306l-7.959,18.792
                c-0.014,0.034-0.021,0.07-0.035,0.103c2.787,0.818,5.487,1.9,8.064,3.232L138.495,51.723z"/>
              <path fill="#43647E" d="M88.124,70.841c0.014,0.031,0.035,0.058,0.051,0.091c1.508-0.822,3.072-1.576,4.703-2.238
                c1.087-0.44,2.184-0.82,3.283-1.17l-7.639-18.862c-0.901-2.226-3.436-3.298-5.662-2.397c-2.223,0.901-3.299,3.435-2.395,5.66
                L88.124,70.841z"/>
              <path fill="#43647E" d="M47.633,89.838l18.79,7.959c0.033,0.012,0.07,0.021,0.104,0.032c0.818-2.786,1.901-5.485,3.234-8.061
                l-18.74-7.935c-2.209-0.937-4.761,0.098-5.696,2.308C44.388,86.354,45.423,88.904,47.633,89.838z"/>
              <path fill="#43647E" d="M149.397,88.874c0.821,1.508,1.576,3.074,2.236,4.705c0.439,1.088,0.821,2.183,1.171,3.284l18.862-7.638
                c2.226-0.902,3.299-3.437,2.398-5.661c-0.901-2.224-3.437-3.299-5.661-2.398l-18.916,7.66
                C149.458,88.837,149.43,88.859,149.397,88.874z"/>
              <path fill="#43647E" d="M81.135,169.308c-0.937,2.21,0.097,4.761,2.308,5.696c1.105,0.469,2.295,0.445,3.324,0.027
                c1.034-0.418,1.905-1.229,2.371-2.334l7.959-18.789c0.016-0.035,0.023-0.071,0.037-0.104c-2.787-0.818-5.488-1.901-8.065-3.233
                L81.135,169.308z"/>
              <path fill="#43647E" d="M131.503,150.19c-0.012-0.033-0.031-0.062-0.047-0.093c-1.508,0.822-3.074,1.574-4.704,2.238
                c-1.089,0.439-2.185,0.82-3.284,1.17l7.639,18.863c0.901,2.225,3.436,3.297,5.662,2.395c2.223-0.901,3.297-3.434,2.397-5.659
                L131.503,150.19z"/>
              <path fill="#43647E" d="M70.233,132.157c-0.824-1.51-1.578-3.074-2.238-4.707c-0.441-1.085-0.821-2.183-1.171-3.282l-18.862,7.641
                c-2.225,0.899-3.297,3.436-2.396,5.658c0.9,2.227,3.435,3.299,5.66,2.398l18.914-7.66C70.173,132.191,70.2,132.172,70.233,132.157z
                "/>
              <path fill="#43647E" d="M171.997,131.191l-18.791-7.959c-0.033-0.014-0.068-0.02-0.104-0.033c-0.818,2.786-1.9,5.484-3.234,8.062
                l18.739,7.936c1.104,0.467,2.295,0.443,3.327,0.025c1.029-0.417,1.902-1.228,2.371-2.334
                C175.24,134.678,174.207,132.127,171.997,131.191z"/>
            </g>
            <!--       Sun body -->
            <g class="sunny-body">
              <path fill="#43647E" d="M142.702,97.196c-7.357-18.162-28.043-26.923-46.205-19.568c-18.164,7.356-26.925,28.045-19.568,46.205
              c7.354,18.165,28.043,26.926,46.205,19.569C141.298,136.045,150.058,115.36,142.702,97.196z M117.348,84.979
              c-0.411,1.812-2.217,2.948-4.026,2.535c-4.427-1.007-8.997-0.636-13.221,1.075c-5.488,2.224-9.782,6.45-12.091,11.9
              c-2.308,5.452-2.356,11.475-0.134,16.964c0.697,1.721-0.134,3.684-1.857,4.381c-0.413,0.168-0.841,0.248-1.262,0.248
              c-1.33,0-2.588-0.795-3.117-2.104c-2.898-7.154-2.836-15.008,0.174-22.113c3.007-7.108,8.605-12.619,15.76-15.516
              c5.504-2.229,11.469-2.715,17.241-1.398C116.626,81.363,117.762,83.167,117.348,84.979z"/>
            </g>
            </svg>
            

            <!--       Cloudy -->
            <svg class="icon icon-cloudy" viewBox="0 0 220 220" style="display: none;" id="berawan">
        <!--       Small cloud -->
              <g class="small-cloud">
            <path fill="#43647E" d="M69.054,67.463c-5.109-9.405-15.105-15.409-25.866-15.409c-14.947,0-27.066,10.456-29.036,24.651
              C6.634,78.396,1,85.121,1,93.143c0,9.293,7.561,16.854,16.853,16.854c3.911,0,7.547-1.27,10.472-3.617
              c4.715,3.022,9.6,4.497,14.864,4.497c4.978,0,8.361-0.792,12.25-2.944c3.312,1.927,7.053,2.944,10.932,2.944
              c12.016,0,21.792-9.776,21.792-21.792C88.162,77.976,79.807,68.789,69.054,67.463z"/></g>
                  <!--       Cloud offset -->
                  <g class="cloud-offset">
                  <path fill="#43647E" d="M113.903,179.264c-6.173,0-12.273-1.229-17.931-3.585
              c-6.062,2.515-12.218,3.585-19.999,3.585c-8.325,0-16.356-1.866-23.959-5.559c-5.329,2.711-11.262,4.119-17.492,4.119
              c-21.27,0-38.574-17.306-38.574-38.576c0-15.345,9.325-29.175,22.996-35.269c6.653-25.268,29.615-42.96,57.029-42.96
              c19.873,0,38.259,9.958,49.18,26.313c20.532,5.085,35.406,23.653,35.406,45.276C160.56,158.334,139.63,179.264,113.903,179.264z"/></g>
            <!--       Main cloud -->
                  <g class="main-cloud">
            <path fill="#43647E" d="M118.294,97.231c-8.359-15.388-24.715-25.212-42.32-25.212c-24.457,0-44.283,17.108-47.506,40.333
              c-12.301,2.767-21.52,13.771-21.52,26.896c0,15.205,12.369,27.576,27.574,27.576c6.396,0,12.348-2.078,17.133-5.917
              c7.713,4.944,15.705,7.356,24.318,7.356c8.145,0,13.68-1.295,20.043-4.816c5.418,3.152,11.541,4.816,17.887,4.816
                                    c19.662,0,35.656-15.996,35.656-35.656C149.56,114.432,135.888,99.401,118.294,97.231z"/></g>
            </svg>
            <!--     Windy -->
            <svg class="icon icon-windy" viewBox="0 0 220 220" style="display: none;" id="berangin">
        <!--       Small cloud -->
              <g class="small-cloud">
            <path fill="#43647E" d="M69.054,67.463c-5.109-9.405-15.105-15.409-25.866-15.409c-14.947,0-27.066,10.456-29.036,24.651
              C6.634,78.396,1,85.121,1,93.143c0,9.293,7.561,16.854,16.853,16.854c3.911,0,7.547-1.27,10.472-3.617
              c4.715,3.022,9.6,4.497,14.864,4.497c4.978,0,8.361-0.792,12.25-2.944c3.312,1.927,7.053,2.944,10.932,2.944
              c12.016,0,21.792-9.776,21.792-21.792C88.162,77.976,79.807,68.789,69.054,67.463z"/></g>
                  <!--       Cloud offset -->
                  <g class="cloud-offset">
                  <path fill="#43647E" d="M113.903,179.264c-6.173,0-12.273-1.229-17.931-3.585
              c-6.062,2.515-12.218,3.585-19.999,3.585c-8.325,0-16.356-1.866-23.959-5.559c-5.329,2.711-11.262,4.119-17.492,4.119
              c-21.27,0-38.574-17.306-38.574-38.576c0-15.345,9.325-29.175,22.996-35.269c6.653-25.268,29.615-42.96,57.029-42.96
              c19.873,0,38.259,9.958,49.18,26.313c20.532,5.085,35.406,23.653,35.406,45.276C160.56,158.334,139.63,179.264,113.903,179.264z"/></g>
            <!--       Main cloud -->
                  <g class="main-cloud">
            <path fill="#43647E" d="M118.294,97.231c-8.359-15.388-24.715-25.212-42.32-25.212c-24.457,0-44.283,17.108-47.506,40.333
              c-12.301,2.767-21.52,13.771-21.52,26.896c0,15.205,12.369,27.576,27.574,27.576c6.396,0,12.348-2.078,17.133-5.917
              c7.713,4.944,15.705,7.356,24.318,7.356c8.145,0,13.68-1.295,20.043-4.816c5.418,3.152,11.541,4.816,17.887,4.816
                                    c19.662,0,35.656-15.996,35.656-35.656C149.56,114.432,135.888,99.401,118.294,97.231z"/></g>
                  <g class="wind-string">
              <path fill="none" stroke="#43637D" stroke-miterlimit="10" d="M85.263,105.176
                c3.002-1.646,6.403-2.549,9.903-2.549c11.375,0,20.633,9.256,20.633,20.633s-9.258,20.633-20.633,20.633H3.473"/>
              <path fill="none" stroke="#43637D" stroke-miterlimit="10" d="M69.756,113.884
                c1.62-0.888,3.457-1.376,5.345-1.376c6.14,0,11.136,4.996,11.136,11.137c0,6.14-4.996,11.136-11.136,11.136H25.313"/>
              <path fill="none" stroke="#43637D" stroke-miterlimit="10" d="M75.536,180.462
                c2.131,1.166,4.545,1.809,7.027,1.809c8.072,0,14.642-6.569,14.642-14.643s-6.569-14.643-14.642-14.643H18.043"/>
            </g>
            </svg>
                <!--       Rainy -->
                <svg class="icon icon-rainy" viewBox="0 0 220 220" style="display: none;" id="hujan">
            <g class="rain-drops">
              <path fill="#43647E" d="M69.942,143.08c-0.852,6.32-11.666,18.842-11.666,27.824c0,6.443,5.225,11.664,11.666,11.664
                c6.443,0,11.666-5.221,11.666-11.664C81.608,161.521,70.696,149.551,69.942,143.08z"/>
              <path fill="#43647E" d="M110.126,143.08c-0.854,6.32-11.666,18.842-11.666,27.824c0,6.443,5.223,11.664,11.666,11.664
                s11.666-5.221,11.666-11.664C121.792,161.521,110.878,149.551,110.126,143.08z"/>
              <path fill="#43647E" d="M150.308,143.08c-0.854,6.32-11.664,18.842-11.664,27.824c0,6.443,5.223,11.664,11.664,11.664
                c6.445,0,11.666-5.221,11.666-11.664C161.974,161.521,151.062,149.551,150.308,143.08z"/>
            </g>
                  <g class="cloud-offset">
                    <path fill="#43647E" d="M144.901,144.943c-6.173,0-12.273-1.229-17.932-3.586c-6.06,2.516-12.216,3.586-19.998,3.586
              c-8.323,0-16.355-1.867-23.959-5.56c-5.329,2.71-11.261,4.118-17.492,4.118c-21.27,0-38.574-17.305-38.574-38.575
              c0-15.344,9.324-29.174,22.996-35.267c6.651-25.269,29.613-42.961,57.03-42.961c19.872,0,38.257,9.958,49.177,26.311
              c20.533,5.087,35.409,23.656,35.409,45.277C191.558,124.014,170.628,144.943,144.901,144.943z"/>
                  </g>
                  <g class="rain-cloud">
              <path fill="#43647E" d="M150.288,62.909c-8.357-15.386-24.713-25.209-42.316-25.209c-24.459,0-44.285,17.107-47.506,40.334
                c-12.301,2.766-21.52,13.77-21.52,26.894c0,15.204,12.369,27.575,27.574,27.575c6.396,0,12.348-2.076,17.133-5.916
                c7.713,4.943,15.701,7.357,24.318,7.357c8.145,0,13.682-1.295,20.041-4.818c5.42,3.154,11.541,4.818,17.889,4.818
                c19.66,0,35.656-15.996,35.656-35.656C181.558,80.111,167.886,65.081,150.288,62.909z"/>
                  </g>
                </svg>
                <!--     Sunshower -->
                
                <!--     Snowy -->
                <svg class="icon icon-sunshower" viewBox="0 0 220 220" style="display: none;" id="dingin">
            <g class="snowflakes">
              <path fill="#43647E" d="M84.535,166.239l-5.663,1.73l-3.644-2.104c0.089-0.392,0.141-0.798,0.141-1.218
                c0-0.418-0.052-0.824-0.141-1.216l3.645-2.104l5.662,1.729c0.156,0.048,0.314,0.071,0.47,0.071c0.688,0,1.324-0.445,1.536-1.138
                c0.26-0.849-0.218-1.747-1.067-2.006l-2.795-0.854l1.482-0.856c0.769-0.443,1.032-1.426,0.588-2.194s-1.426-1.032-2.195-0.589
                l-1.483,0.856l0.658-2.848c0.2-0.865-0.339-1.728-1.204-1.928c-0.865-0.2-1.728,0.339-1.927,1.204l-1.333,5.769l-3.648,2.106
                c-0.595-0.553-1.309-0.979-2.104-1.224v-4.204l4.33-4.039c0.649-0.605,0.685-1.621,0.079-2.271
                c-0.605-0.648-1.622-0.685-2.271-0.078l-2.138,1.993v-1.712c0-0.888-0.72-1.607-1.606-1.607c-0.888,0-1.607,0.72-1.607,1.607v1.712
                l-2.138-1.993c-0.648-0.606-1.666-0.57-2.271,0.078c-0.605,0.649-0.57,1.665,0.079,2.271l4.33,4.039v4.204
                c-0.795,0.245-1.509,0.67-2.104,1.224l-3.649-2.106l-1.332-5.77c-0.2-0.864-1.062-1.403-1.927-1.203
                c-0.865,0.199-1.403,1.063-1.204,1.927l0.658,2.849l-1.483-0.856c-0.769-0.443-1.752-0.18-2.195,0.589
                c-0.444,0.768-0.18,1.751,0.588,2.194l1.483,0.856l-2.796,0.854c-0.849,0.26-1.326,1.158-1.067,2.006
                c0.212,0.693,0.848,1.139,1.537,1.139c0.155,0,0.313-0.023,0.47-0.071l5.662-1.729l3.645,2.104
                c-0.09,0.393-0.142,0.798-0.142,1.217s0.052,0.825,0.142,1.218l-3.646,2.104l-5.662-1.73c-0.848-0.259-1.747,0.218-2.006,1.067
                c-0.259,0.849,0.219,1.746,1.067,2.006l2.796,0.854l-1.483,0.856c-0.769,0.443-1.032,1.427-0.588,2.195
                c0.298,0.515,0.838,0.804,1.393,0.804c0.273,0,0.549-0.07,0.802-0.216l1.483-0.856l-0.658,2.849
                c-0.2,0.864,0.339,1.728,1.204,1.927c0.121,0.028,0.243,0.042,0.362,0.042c0.731,0,1.393-0.503,1.564-1.245l1.333-5.769
                l3.649-2.107c0.595,0.553,1.31,0.979,2.104,1.224v4.204l-4.329,4.039c-0.649,0.604-0.685,1.622-0.079,2.271
                c0.605,0.649,1.623,0.685,2.271,0.079l2.137-1.994v1.712c0,0.888,0.72,1.607,1.606,1.607c0.887,0,1.607-0.72,1.607-1.607v-1.712
                l2.138,1.994c0.31,0.289,0.703,0.432,1.095,0.432c0.431,0,0.859-0.171,1.176-0.511c0.605-0.648,0.57-1.666-0.079-2.271l-4.33-4.039
                v-4.204c0.795-0.245,1.509-0.671,2.104-1.224l3.649,2.107l1.333,5.769c0.171,0.743,0.833,1.245,1.564,1.245
                c0.12,0,0.241-0.014,0.362-0.042c0.865-0.199,1.404-1.063,1.205-1.927l-0.658-2.849l1.482,0.856
                c0.253,0.146,0.529,0.216,0.802,0.216c0.556,0,1.096-0.288,1.393-0.804c0.444-0.769,0.181-1.751-0.588-2.194l-1.483-0.857
                l2.796-0.854c0.849-0.259,1.327-1.157,1.067-2.006C86.281,166.457,85.382,165.979,84.535,166.239z M69.906,167.54
                c-1.594,0-2.892-1.297-2.892-2.893c0-1.594,1.297-2.892,2.892-2.892c1.595,0,2.893,1.298,2.893,2.892
                C72.798,166.243,71.501,167.54,69.906,167.54z"/>
              <path fill="#43647E" d="M123.582,166.239l-5.662,1.73l-3.645-2.104c0.09-0.392,0.142-0.798,0.142-1.218
                c0-0.418-0.052-0.824-0.142-1.216l3.645-2.104l5.662,1.729c0.156,0.048,0.314,0.071,0.471,0.071c0.688,0,1.324-0.445,1.535-1.138
                c0.26-0.849-0.218-1.747-1.066-2.006l-2.795-0.854l1.482-0.856c0.768-0.443,1.031-1.426,0.588-2.194s-1.426-1.032-2.195-0.589
                l-1.482,0.856l0.658-2.848c0.2-0.865-0.339-1.728-1.203-1.928c-0.865-0.2-1.729,0.339-1.928,1.204l-1.333,5.769l-3.648,2.106
                c-0.595-0.553-1.31-0.979-2.104-1.224v-4.204l4.33-4.039c0.648-0.605,0.685-1.621,0.078-2.271c-0.604-0.648-1.621-0.685-2.27-0.078
                l-2.138,1.993v-1.712c0-0.888-0.72-1.607-1.606-1.607c-0.888,0-1.607,0.72-1.607,1.607v1.712l-2.138-1.993
                c-0.648-0.606-1.666-0.57-2.271,0.078c-0.605,0.649-0.57,1.665,0.079,2.271l4.33,4.039v4.204c-0.795,0.245-1.509,0.67-2.104,1.224
                l-3.649-2.106l-1.332-5.77c-0.2-0.864-1.062-1.403-1.927-1.203c-0.865,0.199-1.403,1.063-1.204,1.927l0.658,2.849l-1.483-0.856
                c-0.769-0.443-1.752-0.18-2.195,0.589c-0.444,0.768-0.18,1.751,0.588,2.194l1.483,0.856l-2.796,0.854
                c-0.849,0.26-1.326,1.158-1.067,2.006c0.212,0.693,0.848,1.139,1.537,1.139c0.155,0,0.313-0.023,0.47-0.071l5.662-1.729
                l3.645,2.104c-0.09,0.393-0.142,0.798-0.142,1.217s0.052,0.825,0.142,1.218l-3.646,2.104l-5.662-1.73
                c-0.848-0.259-1.747,0.218-2.006,1.067c-0.259,0.849,0.219,1.746,1.067,2.006l2.796,0.854l-1.483,0.856
                c-0.769,0.443-1.032,1.427-0.588,2.195c0.298,0.515,0.838,0.804,1.393,0.804c0.273,0,0.549-0.07,0.802-0.216l1.483-0.856
                l-0.658,2.849c-0.2,0.864,0.339,1.728,1.204,1.927c0.121,0.028,0.243,0.042,0.362,0.042c0.731,0,1.393-0.503,1.564-1.245
                l1.333-5.769l3.649-2.107c0.595,0.553,1.31,0.979,2.104,1.224v4.204l-4.329,4.039c-0.649,0.604-0.685,1.622-0.079,2.271
                c0.605,0.649,1.623,0.685,2.271,0.079l2.137-1.994v1.712c0,0.888,0.72,1.607,1.606,1.607c0.887,0,1.607-0.72,1.607-1.607v-1.712
                l2.138,1.994c0.31,0.289,0.703,0.432,1.095,0.432c0.432,0,0.859-0.171,1.176-0.511c0.605-0.648,0.57-1.666-0.078-2.271l-4.33-4.039
                v-4.204c0.795-0.245,1.51-0.671,2.104-1.224l3.65,2.107l1.332,5.769c0.172,0.743,0.832,1.245,1.564,1.245
                c0.119,0,0.24-0.014,0.361-0.042c0.865-0.199,1.404-1.063,1.205-1.927l-0.658-2.849l1.482,0.856
                c0.254,0.146,0.529,0.216,0.802,0.216c0.556,0,1.097-0.288,1.394-0.804c0.443-0.769,0.18-1.751-0.588-2.194l-1.483-0.857
                l2.796-0.854c0.849-0.259,1.326-1.157,1.066-2.006C125.328,166.457,124.43,165.979,123.582,166.239z M108.954,167.54
                c-1.594,0-2.892-1.297-2.892-2.893c0-1.594,1.297-2.892,2.892-2.892c1.595,0,2.892,1.298,2.892,2.892
                C111.846,166.243,110.549,167.54,108.954,167.54z"/>
              <path fill="#43647E" d="M162.632,166.239l-5.662,1.73l-3.645-2.104c0.09-0.392,0.142-0.798,0.142-1.218
                c0-0.418-0.052-0.824-0.142-1.216l3.645-2.104l5.662,1.729c0.156,0.048,0.314,0.071,0.471,0.071c0.688,0,1.324-0.445,1.535-1.138
                c0.26-0.849-0.218-1.747-1.066-2.006l-2.795-0.854l1.482-0.856c0.768-0.443,1.031-1.426,0.588-2.194s-1.426-1.032-2.195-0.589
                l-1.482,0.856l0.658-2.848c0.2-0.865-0.339-1.728-1.203-1.928c-0.865-0.2-1.729,0.339-1.928,1.204l-1.333,5.769l-3.648,2.106
                c-0.595-0.553-1.31-0.979-2.104-1.224v-4.204l4.329-4.039c0.648-0.605,0.685-1.621,0.078-2.271
                c-0.604-0.648-1.621-0.685-2.27-0.078l-2.138,1.993v-1.712c0-0.888-0.721-1.607-1.607-1.607s-1.606,0.72-1.606,1.607v1.712
                l-2.138-1.993c-0.648-0.606-1.666-0.57-2.271,0.078c-0.605,0.649-0.57,1.665,0.08,2.271l4.329,4.039v4.204
                c-0.795,0.245-1.509,0.67-2.104,1.224l-3.648-2.106l-1.332-5.77c-0.2-0.864-1.063-1.403-1.928-1.203
                c-0.865,0.199-1.403,1.063-1.203,1.927l0.658,2.849l-1.483-0.856c-0.769-0.443-1.752-0.18-2.195,0.589
                c-0.444,0.768-0.181,1.751,0.589,2.194l1.482,0.856l-2.796,0.854c-0.849,0.26-1.326,1.158-1.067,2.006
                c0.212,0.693,0.848,1.139,1.537,1.139c0.154,0,0.313-0.023,0.469-0.071l5.662-1.729l3.646,2.104
                c-0.09,0.393-0.142,0.798-0.142,1.217s0.052,0.825,0.142,1.218l-3.646,2.104l-5.662-1.73c-0.848-0.259-1.746,0.218-2.006,1.067
                c-0.259,0.849,0.219,1.746,1.067,2.006l2.796,0.854l-1.482,0.856c-0.77,0.443-1.033,1.427-0.589,2.195
                c0.298,0.515,0.838,0.804,1.394,0.804c0.272,0,0.549-0.07,0.802-0.216l1.483-0.856l-0.658,2.849
                c-0.201,0.864,0.338,1.728,1.203,1.927c0.121,0.028,0.243,0.042,0.362,0.042c0.731,0,1.394-0.503,1.564-1.245l1.333-5.769
                l3.648-2.107c0.595,0.553,1.31,0.979,2.104,1.224v4.204l-4.328,4.039c-0.65,0.604-0.686,1.622-0.08,2.271
                c0.605,0.649,1.623,0.685,2.271,0.079l2.137-1.994v1.712c0,0.888,0.721,1.607,1.607,1.607s1.606-0.72,1.606-1.607v-1.712
                l2.138,1.994c0.31,0.289,0.703,0.432,1.095,0.432c0.432,0,0.859-0.171,1.176-0.511c0.605-0.648,0.57-1.666-0.078-2.271l-4.33-4.039
                v-4.204c0.795-0.245,1.51-0.671,2.104-1.224l3.65,2.107l1.332,5.769c0.172,0.743,0.832,1.245,1.564,1.245
                c0.119,0,0.24-0.014,0.361-0.042c0.865-0.199,1.404-1.063,1.205-1.927l-0.658-2.849l1.482,0.856
                c0.254,0.146,0.529,0.216,0.802,0.216c0.556,0,1.097-0.288,1.394-0.804c0.443-0.769,0.18-1.751-0.588-2.194l-1.483-0.857
                l2.796-0.854c0.849-0.259,1.326-1.157,1.066-2.006C164.378,166.457,163.479,165.979,162.632,166.239z M148.004,167.54
                c-1.595,0-2.893-1.297-2.893-2.893c0-1.594,1.298-2.892,2.893-2.892s2.892,1.298,2.892,2.892
                C150.896,166.243,149.599,167.54,148.004,167.54z"/>
            </g>
            <g class="cloud-offset">
              <path fill="#43647E" d="M144.979,144.945c-6.177,0-12.277-1.229-17.934-3.585c-6.06,2.515-12.216,3.585-19.997,3.585
                c-8.326,0-16.357-1.866-23.96-5.56c-5.329,2.71-11.261,4.118-17.491,4.118c-21.271,0-38.576-17.305-38.576-38.575
                c0-15.344,9.325-29.173,22.996-35.267c6.651-25.269,29.614-42.96,57.032-42.96c19.87,0,38.255,9.958,49.176,26.31
                c20.533,5.087,35.41,23.656,35.41,45.278C191.635,124.016,170.705,144.945,144.979,144.945z"/>
            </g>
                  <g class="snow-cloud">
              <path fill="#43647E" d="M149.365,62.911c-8.359-15.386-24.712-25.209-42.316-25.209c-24.461,0-44.287,17.107-47.508,40.333
                c-12.299,2.766-21.52,13.77-21.52,26.894c0,15.206,12.369,27.575,27.576,27.575c6.395,0,12.346-2.076,17.133-5.916
                c7.713,4.945,15.701,7.357,24.318,7.357c8.141,0,13.678-1.293,20.041-4.818c5.419,3.156,11.542,4.818,17.89,4.818
                c19.658,0,35.655-15.994,35.655-35.656C180.635,80.114,166.961,65.083,149.365,62.911z"/>
            </g>
          </svg>
                </div>
            </div>

      </div>


    <div class="col-xs-12" id="map" style="padding:0">

   <!--    <center>
        <img src="{{url("images/ympi_map_fix.png")}}"  style="width: 1350px;opacity:0.8">
      </center>
 -->

      <!-- <div class="squarex text" style="position: absolute; top: 40vw; left:32vw;">
        <center>
          <table style="width: 100%;margin-bottom: 0;" class="table table-bordered">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Tanpo</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_Tanpo">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_Tanpo">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="min_temp_Tanpo">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="max_temp_Tanpo">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="min_hum_Tanpo">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="max_hum_Tanpo">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);" id="temp_Tanpo">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);" id="hum_Tanpo">0</td>
              </tr>
            </tbody>
          </table>
        </center>
      </div>

      <div class="squarex text" style="position: absolute; top: 33vw; left:32vw;">
        <center>
          <table style="width: 100%;margin-bottom: 0;" class="table table-bordered">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">3D</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_3D">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_3D">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="min_temp_3D">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="max_temp_3D">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="min_hum_3D">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="max_hum_3D">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);" id="temp_3D">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);" id="hum_3D">0</td>
              </tr>
            </tbody>
          </table>
        </center>
      </div>

    <div class="squarex text" style="position: absolute; top: 28vw; left:17vw;">
        <center>
          <table style="width: 100%;margin-bottom: 0;" class="table table-bordered">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Assembly Utara</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_Assembly_Utara">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_Assembly_Utara">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="min_temp_Assembly_Utara">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="max_temp_Assembly_Utara">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="min_hum_Assembly_Utara">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="max_hum_Assembly_Utara">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);" id="temp_Assembly_Utara">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);" id="hum_Assembly_Utara">0</td>
              </tr>
            </tbody>
          </table>
        </center>
      </div>

    <div class="squarex text" style="position: absolute; top: 36vw; left:17vw;">
        <center>
          <table style="width: 100%;margin-bottom: 0;" class="table table-bordered">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Assembly Selatan</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_Assembly_Selatan">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_Assembly_Selatan">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="min_temp_Assembly_Selatan">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="max_temp_Assembly_Selatan">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="min_hum_Assembly_Selatan">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="max_hum_Assembly_Selatan">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);" id="temp_Assembly_Selatan">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);" id="hum_Assembly_Selatan">0</td>
              </tr>
            </tbody>
          </table>
        </center>
      </div>

    <div class="squarex text" style="position: absolute; top: 28vw; left:2vw;">
        <center>
          <table style="width: 100%;margin-bottom: 0;" class="table table-bordered">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Stock Room</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_Stock_Room">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_Stock_Room">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="min_temp_Stock_Room">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="max_temp_Stock_Room">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="min_hum_Stock_Room">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="max_hum_Stock_Room">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);" id="temp_Stock_Room">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);" id="hum_Stock_Room">0</td>
              </tr>
            </tbody>
          </table>
        </center>
      </div>

      <div class="squarex text" style="position: absolute; top: 36vw; left:2vw;">
        <center>
          <table style="width: 100%;margin-bottom: 0;" class="table table-bordered">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Clean Room</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_Clean_Room">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_Clean_Room">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="min_temp_Clean_Room">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="max_temp_Clean_Room">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="min_hum_Clean_Room">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="max_hum_Clean_Room">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);" id="temp_Clean_Room">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);" id="hum_Clean_Room">0</td>
              </tr>
            </tbody>
          </table>
        </center>
      </div>

      <div class="squarex text" style="position: absolute; top: 23vw; left:57vw;">
        <center>
          <table style="width: 100%;margin-bottom: 0;" class="table table-bordered">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Warehouse Lt 1</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_warehouse_1">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_warehouse_1">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);">0</td>
              </tr>
            </tbody>
          </table>
        </center>
      </div>

      <div class="squarex text" style="position: absolute; top: 30vw; left:57vw;">
        <center>
          <table style="width: 100%;margin-bottom: 0;" class="table table-bordered">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Warehouse Lt 2</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_warehouse_2">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_warehouse_2">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);">0</td>
              </tr>
            </tbody>
          </table>
        </center>
      </div> -->

    <form method="GET" action="{{ url("export/temperature") }}">
      <div class="col-md-12" style="margin-top:20px;padding-left: 1px;">
        <div class="col-md-2" style="padding-left:0">
          <div class="input-group date">
            <div class="input-group-addon bg-green">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" class="form-control dateexport" id="date_from" name="date_from" placeholder="Select Date From">
          </div>
        </div>
        <div class="col-md-2"  style="padding-left:0">
          <div class="input-group date">
            <div class="input-group-addon bg-green">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" class="form-control dateexport" id="date_to" name="date_to" placeholder="Select Date To">
          </div>
        </div>
        <div class="col-md-2"  style="padding-left:0">
           <select class="form-control select2" id="location" name="location" data-placeholder="Select Location" style="width: 100%;border-color: #605ca8" >
              <option value=""></option>
              @foreach($location as $loc)
              <option value="{{ $loc->location }}">{{ $loc->location }}</option>
              @endforeach
            </select>
        </div>
        <div class="col-xs-2">
              <button type="submit" class="btn btn-success form-control" style="width: 100%"><i class="fa fa-file-excel-o"></i> &nbsp;&nbsp;Download Log Data</button>
        </div>

        <div class="col-xs-4">
          <button class="btn btn-success pull-right" onclick="AddCuaca()" style="width: 50%; margin-bottom: 5px;"><i class="fa fa-plus"></i> Add Cuaca</button>
        </div>

       </div>
   </form>

    <div class="col-xs-12" style="padding-left: 0;padding-right: 0;">
        @foreach($location as $loc)
        <?php 
        $locfix = str_replace(" ","_",$loc->location);
        ?>
        <div class="col-xs-4" style="border: 2px solid black; height: 30vh; padding: 0; background: white;margin-top: 5px;">
          <!-- <center><span style="font-weight: bold; font-size: 3vh; color: white;">{{$loc->location}}</span></center> -->
          <div class="col-xs-12" style="padding: 0;">
            <div class="col-xs-12" style="padding: 0;">
              <div id="chart_{{$locfix}}" style="height: 30vh;">
              </div>
            </div>
          
          </div>
        </div>
        @endforeach
    </div>  
  </div>
</section>


<div class="modal fade" id="modalCuaca">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <center style="background-color: green">
          <span style="font-weight: bold; font-size: 1.5vw;color: white">Menambahkan Cuaca</span>
        </center>
        <hr>
        <div class="modal-body table-responsive no-padding" style="min-height: 100px; padding-bottom: 5px;">
          <form class="form-horizontal">
            <div class="col-xs-12" style="padding-bottom: 5px;">
              <div class="form-group">
                <label for="inputor" class="col-sm-2 control-label">Inputor<span class="text-red">*</span></label>
                <div class="col-sm-6">
                  <input type="text" style="width: 100%" class="form-control" name="inputor" id="inputor" placeholder="Inputor Name" value="{{Auth::user()->name }}" readonly>
                </div>
              </div>
              <div class="form-group">
                <label for="createStart" class="col-sm-2 control-label">Waktu<span class="text-red">*</span></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <div class="input-group-addon bg-purple" style="border: none;">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control dateexport" id="input_date" placeholder="Select Date" >
                  </div>
                </div>
                <div class="col-sm-3">    
                  <div class="input-group date">
                    <div class="input-group-addon bg-purple" style="border: none;">
                      <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" class="form-control timepicker" id="input_time" placeholder="Select Time" value="0:00">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="editRemark" class="col-sm-2 control-label">Jenis Cuaca<span class="text-red">*</span></label>
                <div class="col-sm-6">
                  <select class="form-control select5" style="width: 100%" id="input_cuaca" data-placeholder="Pilih Jenis Cuaca">
                    <option value=""></option>
                    <option value="Panas">Panas</option>
                    <option value="Berawan">Berawan</option>
                    <option value="Hujan">Hujan</option>
                    <option value="Cerah">Cerah</option>
                    <option value="Berangin">Berangin</option>
                    <option value="Dingin">Dingin</option>
                  </select>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="box-footer">
          <div class="col-xs-12">
            <a class="btn btn-success pull-right" onclick="save()" style="font-size: 1.2vw; font-weight: bold">Simpan</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalSuhu">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <center style="background-color: green">
          <span style="font-weight: bold; font-size: 1.5vw;color: white">Detail Data Temperature Humidity Lokasi <span id="suhu_lokasi"></span>
        </center>
        <hr>
        <div class="modal-body table-responsive no-padding" style="min-height: 100px; padding-bottom: 5px;">
          <form class="form-horizontal">
            <div class="col-xs-12" style="padding-bottom: 5px;">
        <center>
          <table style="width: 100%;margin-bottom: 0;display:none" class="table table-bordered" id="table_Tanpo">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Tanpo</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_Tanpo">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_Tanpo">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="min_temp_Tanpo">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="max_temp_Tanpo">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="min_hum_Tanpo">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="max_hum_Tanpo">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);" id="temp_Tanpo">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);" id="hum_Tanpo">0</td>
              </tr>
            </tbody>
          </table>
        </center>
        <center>
          <table style="width: 100%;margin-bottom: 0;display:none" class="table table-bordered" id="table_3D">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">3D</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_3D">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_3D">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="min_temp_3D">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="max_temp_3D">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="min_hum_3D">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="max_hum_3D">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);" id="temp_3D">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);" id="hum_3D">0</td>
              </tr>
            </tbody>
          </table>
        </center>
        <center>
          <table style="width: 100%;margin-bottom: 0;display:none" class="table table-bordered" id="table_Assembly_Utara">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Assembly Utara</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_Assembly_Utara">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_Assembly_Utara">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="min_temp_Assembly_Utara">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="max_temp_Assembly_Utara">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="min_hum_Assembly_Utara">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="max_hum_Assembly_Utara">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);" id="temp_Assembly_Utara">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);" id="hum_Assembly_Utara">0</td>
              </tr>
            </tbody>
          </table>
        </center>
        <center>
          <table style="width: 100%;margin-bottom: 0;display:none" class="table table-bordered" id="table_Assembly_Selatan">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Assembly Selatan</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_Assembly_Selatan">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_Assembly_Selatan">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="min_temp_Assembly_Selatan">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="max_temp_Assembly_Selatan">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="min_hum_Assembly_Selatan">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="max_hum_Assembly_Selatan">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);" id="temp_Assembly_Selatan">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);" id="hum_Assembly_Selatan">0</td>
              </tr>
            </tbody>
          </table>
        </center>

        <center>
          <table style="width: 100%;margin-bottom: 0;display:none" class="table table-bordered" id="table_Stock_Room">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Stock Room</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_Stock_Room">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_Stock_Room">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="min_temp_Stock_Room">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="max_temp_Stock_Room">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="min_hum_Stock_Room">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="max_hum_Stock_Room">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);" id="temp_Stock_Room">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);" id="hum_Stock_Room">0</td>
              </tr>
            </tbody>
          </table>
        </center>

        <center>
          <table style="width: 100%;margin-bottom: 0;display:none" class="table table-bordered" id="table_Clean_Room">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Clean Room</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_Clean_Room">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_Clean_Room">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="min_temp_Clean_Room">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;" id="max_temp_Clean_Room">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="min_hum_Clean_Room">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;" id="max_hum_Clean_Room">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);" id="temp_Clean_Room">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);" id="hum_Clean_Room">0</td>
              </tr>
            </tbody>
          </table>
        </center>

        <center>
          <table style="width: 100%;margin-bottom: 0;display:none" class="table table-bordered" id="table_warehouse_lt1">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Warehouse Lt 1</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_warehouse_lt1">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_warehouse_lt1">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);">0</td>
              </tr>
            </tbody>
          </table>
        </center>

        <center>
          <table style="width: 100%;margin-bottom: 0;display:none" class="table table-bordered" id="table_warehouse_lt2">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Warehouse Lt 2</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_warehouse_lt2">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_warehouse_lt2">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);">0</td>
              </tr>
            </tbody>
          </table>
        </center>

        <center>
          <table style="width: 100%;margin-bottom: 0;display:none" class="table table-bordered" id="table_seasoning_cl">
            <thead>
              <tr>
                <td colspan="4" style="background-color: rgba(112,48,160,0.8) !important; text-align: center; font-size: 1vw; font-weight: bold;color: white;">Seasoning CL</td>
              </tr>
              <tr>
                <td colspan="2" style="background-color: rgba(125, 250, 140, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_temp_seasoning_cl">Temp <img src="{{url('images/temp.png')}}" width="20"></td>
                <td colspan="2" style="background-color: rgba(238, 241, 50, 0.8); text-align: center; font-size: 1vw; font-weight: bold;" id="title_hum_seasoning_cl">Hum <img src="{{url('images/hum.png')}}" width="20"></td>
              </tr>
            </thead>
            <tbody>
            
              <tr>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(125, 250, 140, 0.8);text-align: center;">0&#8451;</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;">0</td>
                <td style="min-width: 1%; font-size: 0.8vw; background-color: rgba(238, 241, 50, 0.8);text-align: center;">0</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(125, 250, 140, 0.8);">0&#8451;</td>
                <td colspan="2" style="text-align: center; font-size: 1.4vw; font-weight: bold;background-color: rgba(238, 241, 50, 0.8);">0</td>
              </tr>
            </tbody>
          </table>
        </center>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
@section('scripts')

<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
<script src="{{ url("js/highcharts.js")}}"></script>
<script src="{{ url("js/highcharts-3d.js")}}"></script>
<script src="{{ url("js/exporting.js")}}"></script>
<script src="{{ url("js/export-data.js")}}"></script>
<script src="{{ url("plugins/timepicker/bootstrap-timepicker.min.js")}}"></script>
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });


  jQuery(document).ready(function() {
    fetchData();
    setInterval(fetchData, 120000);
    $('.select2').select2({
      dropdownAutoWidth : true,
      allowClear:true,
    });
  });

  $('.dateexport').datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
    todayHighlight: true
  });

  $(function () {

    $('.select5').select2({
      dropdownAutoWidth : true,
      allowClear:true,
    });

    $('.timepicker').timepicker({
      showInputs: false,
      showMeridian: false,
      defaultTime: '0:00',
    });
      
  })


  var alarm_error = new Audio('{{ url("sounds/alarm_error.mp3") }}');

  function fetchData(){

        var data = {

        };

        $.get('https://cors-anywhere.herokuapp.com/http://10.109.52.4/mirai/public/fetch/temperature/room_temperature', data, function(result, status, xhr) {
          if(result.status){

            $.each(result.location, function(key, value) {
              var data_temp = [],
              data_hum = [],
              upper_limit_temp = 0,
              lower_limit_temp = 0,
              upper_limit_hum = 0,
              lower_limit_hum = 0,
              categories = [];
              $.each(result.logs, function(key2, value2) {
                if (value2.location == value.location) {
                  if (value2.remark == "temperature") {
                    data_temp.push(parseInt(value2.value));
                    upper_limit_temp = parseInt(value2.upper_limit);
                    lower_limit_temp = parseInt(value2.lower_limit);
                    categories.push(value2.created_at);
                  }else if(value2.remark == "humidity"){
                    data_hum.push(parseInt(value2.value));
                    upper_limit_hum = parseInt(value2.upper_limit);
                    lower_limit_hum = parseInt(value2.lower_limit);
                  }
                }
              });

              $.each(result.weather, function(key3, value3) {
                if (result.weather != null || result.weather != "") {
                  var tanggal_fix = value3.created_at.replace(/-/g,'/');
                  $("#tanggal_cuaca").text(getFormattedTime(new Date(tanggal_fix)));
                  $("#lokasi_cuaca").text(value3.location+" - "+value3.value);

                  if (value3.value == "Panas" || value3.value == "Cerah"){
                    $("#cerah").show();
                    $("#hujan").hide();
                    $("#berawan").hide();
                    $("#berangin").hide();
                    $("#dingin").hide();
                  }
                  else if (value3.value == "Hujan") {
                    $("#cerah").hide();
                    $("#hujan").show();
                    $("#berawan").hide();
                    $("#berangin").hide();
                    $("#dingin").hide();
                  }
                  else if (value3.value == "Berawan") {
                    $("#cerah").hide();
                    $("#hujan").hide();
                    $("#berawan").show();
                    $("#berangin").hide();
                    $("#dingin").hide();
                  }
                  else if (value3.value == "Berangin") {
                    $("#cerah").hide();
                    $("#hujan").hide();
                    $("#berawan").hide();
                    $("#berangin").show();
                    $("#dingin").hide();
                  }
                  else if (value3.value == "Dingin") {
                    $("#cerah").hide();
                    $("#hujan").hide();
                    $("#berawan").hide();
                    $("#berangin").hide();
                    $("#dingin").show();
                  }
                }
                else{
                  $("#tanggal_cuaca").text("-");
                  $("#lokasi_cuaca").text("YMPI");
                }
              });

              var loc = value.location.replace(" ","_");


              Highcharts.chart('chart_'+loc, {
              chart: {
                type: 'spline'
              },

              title: {
                text: value.location,
                zoomType: 'xy'
              },

              yAxis: {
                title: {
                  text: null
                },
                plotLines: [{
                  value: lower_limit_temp,
                  width: 1,
                  color: '#ff3030',
                  dashStyle: 'ShortDash',
                  label: {
                    text: 'Min Temperature',
                    style: {
                      color: '#ff3030',
                      fontWeight: 'bold'
                    }         
                  }
                },{
                  value: lower_limit_hum,
                  width: 1,
                  color: '#ff5722',
                  dashStyle: 'ShortDash',
                  label: {
                    text: 'Min Humidity',
                    style: {
                      color: '#ff5722',
                      fontWeight: 'bold'
                    }             
                  }
                },{
                  value: upper_limit_temp,
                  width: 1,
                  color: '#ff3030',
                  dashStyle: 'ShortDash',
                  label: {
                    text: 'Max Temperature',
                    style: {
                      color: '#ff3030',
                      fontWeight: 'bold'
                    }             
                  }
                },{
                  value: upper_limit_hum,
                  width: 1,
                  color: '#ff5722',
                  dashStyle: 'ShortDash',
                  label: {
                    text: 'Max Humidity',
                    style: {
                      color: '#ff5722',
                      fontWeight: 'bold'
                    }             
                  }
                }],
                gridLineWidth: 2,
                minorGridLineWidth: 2

                // plotBands: [{
                //  from: lower_limit_temp,
                //  to: upper_limit_temp,
                //  color: '#57ff5c'
                // }, {
                //  from: lower_limit_hum,
                //  to: upper_limit_hum,
                //  color: '#fcba03'
                // }, {
                //  from: 1000,
                //  to: 5000,
                //  color: '#ed4545'
                // }]
              },

              xAxis: {
                categories: categories,
                tickInterval: 30
              },

              legend: {
                enabled: false
              },

              credits:{
                enabled:false
              },

              plotOptions: {
                series: {
                  label: {
                    connectorAllowed: false
                  },
                  marker: {
                    enabled: false
                  },
                  animation: false,
                }
              },

              series: [
              {
                name: 'Data Temperature',
                data: data_temp,
                color: '#0091ea',
                lineWidth: 3
              },
              {
                name: 'Data Humidity',
                data: data_hum,
                color: '#ff9800',
                lineWidth: 3
              }
              ],

              responsive: {
                rules: [{
                  condition: {
                    maxWidth: 500
                  },
                }]
              },

              exporting: {
                enabled: false
              }

            });
            });


            $.each(result.lists, function(key, value) {
                var loc = value.location.replace(" ","_");

                if (value.upper_limit != null && value.lower_limit != null) {
                  if (value.value > value.upper_limit || value.value < value.lower_limit ) {
                    if(value.remark == "temperature"){
                      $('#value_temp_'+loc).addClass('sedang');
                      $('#value_temp_'+loc).text(value.value+" °C");
                    }
                    else if(value.remark == "humidity"){
                      $('#value_hum_'+loc).addClass('sedang');
                      $('#value_hum_'+loc).text(value.value+" %");
                    }
                  }
                  else{
                    if(value.remark == "temperature"){
                      $('#value_temp_'+loc).removeClass('sedang');
                      $('#value_temp_'+loc).text(value.value+" °C");
                    }
                    else if(value.remark == "humidity"){
                      $('#value_hum_'+loc).removeClass('sedang');
                      $('#value_hum_'+loc).text(value.value+" %");
                    }
                  }
                }
                else if(value.upper_limit == null && value.lower_limit == null){
                  if(value.remark == "temperature"){
                      $('#value_temp_'+loc).removeClass('sedang');
                      $('#value_temp_'+loc).text(value.value+" °C");
                    }
                    else if(value.remark == "humidity"){
                      $('#value_hum_'+loc).removeClass('sedang');
                      $('#value_hum_'+loc).text(value.value+" %");
                    }
                }
                else if(value.upper_limit != null ){
                  if (value.value > value.upper_limit) {
                    if(value.remark == "temperature"){
                      $('#value_temp_'+loc).addClass('sedang');
                      $('#value_temp_'+loc).text(value.value+" °C");

                    }
                    else if(value.remark == "humidity"){
                      $('#value_hum_'+loc).addClass('sedang');
                      $('#value_hum_'+loc).text(value.value+" %");
                    }
                  }
                  else{
                    if(value.remark == "temperature"){
                      $('#value_temp_'+loc).removeClass('sedang');
                      $('#value_temp_'+loc).text(value.value+" °C");
                    }
                    else if(value.remark == "humidity"){
                      $('#value_hum_'+loc).removeClass('sedang');
                      $('#value_hum_'+loc).text(value.value+" %");
                    }
                  }
                }

                else if(value.lower_limit != null ){
                  if (value.value < value.lower_limit) {
                    if(value.remark == "temperature"){
                      $('#value_temp_'+loc).addClass('sedang');
                      $('#value_temp_'+loc).text(value.value+" °C");

                    }
                    else if(value.remark == "humidity"){
                      $('#value_hum_'+loc).addClass('sedang');
                      $('#value_hum_'+loc).text(value.value+" %");
                    }
                  }
                  else{
                    if(value.remark == "temperature"){
                      $('#value_temp_'+loc).removeClass('sedang');
                      $('#value_temp_'+loc).text(value.value+" °C");
                    }
                    else if(value.remark == "humidity"){
                      $('#value_hum_'+loc).removeClass('sedang');
                      $('#value_hum_'+loc).text(value.value+" %");
                    }
                  }
                }
             });

            $.each(result.lists_wh, function(key, value) {
                var loc = value.location.replace(" ","_");

                if (value.upper_limit != null && value.lower_limit != null) {
                  if (value.value > value.upper_limit || value.value < value.lower_limit ) {
                    if(value.remark == "temperature"){
                      $('#value_temp_'+loc).addClass('sedang');
                      $('#value_temp_'+loc).text(value.value+" °C");
                    }
                    else if(value.remark == "humidity"){
                      $('#value_hum_'+loc).addClass('sedang');
                      $('#value_hum_'+loc).text(value.value+" %");
                    }
                  }
                  else{
                    if(value.remark == "temperature"){
                      $('#value_temp_'+loc).removeClass('sedang');
                      $('#value_temp_'+loc).text(value.value+" °C");
                    }
                    else if(value.remark == "humidity"){
                      $('#value_hum_'+loc).removeClass('sedang');
                      $('#value_hum_'+loc).text(value.value+" %");
                    }
                  }
                }
                else if(value.upper_limit == null && value.lower_limit == null){
                  if(value.remark == "temperature"){
                      $('#value_temp_'+loc).removeClass('sedang');
                      $('#value_temp_'+loc).text(value.value+" °C");
                    }
                    else if(value.remark == "humidity"){
                      $('#value_hum_'+loc).removeClass('sedang');
                      $('#value_hum_'+loc).text(value.value+" %");
                    }
                }
                else if(value.upper_limit != null ){
                  if (value.value > value.upper_limit) {
                    if(value.remark == "temperature"){
                      $('#value_temp_'+loc).addClass('sedang');
                      $('#value_temp_'+loc).text(value.value+" °C");

                    }
                    else if(value.remark == "humidity"){
                      $('#value_hum_'+loc).addClass('sedang');
                      $('#value_hum_'+loc).text(value.value+" %");
                    }
                  }
                  else{
                    if(value.remark == "temperature"){
                      $('#value_temp_'+loc).removeClass('sedang');
                      $('#value_temp_'+loc).text(value.value+" °C");
                    }
                    else if(value.remark == "humidity"){
                      $('#value_hum_'+loc).removeClass('sedang');
                      $('#value_hum_'+loc).text(value.value+" %");
                    }
                  }
                }

                else if(value.lower_limit != null ){
                  if (value.value < value.lower_limit) {
                    if(value.remark == "temperature"){
                      $('#value_temp_'+loc).addClass('sedang');
                      $('#value_temp_'+loc).text(value.value+" °C");

                    }
                    else if(value.remark == "humidity"){
                      $('#value_hum_'+loc).addClass('sedang');
                      $('#value_hum_'+loc).text(value.value+" %");
                    }
                  }
                  else{
                    if(value.remark == "temperature"){
                      $('#value_temp_'+loc).removeClass('sedang');
                      $('#value_temp_'+loc).text(value.value+" °C");
                    }
                    else if(value.remark == "humidity"){
                      $('#value_hum_'+loc).removeClass('sedang');
                      $('#value_hum_'+loc).text(value.value+" %");
                    }
                  }
                }
             });
            

          //   $.each(result.lists, function(key, value) {
          //     if(value.remark == "temperature"){

          //       $.each($(".temperature"), function(key2, value2) {
          //         var loc = value.location.replace(" ","_")
          //         if (value2.id == "temp_"+loc) {
          //           if (value.upper_limit == null && value.lower_limit == null) {
          //               $("#"+value2.id).parent().addClass('bg-green-active');
          //           }

          //           else if (value.upper_limit != null && value.lower_limit != null) {
          //               if (value.value > value.upper_limit || value.value < value.lower_limit ) {
          //                 $("#"+value2.id).parent().addClass('bg-red-active');
          //               }
          //               else{
          //                 $("#"+value2.id).parent().addClass('bg-green-active');
          //               }
          //           }

          //           else if (value.upper_limit != null ) {
          //             if (value.value > value.upper_limit ) {
          //               $("#"+value2.id).parent().addClass('bg-red-active');
          //             }
          //             else{
          //               $("#"+value2.id).parent().addClass('bg-green-active');
          //             }
          //           }

          //           else if (value.lower_limit != null){
          //             if (value.value > value.lower_limit ) {
          //               $("#"+value2.id).parent().addClass('bg-green-active');
          //             }
          //             else{
          //               $("#"+value2.id).parent().addClass('bg-red-active');
          //             }
          //           }

          //           $('#'+value2.id).html(value.value);
          //           if(value.remark == "temperature"){
                    //  $('#min_temp_'+loc).text(value.lower_limit+" °C");
                    //  $('#max_temp_'+loc).text(value.upper_limit+" °C");
                    // } else if(value.remark == "humidity"){
                    //  $('#min_hum_'+loc).text(value.lower_limit+" %");
                    //  $('#max_hum_'+loc).text(value.upper_limit+" %");
                    // }
          //         }
          //       })        
          //     }

          //     if(value.remark == "humidity"){
          //       $.each($(".humidity"), function(key3, value3) {

          //         var loc = value.location.replace(" ","_");
          //         if (value3.id == "hum_"+loc) {

          //           if (value.upper_limit == null && value.lower_limit == null) {
          //               $("#"+value3.id).parent().addClass('bg-green-active');
          //           }

          //           else if (value.upper_limit != null && value.lower_limit != null) {
          //               if (value.value > value.upper_limit || value.value < value.lower_limit ) {
          //                 $("#"+value3.id).parent().addClass('bg-red-active');
          //               }
          //               else{
          //                 $("#"+value3.id).parent().addClass('bg-green-active');
          //               }
          //           }

          //           else if (value.upper_limit != null ) {
          //             if (value.value > value.upper_limit ) {
          //               $("#"+value3.id).parent().addClass('bg-red-active');
          //             }
          //             else{
          //               $("#"+value3.id).parent().addClass('bg-green-active');
          //             }
          //           }

          //           else if (value.lower_limit != null){
          //             if (value.value > value.lower_limit ) {
          //               $("#"+value3.id).parent().addClass('bg-green-active');
          //             }
          //             else{
          //               $("#"+value3.id).parent().addClass('bg-red-active');
          //             }
          //           }

          //           $('#'+value3.id).html(value.value);
          //         }
          //       }) 
          //     }
          //   });
          }
          else{
            alert('Attempt to retrieve data failed');
          }
        });
      } 

    function AddCuaca(){
    clearAll();
    $('#modalCuaca').modal('show');
  }

  function modalSuhu(location){

    var loca = location.replace("_"," ");
  	$('#suhu_lokasi').text(loca);

    var data = {
    };

    $.get('{{ url("fetch/temperature/room_temperature") }}', data, function(result, status, xhr) {
      if(result.status){
      			$.each(result.lists, function(key, value) {
                var loc = value.location.replace(" ","_");
                $('#table_'+loc).hide();
                if (loc == location){
                	$('#table_'+loc).show();
                	if (value.upper_limit != null && value.lower_limit != null) {
	                  if(value.remark == "temperature"){
	                    $('#min_temp_'+loc).html("Min <br>"+value.lower_limit+" °C");
	                    $('#max_temp_'+loc).html("Max <br>"+value.upper_limit+" °C");
	                  } else if(value.remark == "humidity"){
	                    $('#min_hum_'+loc).html("Min <br>"+value.lower_limit+" %");
	                    $('#max_hum_'+loc).html("Max <br>"+value.upper_limit+" %");
	                  }
	                }
	                else if (value.upper_limit != null) {
	                  if(value.remark == "temperature"){
	                    $('#min_temp_'+loc).html("-");
	                    $('#max_temp_'+loc).html("Max <br>"+value.upper_limit+" °C");
	                  } else if(value.remark == "humidity"){
	                    $('#min_hum_'+loc).html("-");
	                    $('#max_hum_'+loc).html("Max <br>"+value.upper_limit+" %");
	                  }
	                }
	                else if (value.lower_limit != null) {
	                  if(value.remark == "temperature"){
	                    $('#min_temp_'+loc).html("Min <br>"+value.lower_limit+" °C");
	                    $('#max_temp_'+loc).html("-");
	                  } else if(value.remark == "humidity"){
	                    $('#min_hum_'+loc).html("Min <br>"+value.lower_limit+" °C");
	                    $('#max_hum_'+loc).html("-");
	                  }
	                }
	                else{
	                  if(value.remark == "temperature"){
	                    $('#min_temp_'+loc).html("-");
	                    $('#max_temp_'+loc).html("-");
	                  } else if(value.remark == "humidity"){
	                    $('#min_hum_'+loc).html("-");
	                    $('#max_hum_'+loc).html("-");
	                  }
	                }




	                if (value.upper_limit != null && value.lower_limit != null) {
	                  if (value.value > value.upper_limit || value.value < value.lower_limit ) {
	                    if(value.remark == "temperature"){
	                      $('#title_temp_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#min_temp_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#max_temp_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#temp_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#title_temp_'+loc).addClass('sedang');
	                      $('#min_temp_'+loc).addClass('sedang');
	                      $('#max_temp_'+loc).addClass('sedang');
	                      $('#temp_'+loc).addClass('sedang');
	                      $('#temp_'+loc).text(value.value+" °C");
	                      // alarm_error.play();

	                    }
	                    else if(value.remark == "humidity"){
	                      
	                      $('#title_hum_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#min_hum_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#max_hum_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#hum_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#title_hum_'+loc).addClass('sedang');
	                      $('#min_hum_'+loc).addClass('sedang');
	                      $('#max_hum_'+loc).addClass('sedang');
	                      $('#hum_'+loc).addClass('sedang');
	                      $('#hum_'+loc).text(value.value+" %");
	                      // alarm_error.play();
	                    }
	                  }
	                  else{
	                    if(value.remark == "temperature"){
	                      $('#title_temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');
	                      $('#min_temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');
	                      $('#max_temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');
	                      $('#temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');
	                      $('#title_temp_'+loc).removeClass('sedang');
	                      $('#min_temp_'+loc).removeClass('sedang');
	                      $('#max_temp_'+loc).removeClass('sedang');
	                      $('#temp_'+loc).removeClass('sedang');
	                      $('#temp_'+loc).text(value.value+" °C");
	                    }
	                    else if(value.remark == "humidity"){

	                      $('#title_hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');
	                      $('#min_hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');
	                      $('#max_hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');
	                      $('#hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');
	                      $('#title_hum_'+loc).removeClass('sedang');
	                      $('#min_hum_'+loc).removeClass('sedang');
	                      $('#max_hum_'+loc).removeClass('sedang');
	                      $('#hum_'+loc).removeClass('sedang');
	                      $('#hum_'+loc).text(value.value+" %");
	                    }
	                  }
	                }
	                else if(value.upper_limit == null && value.lower_limit == null){
	                  if(value.remark == "temperature"){
	                      $('#title_temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');
	                      $('#min_temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');
	                      $('#max_temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');
	                      $('#temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');
	                      $('#title_temp_'+loc).removeClass('sedang');
	                      $('#min_temp_'+loc).removeClass('sedang');
	                      $('#max_temp_'+loc).removeClass('sedang');
	                      $('#temp_'+loc).removeClass('sedang');
	                      $('#temp_'+loc).text(value.value+" °C");
	                    }
	                    else if(value.remark == "humidity"){
	                      $('#title_hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');
	                      $('#min_hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');
	                      $('#max_hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');
	                      $('#hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');

	                      $('#title_hum_'+loc).removeClass('sedang');
	                      $('#min_hum_'+loc).removeClass('sedang');
	                      $('#max_hum_'+loc).removeClass('sedang');
	                      $('#hum_'+loc).removeClass('sedang');
	                      $('#hum_'+loc).text(value.value+" %");
	                    }
	                }
	                else if(value.upper_limit != null ){
	                  if (value.value > value.upper_limit) {
	                    if(value.remark == "temperature"){
	                      $('#title_temp_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#min_temp_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#max_temp_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#temp_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#title_temp_'+loc).addClass('sedang');
	                      $('#min_temp_'+loc).addClass('sedang');
	                      $('#max_temp_'+loc).addClass('sedang');
	                      $('#temp_'+loc).addClass('sedang');
	                      $('#temp_'+loc).text(value.value+" °C");
	                      // alarm_error.play();

	                    }
	                    else if(value.remark == "humidity"){
	                      
	                      $('#title_hum_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#min_hum_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#max_hum_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#hum_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#title_hum_'+loc).addClass('sedang');
	                      $('#min_hum_'+loc).addClass('sedang');
	                      $('#max_hum_'+loc).addClass('sedang');
	                      $('#hum_'+loc).addClass('sedang');
	                      $('#hum_'+loc).text(value.value+" %");
	                      // alarm_error.play();
	                    }
	                  }
	                  else{
	                    if(value.remark == "temperature"){
	                      $('#title_temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');
	                      $('#min_temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');
	                      $('#max_temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');
	                      $('#temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');

	                      $('#title_temp_'+loc).removeClass('sedang');
	                      $('#min_temp_'+loc).removeClass('sedang');
	                      $('#max_temp_'+loc).removeClass('sedang');
	                      $('#temp_'+loc).removeClass('sedang');
	                      $('#temp_'+loc).text(value.value+" °C");
	                    }
	                    else if(value.remark == "humidity"){
	                      $('#title_hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');
	                      $('#min_hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');
	                      $('#max_hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');
	                      $('#hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');

	                      $('#title_hum_'+loc).removeClass('sedang');
	                      $('#min_hum_'+loc).removeClass('sedang');
	                      $('#max_hum_'+loc).removeClass('sedang');
	                      $('#hum_'+loc).removeClass('sedang');
	                      $('#hum_'+loc).text(value.value+" %");
	                    }
	                  }
	                }

	                else if(value.lower_limit != null ){
	                  if (value.value < value.lower_limit) {
	                    if(value.remark == "temperature"){
	                      $('#title_temp_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#min_temp_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#max_temp_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#temp_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#title_temp_'+loc).addClass('sedang');
	                      $('#min_temp_'+loc).addClass('sedang');
	                      $('#max_temp_'+loc).addClass('sedang');
	                      $('#temp_'+loc).addClass('sedang');
	                      $('#temp_'+loc).text(value.value+" °C");
	                      // alarm_error.play();

	                    }
	                    else if(value.remark == "humidity"){
	                      
	                      $('#title_hum_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#min_hum_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#max_hum_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#hum_'+loc).css('background-color','rgba(229, 115, 115, 0.8)');
	                      $('#title_hum_'+loc).addClass('sedang');
	                      $('#min_hum_'+loc).addClass('sedang');
	                      $('#max_hum_'+loc).addClass('sedang');
	                      $('#hum_'+loc).addClass('sedang');
	                      $('#hum_'+loc).text(value.value+" %");
	                      // alarm_error.play();
	                    }
	                  }
	                  else{
	                    if(value.remark == "temperature"){
	                      $('#title_temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');
	                      $('#min_temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');
	                      $('#max_temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');
	                      $('#temp_'+loc).css('background-color','rgba(125, 250, 140, 0.8)');

	                      $('#title_temp_'+loc).removeClass('sedang');
	                      $('#min_temp_'+loc).removeClass('sedang');
	                      $('#max_temp_'+loc).removeClass('sedang');
	                      $('#temp_'+loc).removeClass('sedang');
	                      $('#temp_'+loc).text(value.value+" °C");
	                    }
	                    else if(value.remark == "humidity"){
	                      $('#title_hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');
	                      $('#min_hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');
	                      $('#max_hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');
	                      $('#hum_'+loc).css('background-color','rgb(238, 241, 50, 0.8)');

	                      $('#title_hum_'+loc).removeClass('sedang');
	                      $('#min_hum_'+loc).removeClass('sedang');
	                      $('#max_hum_'+loc).removeClass('sedang');
	                      $('#hum_'+loc).removeClass('sedang');
	                      $('#hum_'+loc).text(value.value+" %");
	                    }
	                  }
	                }
                }
                
                
            }); 

			}
		});

  	$('#modalSuhu').modal('show');
  }

  function clearAll(){
    // $('#inputor').val('');
    $('#input_date').val('');
    // $('#input_time').val('');
    $('#input_cuaca').val('');
  }

  function save(){
    $('#loading').show();
    var inputor = $('#inputor').val();
    var tanggal = $('#input_date').val();
    var waktu = $('#input_time').val();
    var cuaca = $('#input_cuaca').val();

    var data = {
      inputor:inputor,
      tanggal:tanggal,
      waktu:waktu,
      cuaca:cuaca
    }
    if(inputor != '' && tanggal != '' && waktu != "" && cuaca != ""){
      $.post('{{ url("create/cuaca") }}', data, function(result, status, xhr){
        if(result.status){
          $('#modalCuaca').modal('hide');
          openSuccessGritter('Success!', result.message);
          // fetchDriver();
          // fetchRequest();
          fetchData()
          clearAll();
          $('#loading').hide();
        }
        else{
          openErrorGritter('Error!', result.message);
          $('#loading').hide();
        }
      });
    }
    else{
      openErrorGritter('Error!', 'Data harus lengkap tidak boleh ada yang kosong');
      $('#loading').hide();   
    }

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

    function getFormattedTime(date) {
        var year = date.getFullYear();

        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
          "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];

        var month = date.getMonth();

        var day = date.getDate().toString();
        day = day.length > 1 ? day : '0' + day;

        var hour = date.getHours();
        if (hour < 10) {
            hour = "0" + hour;
        }

        var minute = date.getMinutes();
        if (minute < 10) {
            minute = "0" + minute;
        }
        var second = date.getSeconds();
        
        return day + '-' + monthNames[month] + '-' + year +' '+ hour +':'+ minute;
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