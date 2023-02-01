@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $title_page }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label for="id_analise">Cód.:</label>
                                <input type="email" class="form-control" id="id_analise" value="{{ $analise->id }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="nm_pessoa">Cliente:</label>
                                <input type="text" class="form-control" id="nm_pessoa" value="{{ $analise->nm_pessoa }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="placa">Placa:</label>
                                <input type="text" class="form-control" id="placa" value="{{ $analise->placa }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pressao">Pressão Ideal:</label>
                                <input type="text" class="form-control"
                                    value="{{ $analise->ps_min . ' / ' . $analise->ps_max }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="responsavel">Sulco:</label>
                                <input type="text" class="form-control" value="{{ $analise->sulco }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="medida">Medida: </label>
                                <select class="form-control" name="medida" id="medida" required style="width: 100%">
                                    <option value="0" selected>Selecione um Medida</option>
                                    @foreach ($medidapneu as $m)
                                        <option value="{{ $m->ID }}">{{ $m->DS_MEDIDAPNEU }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 hidden-xs">
                            <div class="form-group">
                                <label for="fogo">Fogo:</label>
                                <input type="number" class="form-control" id="fogo" required>
                            </div>
                        </div>
                        <div class="col-md-3 hidden-xs">
                            <div class="form-group">
                                <label for="sulco">Sulco:</label>
                                <input type="number" class="form-control" id="sulco" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ps">Pressão:</label>
                                <input type="number" class="form-control" id="ps" placeholder="110">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="modelo">Modelo: </label>
                                <select class="form-control" name="modelo" id="modelo" required style="width: 100%">
                                    <option value="0" selected>Selecione um Modelo</option>
                                    @foreach ($modelopneu as $m)
                                        <option value="{!! $m->ID !!}">{{ $m->DS_MODELO }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="modelo">Dano Encontrado: </label>
                                <select class="form-control" name="motivo" id="motivo" required style="width: 100%">
                                    <option value="0" selected>Selecione um Dano</option>
                                    @foreach ($motivopneu as $m)
                                        <option value=" {!! $m->id !!}">{{ $m->ds_motivo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modelo">Posição: </label>
                                <select class="form-control" name="posicao" id="posicao" required style="width: 100%">
                                    <option value="0" selected>Selecione um posição</option>
                                    @foreach ($posicaopneu as $p)
                                        <option value=" {!! $p->id !!}">{{ $p->ds_posicao }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Capturar Imagem:</label>
                                {{-- <input type="file" class="form-control" id="image"> --}}
                                <button class="btn btn-primary btn-sm form-control" data-toggle="modal"
                                    data-target="#capture-image" id="open-camera">Abrir Câmera</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="submit-add-item" class="btn btn-success pull-right">Adicionar item</button>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="nav-tabs-custom" style="cursor: move;">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class=""><a href="#finalizar" data-toggle="tab" aria-expanded="false">Finalizar</a>
                        </li>
                        <li class=""><a href="#table-resumo" data-toggle="tab" aria-expanded="false">Resumo</a>
                        </li>
                        <li class="active"><a href="#table-itens" data-toggle="tab" aria-expanded="true">Itens</a>
                        </li>
                        <li class="pull-left header"><i class="fa fa-inbox"></i>Adicionados</li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="table-itens">
                            <table id="table-add-item" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Cód.</th>
                                        <th>Fogo</th>
                                        <th>Modelo</th>
                                        <th>Medida</th>
                                        <th>Pressão</th>
                                        <th>Sulco</th>
                                        <th>Posição</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="table-resumo">
                            <table id="table-item-group" class="table">
                                {{-- <thead>
                                    <tr>
                                        <th>Cód. Item</th>
                                        <th>Descrição</th>
                                        <th>Quantidade</th>
                                        <th>Soma Peso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($itemgroup as $i)
                                        <tr>
                                            <td>{{ $i->cd_produto }}</td>
                                            <td>{{ $i->ds_item }}</td>
                                            <td>{{ $i->qtditem }}</td>
                                            <td>{{ number_format($i->peso, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th style="text-align: center">Total</th>
                                        <th>{{ $itemgroup->sum(function ($i) {
                                            return $i->qtditem;
                                        }) }}
                                        </th>
                                        <th>{{ $pesototal = $itemgroup->sum(function ($i) {
                                            return $i->peso;
                                        }) }}
                                        </th>
                                    </tr>
                                </tfoot> --}}
                            </table>
                        </div>
                        <div class="tab-pane" id="finalizar">
                            <div class="box">
                                <div class="box-body">
                                    <button type="button" id="finalizar-lote" data-id=""
                                        class="btn btn-success center-block">Finalizar Lote</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="capture-image" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <div id="my_camera"></div>

                            <ul id="menu" class="list-unstyled">
                            </ul>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"
                                id="close-camera">Fechar Camera</button>
                            <button type="button" class="btn btn-primary" id="take_picture">Tirar Foto</button>
                        </div>
                    </div>

                </div>

            </div>
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var pictures = [],
                height, width, medida, ds_medida, fogo, sulco, modelo, ds_modelo, motivo, posicao, imagem, id,
                pressao;
            $('#motivo').select2();
            $('#medida').select2();
            $('#modelo').select2();
            $('#posicao').select2();
            $("#table-add-item").DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                pagingType: "simple",
                ajax: {
                    type: 'GET',
                    url: "{{ route('get-item-analysis') }}",
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                    }, {
                        data: 'fogo',
                        name: 'fogo'
                    },
                    {
                        data: 'ds_modelo',
                        name: 'ds_modelo'
                    },
                    {
                        data: 'ds_medida',
                        name: 'ds_medida'
                    },
                    {
                        data: 'pressao',
                        name: 'pressao'
                    },
                    {
                        data: 'sulco',
                        name: 'sulco'
                    },
                    {
                        data: 'ds_posicao',
                        name: 'ds_posicao'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                responsive: true,
                "order": [
                    [0, "desc"]
                ],
                createdRow: (row, data, dataIndex, cells) => {
                    $(cells[4]).css('background-color', data.ps);
                    $(cells[5]).css('background-color', data.sc);
                },
                lengthMenu: [
                    [10, 25, 50, 75, -1],
                    [10, 25, 50, 75, "Todos"]
                ],
                pageLength: 10
            });
            if (screen.height <= screen.width) {
                // Landscape                
                width = 320;
                height = 240;
            } else {
                // Portrait
                width = 240;
                height = 320;
            }
            $("#open-camera").click(function() {
                var cameraFacingMode = (cameraFacingMode == 'user') ? 'environment' : 'user';
                Webcam.set({
                    width: width,
                    height: height,
                    dest_width: width,
                    dest_height: height,
                    crop_width: width,
                    crop_height: height,
                    image_format: 'jpeg',
                    jpeg_quality: 100,
                    // flip_horiz: true,
                    constraints: {
                        // width: 360, // { exact: 320 },
                        // height: 780, // { exact: 180 },
                        facingMode: 'environment',
                        // facingMode: 'user',
                        frameRate: 30
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
            $('#submit-add-item').click(function() {
                id = $('#id_analise').val();
                fogo = $('#fogo').val();
                sulco = $('#sulco').val();
                id_modelo = $('#modelo').val();
                ds_modelo = $('#modelo option:selected').text();
                id_medida = $('#medida').val();
                ds_medida = $('#medida option:selected').text();
                id_motivo = $('#motivo').val();
                id_posicao = $('#posicao').val();
                pressao = $('#ps').val();

                $.ajax({
                    type: "post",
                    url: "{{ route('store-item-analysis') }}",
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        id: id,
                        id_medida: id_medida,
                        ds_medida: ds_medida,
                        fogo: fogo,
                        sulco: sulco,
                        pressao: pressao,
                        modelo: id_modelo,
                        ds_modelo: ds_modelo,
                        motivo: id_motivo,
                        posicao: id_posicao,
                        imagem: pictures
                    },
                    beforeSend: function() {

                    },
                    success: function(response) {
                        if (response.success) {
                            $("#table-add-item").DataTable().ajax.reload();
                            msgToastr(response.success, 'success');
                        } else {
                            msgToastr(response.error, 'danger');
                        }
                    }
                });
            });
            $("#table-add-item").on('click', '#delete-item', function() {
                id = $(this).data('id');

                if (confirm('Deseja excluir o item: ' + id + '')) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('delete-item-analysis') }}",
                        data: {
                            _token: $("[name=csrf-token]").attr("content"),
                            id: id,
                        },
                        success: function(response) {
                            if (response.success) {
                                $("#table-add-item").DataTable().ajax.reload();
                                msgToastr(response.success, 'success');
                            } else {
                                msgToastr(response.error, 'warning');
                            }
                        }
                    });
                }

            });
            $('#cd_barras').focus();
            $('.pula').keypress(function(e) {
                var tecla = (e.keyCode ? e.keyCode : e.which);
                if (tecla == 13) {
                    campo = $('.pula');
                    indice = campo.index(this);
                    if (campo[indice + 1] != null) {
                        proximo = campo[indice + 1];
                        proximo.focus();
                    }
                }
            });

            function listPictures(pictures) {
                $('#menu').empty();
                $.each(pictures, function(indexInArray, valueOfElement) {
                    $('#menu').append(
                        '<li>' +
                        '<img style="width: ' + width + 'px; height: ' + width + 'px;" src="' +
                        valueOfElement +
                        '"/>' +
                        '<button id="image" type="button" class="btn btn-danger" data-id="' +
                        valueOfElement + '" >X</button>' +
                        '</li>');
                });
            }

        });
    </script>
@endsection
