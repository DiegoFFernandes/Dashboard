@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $title_page }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-success alert-fixed hidden">
                            <p><i class="icon fa fa-check"></i></p>
                        </div>
                        @includeIf('admin.master.messages')
                        <input id="token" name="_token" type="hidden" value="{{ csrf_token() }}">
                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label for="lote">Cód. Lote</label>
                                <input type="email" class="form-control" id="id_lote" value="{{ $lote->id }}"
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cd_barras_peso">Cód. Barras Peso</label>
                                <input type="text" class="form-control pula" id="cd_barras_peso" placeholder="Cód. Peso">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="peso">Peso Kg</label>
                                <input type="text" class="form-control" id="peso" disabled>
                            </div>
                        </div>
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
                        <li class=""><a href="#finalizar" data-toggle="tab"
                                aria-expanded="false">Finalizar</a>
                        </li>
                        <li class=""><a href="#table-resumo" data-toggle="tab"
                                aria-expanded="false">Resumo</a>
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
                                        <th>Cód. Item</th>
                                        <th>Descrição</th>
                                        <th>Peso</th>
                                        <th>Úsuario</th>
                                        <th>Entrada em</th>
                                        <th>Deletar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($itemlote as $i)
                                        <tr>
                                            <td>{{ $i->cd_produto }}</td>
                                            <td>{{ $i->ds_item }}</td>
                                            @if ($i->peso < $i->ps_liquido)
                                                <td class="bg-red color-palette">{{ number_format($i->peso, 2) }}</td>
                                            @else
                                                <td class="bg-green color-palette">{{ number_format($i->peso, 2) }}</td>
                                            @endif
                                            <td>{{ $i->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($i->created_at)->format('d/m/Y H:i:s') }}</td>
                                            <td><button class="delete fa fa-trash-o" aria-hidden="true"
                                                    data-id="{{ $i->id }}"></button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="table-resumo">
                            <table id="table-item-group" class="table">
                                <thead>
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
    <script type="text/javascript">
        var pesoitem;
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
                } else if (cd_peso == "") {
                    $("#cd_item").tooltip('hide');
                    $('#cd_barras_peso').attr('title', 'Código peso ou kg obrigatório!').tooltip('show');
                    return false;
                }

                $.ajax({
                    method: "POST",
                    url: "{{ route('add-item-lote.store') }}",
                    data: {
                        _token: $('#token').val(),
                        cd_lote: $("#id_lote").val(),
                        cd_produto: $("#cd_item").val(),
                        peso: $("#peso").val(),
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(result) {
                        $("#loading").addClass('hidden');
                        if (result.errors) {
                            alert(result.errors +
                            " Peso ou produtos está fora dos parâmetros!");
                        } else {
                            //alert(result.success);                            
                            setTimeout(function() {
                                $(".alert").removeClass('hidden');
                                $(".alert p").text(result.success);
                            }, 400);

                            window.setTimeout(function() {
                                $(".alert").alert('close');
                                location.reload()
                            }, 1000);
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
                        peso: {{ $pesototal }},
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
            pageLength: 10
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
        $(document).ready(function() {
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
            })
        })
    </script>
@endsection
