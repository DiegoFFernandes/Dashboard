@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#new-comprovante" data-toggle="tab" aria-expanded="true">Registrar Comprovante</a>
                        </li>
                        <li class="">
                            <a href="#comprovante-existing" data-toggle="tab" aria-expanded="false">Comprovantes</a>
                        </li>
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="tab-pane active" id="new-comprovante">
                            {{-- <form action="{{ route('manutencao.store') }}" id="form-ticket" method="post"
                                enctype="multipart/form-data"> --}}
                            <div class="box-body">
                                @csrf
                                <div class="col-md-12" style="background-color: #ecf0f5; padding-top:15px">
                                    <input class="form-control cd_comprovante hidden" type="number">

                                    <div class="col-md-3">
                                        <label for="">Valor Adiantado:</label>
                                        <div class="form-group text-justify" style="background-color: #79ffa75c; opacity:1">
                                            <input class="form-control vl_adiantado text-right" type="text" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Valor Gasto Adiantamento:</label>
                                        <div class="form-group text-justify">
                                            <input class="form-control vl_utilizado text-right" type="text" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Valor Devolver:</label>
                                        <div class="form-group text-justify">
                                            <input class="form-control vl_devolver text-right" type="text" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="">Tipo Despesas:</label>
                                        <div class="form-group">
                                            <select class="form-control" name="tp_despesa" id="tp_despesa"
                                                style="width: 100%;" required>
                                                <option value="0" selected="selected">Selecione</option>
                                                <option value="C">Combústivel</option>
                                                <option value="A">Alimentação</option>
                                                <option value="H">Hospedagem</option>
                                                <option value="O">Outro</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Valor Consumido:</label>
                                        <div class="form-group">
                                            <input class="form-control vl_consumido valor" type="number">
                                        </div>
                                    </div>
                                    <div class="col-md-4 ">
                                        <div class="form-group">
                                            <label for="image">Capturar Comprovante:</label>
                                            {{-- <input type="file" class="form-control" id="image"> --}}
                                            <button class="btn btn-primary btn-sm form-control" data-toggle="modal"
                                                data-target="#capture-image" id="open-camera">Abrir Câmera</button>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="padding-bottom: 15px">
                                        <label for="#">Obervação:</label>
                                        <div class="form-group">
                                            <textarea class="form-control" name="#" id="observacao" cols="30" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button class="btn btn-success me-2" id="btn-send">Enviar Despesa</button>
                                <button class="btn btn-warning hidden" id="btn-update">Editar Despesa</button>

                            </div>
                            {{-- </form> --}}
                        </div>
                        <div class="tab-pane" id="comprovante-existing">
                            {{-- <input id="token" name="_token" type="hidden" value="{{ csrf_token() }}"> --}}
                            <div class="box-body">
                                <div class="col-md-12" style="background-color: #ecf0f5">
                                    <div style="padding-bottom: 15px">
                                        <div class="box-header with-border" style="border-bottom: 1px solid #d1d1d1;">
                                            <h3 class="box-title" style="text-align: center;">Lista de Despesas</h3>
                                        </div>
                                    </div>
                                    <table class="table compact" id="table-comprovante" style="font-size: 12px">
                                        <thead>
                                            <th>Cód</th>
                                            <th>Pessoa</th>
                                            <th>Vl Consumido</th>
                                            <th>Tipo despesa</th>
                                            <th>Observação</th>
                                            <th>Visualizado</th>
                                            <th>#</th>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th style="text-align:right">Total:</th>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Capturar imagem --}}
        <div class="modal fade" id="capture-image" data-backdrop="static" data-keyboard="false" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="camera-wrapper" style="text-align:center;">
                            <div class="col-sm-12" id="my_camera"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal"
                            id="close-camera">Fechar Camera</button>
                        <button type="button" class="btn btn-primary" id="take_picture">Capturar Foto</button>
                        <ul id="menu" class="list-unstyled">
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal View Pìctures Tickets --}}
        <div class="modal modal-pictures fade" id="modal-pictures">
            <div class="modal-dialog" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title"><i class="fa fa-picture-o"></i> Imagens
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="post">
                            <div class="row margin-bottom">
                                <div class="col-sm-12">
                                    <div class="row" id="response-pictures">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js?v1') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var pictures = [],
                height, width, imagem, id, update_image = 0,
                tp_despesa, vl_consumido, cd_comprovante;

            saldo();
            if (screen.height <= screen.width) {
                // Landscape                
                width = 320;
                height = 240;
            } else {
                // Portrait
                width = 420;
                height = 620;
            }

            $('#btn-update').click(function() {
                StoreOrUpdate("{{ route('update-adiantamento-despesas.vl_consumido') }}", "U");
            });

            $("#open-camera").click(function() {
                $('#take_picture').show();
                $('#my_camera').show();
                $('#close-camera').text('Fechar Câmera');
                var cameraFacingMode = (cameraFacingMode == 'user') ? 'environment' : 'user';
                Webcam.set({
                    width: 640,
                    height: 480,
                    dest_width: 640,
                    dest_height: 480,
                    image_format: 'jpeg',
                    jpeg_quality: 100,
                    constraints: {
                        // aspectRatio: 1.777777778,
                        zoom: {
                            ideal: 2.0
                        },
                        facingMode: 'environment',
                        focusMode: 'continuous'
                    }
                });
                Webcam.attach('#my_camera');
            });
            $("#close-camera").click(function() {
                Webcam.reset();
            });
            $("#take_picture").click(function() {
                // take snapshot and get image data
                Webcam.snap(function(data_uri) {
                    pictures.push(data_uri);
                });
                listPictures(pictures);

            });
            $('#menu').on('click', '#image', function(e) {
                let removeArrayValue = $(this).data('id');
                pictures.splice($.inArray(removeArrayValue, pictures), 1);
                console.log(pictures);
                listPictures(pictures);
            });

            function listPictures(pictures) {
                $('#menu').empty();
                $.each(pictures, function(indexInArray, valueOfElement) {
                    $('#menu').append(
                        '<li>' +
                        '<img id="results" style="width: ' + 240 + 'px; height: ' + 320 + 'px;" src="' +
                        valueOfElement + '"/>' +
                        '<button id="image" type="button" class="btn btn-danger" data-id="' +
                        valueOfElement + '" >X</button>' +
                        '</li>');
                });
            }

            function initTable(status) {
                var table = $('#table-comprovante');

                table.DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    "pageLength": 50,
                    responsive: true,
                    pagingType: "simple",
                    processing: false,
                    dom: 'Blfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    ajax: {
                        url: "{{ route('adiantamento-despesas.list') }}",

                    },
                    columns: [{
                            data: 'cd_adiantamento',
                            name: 'cd_adiantamento',
                        },
                        {
                            data: 'name',
                            name: 'name',
                        },
                        {
                            data: 'vl_consumido',
                            name: 'vl_consumido',
                        },
                        {
                            data: 'tp_despesa',
                            name: 'tp_despesa',
                        },
                        {
                            data: 'ds_observacao',
                            name: 'ds_observacao',
                        }, {
                            data: 'st_visto',
                            name: 'st_visto'
                        }, {
                            data: 'actions',
                            name: 'actions'
                        }
                    ],
                    order: [
                        [0, "desc"]
                    ],
                    columnDefs: [{
                        targets: 2,
                        render: $.fn.dataTable.render.number('.', ',', 2),

                    }],
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api();

                        // Função para converter em inteiro
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                // Remove símbolos e converte para número
                                typeof i === 'number' ?
                                i : 0;
                        };
                        total = api
                            .column(2, {page: 'current'})
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);

                        $(api.column(2).footer()).html(
                            $.fn.dataTable.render.number('.', ',', 2).display(total)
                            
                        );
                    }
                });
                return table;
            }

            function saldo() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('adiantamento-despesas.saldo') }}",
                    success: function(response) {
                        $('.vl_adiantado').val(response.saldo['vl_adiantado']);
                        $('.vl_utilizado').val(response.saldo['vl_utilizado']);
                        $('.vl_devolver').val(response.saldo['vl_devolver']);

                    }
                });
            }

            function statusOrDelete(route, id) {
                $.ajax({
                    type: "POST",
                    url: route,
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        id: id,
                    },
                    success: function(response) {
                        if (response.success) {
                            msgToastr(response.success, 'success');
                            $('#table-comprovante').DataTable().ajax.reload();
                        } else {
                            msgToastr(response.error, 'error');
                        }
                    }
                });
            }

            function clear() {
                pictures = [];
                $('#menu').empty();
                $('#tp_despesa').val(0).trigger('change');
                $('.vl_consumido').val("");
                $('.cd_comprovante').val("");
            }

            function StoreOrUpdate(route, type) {

                tp_despesa = $('#tp_despesa').val();
                vl_consumido = $('.vl_consumido').val();
                cd_comprovante = $('.cd_comprovante').val();
                ds_observacao = $('#observacao').val();

                if (tp_despesa == 0) {
                    msgToastr('O campo Tipo de Despesa é obrigatório.', 'warning');
                    return 0;
                } else if (!vl_consumido) {
                    msgToastr('O campo Valor Consumido é obrigatório.', 'warning');
                    return 0;

                }
                if (type === 'I') {
                    if (pictures.length === 0) {
                        msgToastr('A lista de imagens está vazia.', 'warning');
                        return false; // Pare a execução                                        
                    }
                }
                if (type === 'U') {
                    toastr.warning(
                        "<button type='button' id='confirmationButtonYes' class='btn btn-success clear'>Sim</button>" +
                        "<button type='button' id='confirmationButtonNo' class='btn btn-primary clear'>Não</button>",
                        'Deseja apagar as fotos e substituir pelas atuais?', {
                            closeButton: false,
                            allowHtml: true,
                            progressBar: false,
                            timeOut: 0,
                            positionClass: "toast-top-center",
                            onShown: function(toast) {
                                $("#confirmationButtonYes").click(function() {
                                    if (pictures.length === 0) {
                                        msgToastr('A lista de imagens está vazia.', 'warning');
                                        return false; // Pare a execução                                        
                                    }
                                    // Continue somente se a lista de imagens não estiver vazia
                                    update_image = 1;
                                    sendData(route, type);
                                });

                                $("#confirmationButtonNo").click(function() {
                                    update_image = 0;
                                    sendData(route, type);
                                });
                            }
                        }
                    );
                } else {
                    sendData(route, type);
                }
            }

            function sendData(route, type) {
                $.ajax({
                    type: "POST",
                    url: route,
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        ds_observacao: ds_observacao,
                        tp_despesa: tp_despesa,
                        vl_consumido: vl_consumido,
                        pictures: pictures,
                        cd_comprovante: cd_comprovante,
                        update_image: update_image,

                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');
                        if (response.success) {
                            saldo();
                            clear();
                            msgToastr(response.success, 'success');
                            if (type === 'U') {
                                $('#btn-send').removeClass('hidden');
                                $('#btn-update').addClass('hidden');
                            }
                        } else {
                            msgToastr(response.error,
                                'warning');
                        }
                    }
                });
            }

            //Cliques nas tabs
            $('.nav-tabs a[href="#new-comprovante"]').on('click', function() {
                saldo();
            });
            $('.nav-tabs a[href="#comprovante-existing"]').on('click', function() {
                $('#table-comprovante').DataTable().clear().destroy();
                table = initTable();
            });

            $('#btn-send').click(function() {
                StoreOrUpdate("{{ route('store-adiantamento-despesas.vl_consumido') }}", "I");
            });

            $('body').on('click', '#getDeleteId', function() {
                var comprovante = $(this).data('id');
                route = "{{ route('delete-despesa') }}";
                statusOrDelete(route, comprovante);
            });

            $('body').on('click', '#visto-comprovante', function() {
                var comprovante = $(this).data('id');
                route = "{{ route('visto-despesa') }}";
                statusOrDelete(route, comprovante);
            });

            $('body').on('click', '#fotos-comprovante', function() {
                var comprovante = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "{{ route('adiantamento-despesas.pictures') }}",
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        id: comprovante,
                    },
                    success: function(response) {
                        $('#pictures-img').remove();
                        $('#response-pictures').append(response.html);
                        $('#modal-pictures').modal('show');
                    }
                });
            });

            $('body').on('click', '#edit-comprovante', function() {
                $('#btn-update').removeClass('hidden');
                $('#btn-send').addClass('hidden');
                var tr = $(this).closest('tr');

                var row = $('#table-comprovante').DataTable().row(tr);

                $('#menu').empty();

                $('#tp_despesa').val(row.data().despesa);
                $('.cd_comprovante').val(row.data().cd_adiantamento);
                $('.vl_consumido').val(row.data().vl_consumido);

                $('a[href="#new-comprovante"]').tab('show');
            });

        });
    </script>
@endsection
