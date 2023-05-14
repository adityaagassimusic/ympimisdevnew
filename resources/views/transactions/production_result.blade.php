@extends('layouts.master')
@section('stylesheets')
    <link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
    <link href="{{ url('css/jquery.numpad.css') }}" rel="stylesheet">
    <style type="text/css">
        .table>tbody>tr:hover {
            background-color: #7dfa8c !important;
        }

        table.table-bordered {
            border: 1px solid black;
            vertical-align: middle;
        }

        table.table-bordered>thead>tr>th {
            border: 1px solid black;
            vertical-align: middle;
        }

        table.table-bordered>tbody>tr>td {
            border: 1px solid black;
            vertical-align: middle;
            padding: 2px 5px 2px 5px;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .nmpd-grid {
            border: none;
            padding: 20px;
        }

        .nmpd-grid>tbody>tr>td {
            border: none;
        }

        #loading {
            display: none;
        }
    </style>
@endsection

@section('header')
    <section class="content-header">
        <h1>
            {{ $title }}
            <small><span class="text-purple">{{ $title_jp }}</span></small>
        </h1>
    </section>
@endsection

@section('content')
    <section class="content" style="font-size: 0.9vw;">
        <div id="loading"
            style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display: none">
            <p style="position: absolute; color: White; top: 45%; left: 45%;">
                <span style="font-size: 5vw;"><i class="fa fa-spin fa-circle-o-notch"></i></span>
            </p>
        </div>
        <input type="hidden" id="materials" value="{{ $materials }}">
        <div class="row">
            <div class="col-xs-6" style="padding-right: 7.5px;">
                <div class="box box-solid" style="border: 1px solid grey;">
                    <div class="box-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label style="padding-top: 0;" for="" class="col-sm-3 control-label">Result
                                    Date<span class="text-red"></span> :</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control datepicker" id="createDate"
                                        placeholder="   Select Date">
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="padding-top: 0;" for="" class="col-sm-3 control-label">Material
                                    Number<span class="text-red"></span> :</label>
                                <div class="col-sm-9">
                                    <select class="form-control select2" id="createMaterialNumber"
                                        data-placeholder="Select Material" style="width: 100%;">
                                        <option value=""></option>
                                        @foreach ($materials as $material)
                                            <option
                                                value="{{ $material->item_code }}||{{ $material->item_name }}||{{ $material->unit_code }}||{{ $material->mrp_ctrl }}||{{ $material->issue_loc_code }}">
                                                {{ $material->item_code }} - {{ $material->item_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="padding-top: 0;" for="" class="col-sm-3 control-label">Quantity<span
                                        class="text-red"></span> :</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control numpad" placeholder="Enter Quantity"
                                        id="createQuantity">
                                </div>
                            </div>
                        </form>
                        <button class="btn btn-primary pull-right" style="width: 30%;"
                            onclick="addResult('each')">Add</button>
                    </div>
                </div>
            </div>
            <div class="col-xs-6" style="padding-left: 7.5px;">
                <div class="box box-solid" style="border: 1px solid grey;">
                    <div class="box-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label style="padding-top: 0;" for="" class="col-sm-3 control-label">Result
                                    Date<span class="text-red"></span> :</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control datepicker" id="bulkDate"
                                        placeholder="   Select Date">
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="padding-top: 0;" for="" class="col-sm-3 control-label">Bulk Input<span
                                        class="text-red"></span> :</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" rows="5" placeholder="Enter Data (GMC+QTY)" id="bulkProductionResult"></textarea>
                                </div>
                            </div>
                        </form>
                        <button class="btn btn-primary pull-right" style="width: 30%;"
                            onclick="addResult('bulk')">Add</button>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="box box-solid" style="border: 1px solid grey;">
                    <div class="box-body">
                        <table id="tableResult" class="table table-bordered table-striped table-hover">
                            <thead style="background-color: #90ed7d;">
                                <tr>
                                    {{-- <th style="width: 0.1%; text-align: center;">#</th> --}}
                                    <th style="width: 0.5%; text-align: right;">Date</th>
                                    <th style="width: 1%; text-align: center;">Material</th>
                                    <th style="width: 5%; text-align: left;">Description</th>
                                    <th style="width: 0.1%; text-align: left;">Uom</th>
                                    <th style="width: 0.5%; text-align: left;">SLoc</th>
                                    <th style="width: 0.5%; text-align: left;">MStation</th>
                                    <th style="width: 1%; text-align: right;">Quantity</th>
                                </tr>
                            </thead>
                            <tbody id="tableResultBody">
                            </tbody>
                        </table>
                        <br>
                        <center>
                            <span style="font-weight: bold; font-size: 1.5vw;" id="count_material">Count Material:
                                0</span>
                        </center>
                        <button class="btn btn-success" onclick="confirmResult()"
                            style="width: 100%; font-weight: bold; margin-top: 10px;">CONFIRM</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ url('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('js/buttons.flash.min.js') }}"></script>
    <script src="{{ url('js/jszip.min.js') }}"></script>
    <script src="{{ url('js/vfs_fonts.js') }}"></script>
    <script src="{{ url('js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('js/buttons.print.min.js') }}"></script>
    <script src="{{ url('js/jquery.gritter.min.js') }}"></script>
    <script src="{{ url('js/jquery.numpad.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.fn.numpad.defaults.gridTpl = '<table class="table modal-content" style="width: 40%; z-index: 9999;"></table>';
        $.fn.numpad.defaults.backgroundTpl = '<div class="modal-backdrop in"></div>';
        $.fn.numpad.defaults.displayTpl = '<input type="text" class="form-control" style="font-size:2vw; height: 50px;"/>';
        $.fn.numpad.defaults.buttonNumberTpl =
            '<button type="button" class="btn btn-default" style="font-size:2vw; width:100%;"></button>';
        $.fn.numpad.defaults.buttonFunctionTpl =
            '<button type="button" class="btn" style="font-size:2vw; width: 100%;"></button>';
        $.fn.numpad.defaults.onKeypadCreate = function() {
            $(this).find('.done').addClass('btn-primary');
            $(this).find('.neg').addClass('btn-default');
            $('.neg').css('display', 'block');
        };

        jQuery(document).ready(function() {
            // $('body').toggleClass("sidebar-collapse");
            $('.numpad').numpad({
                hidePlusMinusButton: true,
                decimalSeparator: '.'
            });
            $('#createDate').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true
            });
            $('#bulkDate').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true
            });
            $('#createMaterialNumber').select2({

            });
        });

        var audio_error = new Audio('{{ url('sounds/error.mp3') }}');
        var audio_ok = new Audio('{{ url('sounds/sukses.mp3') }}');

        var materials = $.parseJSON($('#materials').val());
        var results = [];
        var result_count = 0;

        function confirmResult() {
            if (confirm("Are you sure want proceed this data?")) {
                $('#loading').show();

                var production_results = [];

                $.each(results, function(key, value) {
                    var date = $('#result_' + value).find('td').eq(0).text();
                    var material_number = $('#result_' + value).find('td').eq(1).text();
                    var material_description = $('#result_' + value).find('td').eq(2).text();
                    var location = $('#result_' + value).find('td').eq(4).text();
                    var mstation = $('#result_' + value).find('td').eq(5).text();
                    var quantity = $('#result_' + value).find('td').eq(6).text();

                    production_results.push({
                        date: date,
                        material_number: material_number,
                        material_description: material_description,
                        location: location,
                        mstation: mstation,
                        quantity: quantity
                    });
                });

                var data = {
                    production_results: production_results
                }
                $.post('{{ url('input/ymes/production_result') }}', data, function(result, status, xhr) {
                    if (result.status) {
                        results = [];
                        result_count = 0;
                        $('#tableResultBody').html("");
                        $('#count_material').text('Count Material: 0');

                        audio_ok.play();
                        openSuccessGritter('Success!', result.message);
                        $('#loading').hide();
                    } else {
                        audio_error.play();
                        openErrorGritter('Error!', result.message);
                        $('#loading').hide();
                        return false;
                    }
                });
            } else {
                return false;
            }
        }

        function addResult(cat) {
            $('#loading').show();
            if (cat == 'bulk') {
                var date = $('#bulkDate').val();
                var rows = $('#bulkProductionResult').val().split('\n');

                if (date == "" || $('#bulkProductionResult').val() == "") {
                    audio_error.play();
                    openErrorGritter('Error!', 'All required field must be filled.');
                    $('#loading').hide();
                    return false;
                }

                var status = true;

                for (var i = 0; i < rows.length; i++) {
                    var col = rows[i].split('+');
                    var material = col[0];
                    var quantity = col[1];
                    var tableResultBody = "";

                    if (material.length != 7 || quantity ==
                        0) {
                        openErrorGritter('Error!', 'Data tidak sesuai');
                        $('#loading').hide();
                        return false;
                    }

                    var found = false;
                    $.each(materials, function(key, value) {
                        if (value.item_code == material) {
                            var data = {
                                date: date,
                                material_number: material,
                                category: 'production_result',
                                quantity: quantity
                            }
                            $.get('{{ url('fetch/ymes/inventory_check') }}', data, function(result, status,
                                xhr) {
                                if (result.status) {

                                } else {
                                    results = [];
                                    result_count = 0;
                                    $('#tableResultBody').html("");
                                    audio_error.play();
                                    openErrorGritter('Error!',
                                        'Data tidak sesuai atau stock tidak mencukupi cek YMES 00-45'
                                    );
                                    $('#count_material').text('Count Material: ' + results.length);
                                    $('#loading').hide();
                                    return false;
                                }
                            });

                            result_count += 1;
                            tableResultBody += '<tr id="result_' + result_count + '">';
                            tableResultBody += '<td style="text-align: right;">' + date + '</td>';
                            tableResultBody += '<td style="text-align: center;">' + value.item_code + '</td>';
                            tableResultBody += '<td style="text-align: left;">' + value.item_name + '</td>';
                            tableResultBody += '<td style="text-align: left;">' + value.unit_code + '</td>';
                            tableResultBody += '<td style="text-align: left;">' + value.issue_loc_code +
                                '</td>';
                            tableResultBody += '<td style="text-align: left;">W' + value.mrp_ctrl + 'S10</td>';
                            tableResultBody += '<td style="text-align: right;">' + quantity + '</td>';
                            tableResultBody += '</tr>';
                            results.push(result_count);
                            found = true;
                            return false;
                        }
                    });
                    if (found == false) {
                        status = false;
                    }
                    if (quantity == 0) {
                        status = false;
                    }

                    $('#tableResultBody').append(tableResultBody);
                    $('#count_material').text('Count Material: ' + results.length);

                }

                if (status == false) {
                    results = [];
                    result_count = 0;
                    $('#tableResultBody').html("");
                    audio_error.play();
                    openErrorGritter('Error!', 'Error occured please check your data.');
                    $('#loading').hide();
                    return false;
                }

                audio_ok.play();
                openSuccessGritter('Success!', 'Material successfully added.');
                $('#loading').hide();

                // $('#createDate').val("");
                $('#createMaterialNumber').prop('selectedIndex', 0).change();
                $('#createQuantity').val("");
                $('#bulkDate').val("");
                $('#bulkProductionResult').val("");
                $('#loading').hide();
            }
            if (cat == 'each') {
                var date = $('#createDate').val();
                var material = $('#createMaterialNumber').val();
                var quantity = $('#createQuantity').val();

                if (date == "" || material == "" || quantity == "") {
                    audio_error.play();
                    openErrorGritter('Error!', 'All required field must be filled.');
                    $('#loading').hide();
                    return false;
                }

                if (quantity == 0) {
                    audio_error.play();
                    openErrorGritter('Error!', 'Quantity must not 0.');
                    $('#loading').hide();
                    return false;
                }

                var data = {
                    date: date,
                    material_number: material.split('||')[0],
                    category: 'production_result',
                    quantity: quantity
                }

                $.get('{{ url('fetch/ymes/inventory_check') }}', data, function(result, status, xhr) {
                    if (result.status) {
                        var status = true;
                        $.each(result.inventory, function(key, value) {
                            if (value.diff < 0) {
                                status = false;
                                return false;
                            }
                        });
                        if (quantity > 0) {
                            if (status == false) {
                                audio_error.play();
                                openErrorGritter('Error!', 'Stock backflush tidak mencukupi cek YMES 00-45.');
                                $('#loading').hide();
                                return false;
                            }
                        }

                        var tableResultBody = "";
                        result_count += 1;

                        tableResultBody += '<tr id="result_' + result_count + '">';
                        tableResultBody += '<td style="text-align: right;">' + date + '</td>';
                        tableResultBody += '<td style="text-align: center;">' + material.split('||')[0] + '</td>';
                        tableResultBody += '<td style="text-align: left;">' + material.split('||')[1] + '</td>';
                        tableResultBody += '<td style="text-align: left;">' + material.split('||')[2] + '</td>';
                        tableResultBody += '<td style="text-align: left;">' + material.split('||')[4] + '</td>';
                        tableResultBody += '<td style="text-align: left;">W' + material.split('||')[3] + 'S10</td>';
                        tableResultBody += '<td style="text-align: right;">' + quantity + '</td>';
                        tableResultBody += '</tr>';

                        results.push(result_count);
                        $('#tableResultBody').append(tableResultBody);
                        $('#count_material').text('Count Material: ' + results.length);

                        $('#createMaterialNumber').prop('selectedIndex', 0).change();
                        $('#createQuantity').val("");
                        $('#bulkProductionResult').val("");
                        $('#loading').hide();

                    } else {
                        audio_error.play();
                        openErrorGritter('Error!', result.message);
                        $('#loading').hide();
                        return false;
                    }
                });
            }
        }

        function openSuccessGritter(title, message) {
            jQuery.gritter.add({
                title: title,
                text: message,
                class_name: 'growl-success',
                image: '{{ url('images/image-screen.png') }}',
                sticky: false,
                time: '3000'
            });
        }

        function openErrorGritter(title, message) {
            jQuery.gritter.add({
                title: title,
                text: message,
                class_name: 'growl-danger',
                image: '{{ url('images/image-stop.png') }}',
                sticky: false,
                time: '3000'
            });
        }
    </script>
@endsection
