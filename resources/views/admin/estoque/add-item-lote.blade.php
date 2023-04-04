@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $title_page }}</h3>
                        <div class="box-tools pull-right">
                            <span class="label label-danger" id="qtd_itens_coleta">{{ $qtde_coleta }} Itens</span>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-success alert-fixed hidden">
                            <p><i class="icon fa fa-check"></i></p>
                        </div>
                        @includeIf('admin.master.messages')
                        <input id="token" name="_token" type="hidden" value="{{ csrf_token() }}">
                        <input type="hidden" class="form-control" id="id_subgrupo" value="{{ $lote->id_subgrupo }}">
                        <input type="hidden" class="form-control" id="id_marca" value="{{ $lote->id_marca }}">
                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label for="lote">Cód. Lote</label>
                                <input type="text" class="form-control" id="id_lote" value="{{ $lote->id }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-3 hidden-xs">
                            <div class="form-group">
                                <label for="ds_lote">Descrição</label>
                                <input type="text" class="form-control" id="ds_lote" value="{{ $lote->descricao }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-4 hidden-xs">
                            <div class="form-group">
                                <label for="responsavel">Responsável</label>
                                <input type="text" class="form-control" id="responsavel" value="{{ $lote->cd_usuario }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label for="created_at">Criado em:</label>
                                <input type="text" class="form-control" id="created_at" value="{{ $lote->created_at }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cd_barras">Cód. Barras Prod.</label>
                                <input type="text" class="form-control pula" id="cd_barras" placeholder="Cód. Barras">
                            </div>
                        </div>
                        <div class="col-md-3 hidden-xs">
                            <div class="form-group">
                                <label for="cd_item">Cód. Produto</label>
                                <input type="text" class="form-control" id="cd_item" disabled required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ds_produto">Descrição Produto</label>
                                <input type="text" class="form-control" id="ds_produto" disabled>
                            </div>
                        </div>
                        @if ($lote->id_subgrupo == 101 && $lote->id_marca == 30)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cd_barras_peso">Cód. Barras Peso</label>
                                    <input type="text" class="form-control pula" id="cd_barras_peso"
                                        placeholder="Cód. Peso">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="peso">Peso Kg</label>
                                    <input type="text" class="form-control" id="peso" disabled>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="submit-add-item" class="btn btn-primary pull-right">Adicionar item</button>
                    </div>
                </div>
                {{-- Icon loading --}}
                <div class="hidden" id="loading">
                    <img id="loading-image" class="mb-4" src="{{ Asset('img/loader.gif') }}" alt="Loading...">
                </div>
            </div>
            <div class="col-md-6">
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
                            <table id="table-add-item" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Cód. Item</th>
                                        <th>Descrição</th>
                                        <th>{{ $lote->id_subgrupo == 101 && $lote->id_marca == 30 ? 'Kg' : 'Unid.' }}</th>
                                        <th>Úsuario</th>
                                        <th>Entrada em</th>
                                        <th>Deletar</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="table-resumo">
                            <table id="table-item-group" class="table">
                                <thead>
                                    <tr>
                                        <th>Cód. Item</th>
                                        <th>Descrição</th>
                                        <th>Qtd.</th>
                                        <th>{{ $lote->id_subgrupo == 101 && $lote->id_marca == 30 ? 'Soma Kg' : 'Unid.' }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($itemgroup as $i)
                                        <tr>
                                            <td>{{ $i->cd_produto }}</td>
                                            <td>{{ $i->ds_item }}</td>
                                            <td>{{ $i->qtditem }}</td>
                                            <td>{{ number_format($i->peso, 2) }}</td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                                {{-- <tfoot>
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
                                <tfoot>
                                    <tr>
                                      <td></td>
                                      <th style="text-align: right">Total</th>
                                      <td></td>
                                      <td></td>
                                    </tr>
                                  </tfoot>
                            </table>
                        </div>
                        <div class="tab-pane" id="finalizar">
                            <div class="box">
                                <div class="box-body">
                                    <button type="button" id="finalizar-lote" data-id="{{ $lote->id }}"
                                        class="btn btn-success center-block">Finalizar Lote</button>
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
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script type="text/javascript">
        var pesoitem, subgrupo;
        subgrupo = $('#id_subgrupo').val();
        marca = $('#id_marca').val();
        id_lote = $("#id_lote").val();
        let token = $("meta[name='csrf-token']").attr("content");
        $(document).ready(function() {
            $("#cd_barras").inputmask({
                mask: ['A9999999', '9999999999999']
            });
            $("#cd_barras_peso").inputmask({
                mask: ['99.99', '9Q99.99']
            });
            $("#cd_barras").on("keypress focusout", function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                var cd_barras = $("#cd_barras").val();
                url = "{{ route('get-item-lote', ':cd_barras') }}";
                url = url.replace(':cd_barras', cd_barras);
                if (keycode == '9' || keycode == '13' || event.type == "focusout") {
                    $.ajax({
                        url: url,
                        method: "GET",
                        success: function(result) {
                            if (result.error) {
                                alert("Cód: " + cd_barras + " - " + result.error);
                                location.reload();
                            } else {
                                $("#ds_produto").val(result.ds_item);
                                $("#cd_item").val(result.cd_item);
                                //Essa variavel alimenta a condição #cd_barras_peso
                                pesoitem = parseFloat(result.ps_liquido);
                            }
                        }
                    });
                }
            });
            $("#cd_barras_peso").on("keydown focusout", function(event) {
                if ($("#cd_item").val() == "") {
                    $('#cd_barras').attr('title', 'Código produto obrigatório!').tooltip('show');
                    return false;
                }
                var keycode = (event.keyCode ? event.keyCode : event.which);
                var str = $("#cd_barras_peso").val();
                if (keycode == '9' || keycode == '13' || event.type == "focusout") {
                    var peso = str.replace('1Q', '');
                    peso_ = peso.toString().replace(",", ".")
                    peso = parseFloat(peso);
                    if (peso <= (pesoitem - (pesoitem * 10 / 100)) || peso >= (pesoitem + (pesoitem * 10 /
                            100))) {
                        // $('#cd_barras_peso').attr('title', 'Peso está fora dos parâmetros para esse item!')
                        //     .tooltip('show');
                        if (confirm(
                                'Peso está fora dos parâmetros para esse item! Deseja lançar mesmo assim?'
                            )) {
                            $("#peso").val(peso_);
                            return true;
                        }
                    }
                    $("#peso").val(peso_);
                }
            });
            $("#submit-add-item").on('click', function() {
                let cd_produto = $("#cd_item").val();
                let cd_peso = $("#peso").val();
                if (cd_produto == "") {
                    $('#cd_barras').attr('title', 'Código produto obrigatório!').tooltip('show');
                    return false;
                }

                if (subgrupo == 101 && marca == 30) {
                    if (cd_peso == "") {
                        $("#cd_item").tooltip('hide');
                        $('#cd_barras_peso').attr('title', 'Código peso ou kg obrigatório!').tooltip(
                            'show');
                        return false;
                    }
                }

                $.ajax({
                    method: "POST",
                    url: "{{ route('add-item-lote.store') }}",
                    data: {
                        _token: $('#token').val(),
                        cd_lote: id_lote,
                        cd_produto: $("#cd_item").val(),
                        peso: $("#peso").val(),
                        id_subgrupo: subgrupo,
                        id_marca: marca
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(result) {

                        $("#loading").addClass('hidden');
                        if (result.errors) {
                            msgToastr(result.errors, 'warning');

                        } else {
                            //console.log(result);  
                            $("#cd_barras").val("");
                            $('#cd_item').val("");
                            $('#peso').val("");
                            $('#cd_barras_peso').val("");
                            msgToastr(result.success, 'success');
                            $('#table-add-item').DataTable().ajax.reload();
                            $('#qtd_itens_coleta').text(result.qtde + " Itens");
                        }
                    }
                });
            });
            $("#finalizar-lote").on('click', function() {
                let id_lote = $(this).data();
                $.ajax({
                    method: "POST",
                    url: "{{ route('estoque.finish-lote') }}",
                    data: {
                        id: id_lote['id'],                        
                        _token: token,
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(result) {
                        $("#loading").addClass('hidden');
                        alert(result.success);
                        window.location.replace("{{ route('estoque.index') }}");
                    }
                });
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
            $("#table-add-item").DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                pagingType: "simple",
                responsive: true,
                "order": [
                    [3, "desc"]
                ],
                lengthMenu: [
                    [10, 25, 50, 75, -1],
                    [10, 25, 50, 75, "Todos"]
                ],
                pageLength: 10,
                ajax: {
                    url: "{{ route('estoque.get-itens-lote') }}",
                    data: {
                        id_lote: id_lote
                    }
                },
                columns: [{
                        data: 'cd_produto',
                        name: 'cd_produto'
                    },
                    {
                        data: 'ds_item',
                        name: 'ds_item'
                    },
                    {
                        data: 'peso',
                        name: 'peso'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    },

                ],
                createdRow: (row, data, dataIndex, cells) => {
                    $(cells[2]).css('background-color', data.ps);
                },

            });
            $("#table-add-item").on('click', '.delete', function() {
                let rowId = $(this).data();
                if (confirm('Deseja excluir o item: ' + rowId['id'] + '')) {
                    $.ajax({
                        method: "DELETE",
                        url: "{{ route('delete-item-lote') }}",
                        data: {
                            id: rowId['id'],
                            _token: token
                        },
                        beforeSend: function() {
                            $("#loading").removeClass('hidden');
                        },
                        success: function(result) {
                            $("#loading").addClass('hidden');
                            alert(result.success);
                            location.reload()
                        }
                    });
                }
            });
            $('.nav-tabs a[href="#table-resumo"]').on('click', function() {
                $('#table-item-group').DataTable().destroy();
                $("#table-item-group").DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    pagingType: "simple",

                    lengthMenu: [
                        [10, 25, 50, 75, -1],
                        [10, 25, 50, 75, "Todos"]
                    ],
                    pageLength: 10,
                    ajax: {
                        url: "{{ route('estoque.get-resume-itens-lote') }}",
                        data: {
                            id_lote: id_lote
                        }
                    },
                    columns: [{
                        data: 'cd_produto',
                        name: 'cd_produto'
                    }, {
                        data: 'ds_item',
                        name: 'ds_item'
                    }, {
                        data: 'qtditem',
                        name: 'qtditem'
                    }, {
                        data: 'peso',
                        name: 'peso'
                    }],
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api();

                        // Total da coluna "qtditem"
                        var totalQtdItem = api.column(2, {
                            page: 'current'
                        }).data().sum();

                        var totalPesoItem = api.column(3,{
                            page: 'current'
                        }).data().sum();

                        // Adiciona o valor no rodapé da coluna "qtditem"
                        $(api.column(2).footer()).html(totalQtdItem);
                        $(api.column(3).footer()).html(totalPesoItem);
                    }

                });
            });
        });
    </script>
@endsection
