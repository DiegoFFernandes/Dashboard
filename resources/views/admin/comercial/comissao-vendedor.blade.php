@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#gerar" data-toggle="tab" aria-expanded="true">Gerar Comissão</a>
                        </li>
                        <li class="">
                            <a href="#listar" data-toggle="tab" aria-expanded="false">Comissões Geradas</a>
                        </li>
                    </ul>
                    <div class="tab-content" style="padding: 0 10px;">
                        <div class="tab-pane active" id="gerar">
                            <div class="box-header" style="padding: 12px;">
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-xs btn-success" id="btn-liquidacao">Liquidação
                                    </button>
                                    <button type="button" class="btn btn-xs btn-primary" id="btn-faturamento">Faturamento
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                                @csrf
                                <div class="col-md-12" style="background-color: #ecf0f5; padding-top:15px">

                                    <div class="col-md-5">
                                        <label for="">Empresa:</label>
                                        <div class="form-group">
                                            @includeIf('admin.master.empresas')
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="" class="label-data">Data Liquidação:</label>
                                        <div class="form-group">
                                            @includeIf('admin.master.filtro-data')
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Area Comercial:</label>
                                        <div class="form-group">
                                            <select class="form-control select2" id="cd_areacomercial" style="width: 100%;">
                                                <option selected="selected">Selecione</option>
                                                @foreach ($area as $r)
                                                    <option value="{{ $r['CD_AREACOMERCIAL'] }}">
                                                        {{ $r['DS_AREACOMERCIAL'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Região Comercial:</label>
                                        <div class="form-group">
                                            <select class="form-control select2" id="cd_regiaocomercial"
                                                style="width: 100%;">
                                                <option selected="selected">Selecione</option>
                                                @foreach ($regiao as $r)
                                                    <option value="{{ $r['CD_REGIAOCOMERCIAL'] }}">
                                                        {{ $r['DS_REGIAOCOMERCIAL'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Tipo Vendedor:</label>
                                        <div class="form-group">
                                            <select class="form-control" name="tp_despesa" id="tp_despesa"
                                                style="width: 100%;" required>
                                                <option value="0" selected="selected">Selecione</option>
                                                <option value="C">Vendedor/Região Comercial</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Vendedor:</label>
                                        <div class="form-group">
                                            <select class="form-control select2" id="cd_vendedor" style="width: 100%;">
                                                <option selected="selected">Selecione</option>
                                                @foreach ($vendedor as $v)
                                                    <option value="{{ $v['CD_VENDEDOR'] }}">{{ $v['NM_PESSOA'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button class="btn btn-success" id="listComissaoLiq">Listar Liquidação</button>
                                <button class="btn btn-primary hidden" id="listComissaoFat">Listar Faturamento</button>
                            </div>

                            <!-- /.row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box box-table-comissao hidden">
                                        <!-- /.box-header -->
                                        <div class="box-body" style="padding: 0 10px">
                                            <table class="table stripe compact" style="width:100%; font-size: 11px" id="table-comissao">
                                                <thead>
                                                    <tr>
                                                        <th>Emp</th>
                                                        <th>Area</th>
                                                        <th>Vendedor</th>
                                                        <th>Região</th>
                                                        <th>Emissão</th>
                                                        <th>Pessoa</th>
                                                        <th>Liquidação</th>
                                                        <th>Nota</th>
                                                        <th>Vl Comissão</th>
                                                        <th>Ds Item</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Total</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <!-- /.box-body -->
                                        <div class="box-footer">
                                            <button class="btn btn-primary" id="btn-save">Salvar</button>
                                            <button class="btn btn-warning" id="btn-cancel">Cancelar</button>
                                        </div>
                                    </div>
                                    <!-- /.box -->
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane active" id="listar">
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
        var cd_empresa, cd_vendedor, table_comissao;
        $('#cd_empresa').select2();
        $('#cd_areacomercial').select2();
        $('#cd_regiaocomercial').select2();
        $('#cd_vendedor').select2();

        var inicioData = 0;
        var fimData = 0;
        $('#daterange').daterangepicker({
            autoUpdateInput: false,
        });
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                'DD/MM/YYYY'));
            inicioData = picker.startDate.format('MM/DD/YYYY');
            fimData = picker.endDate.format('MM/DD/YYYY');
        });
        $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val("");
            inicioData = 0;
            fimData = 0;
        });

        $('#btn-liquidacao').click(function() {
            $('.label-data').text('Data Liquidação:');
            $('#listComissaoFat').addClass('hidden');
            $('#listComissaoLiq').removeClass('hidden');
        });
        $('#btn-faturamento').click(function() {
            $('.label-data').text('Data Faturamento:');
            $('#listComissaoLiq').addClass('hidden');
            $('#listComissaoFat').removeClass('hidden');
        });

        $('#listComissaoLiq').click(function() {
            $('.box-table-comissao').removeClass('hidden');
            cd_empresa = $('#cd_empresa').val();
            cd_vendedor = $('#cd_vendedor').val();

            $("#table-comissao").DataTable().clear().destroy();

            table_comissao = $("#table-comissao").DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                // "pageLength": all,
                // responsive: true,
                paging: false,
                pagingType: "simple",
                processing: false,
                layout: {
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                },
                ajax: {
                    method: "GET",
                    url: " {{ route('comissao-liquidacao.list') }}",
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        cd_empresa: cd_empresa,
                        cd_vendedor: cd_vendedor,
                        data_ini: inicioData,
                        data_fim: fimData,
                    }
                },
                columns: [{
                        data: 'CD_EMPRESA',
                        name: 'CD_EMPRESA',
                    },
                    {
                        data: 'CD_PESSOA',
                        name: 'CD_PESSOA',
                    },
                    {
                        data: 'NM_VENDEDOR',
                        name: 'NM_VENDEDOR',
                    },
                    {
                        data: 'CD_PESSOA',
                        name: 'CD_PESSOA',
                    },
                    {
                        data: 'DT_EMISSAO',
                        name: 'DT_EMISSAO',
                    }, {
                        data: 'NM_PESSOA',
                        name: 'NM_PESSOA',
                    }, {
                        data: 'DT_LIQUIDACAO',
                        name: 'DT_LIQUIDACAO',
                    }, {
                        data: 'NR_NOTAFISCAL',
                        name: 'NR_NOTAFISCAL',
                    }, {
                        data: 'VL_COMISSAO',
                        name: 'VL_COMISSAO',
                    }, {
                        data: 'DS_ITEM',
                        name: 'DS_ITEM',
                    },
                ],
                // columnDefs: [{
                //     targets: 8,
                //     render: $.fn.dataTable.render.number('.', ',', 2),

                // }],
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
                        .column(8, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return parseFloat(a) + parseFloat(b);
                        }, 0);

                    $(api.column(8).footer()).html(
                        $.fn.dataTable.render.number('.', ',', 2).display(total)

                    );
                }
            });
        });

        $(document).on('click', '#table-comissao td:nth-child(9)', function() {
                var row = $(this).closest('tr');
                var rowData = table_comissao.row(row).data();                                 

                var valorCellComissao = $(row).find('td').eq(8);
                var valorComissao = parseFloat(valorCellComissao.text()).toFixed(2);               
                

                // Verificar se o input já está sendo editado
                if (!valorCellComissao.find('input').length) {
                    valorCellComissao.html('<input type="number" value="' + valorComissao +
                        '" class="edit-input" style="color: black; width: 100%; box-sizing: border-box;"/>'
                    );

                    // Levar o foco ao input e selecionar seu conteúdo
                    var input = valorCellComissao.find('input');
                    input.focus();
                    input.select();
                }

                // Quando o campo perde o foco, salva o valor editado
                valorCellComissao.find('input').on('blur', function() {                 
                    
                    var newValue = parseFloat($(this).val()).toFixed(2);
                    valorCellComissao.html(newValue);
                    // Atualiza os dados no DataTables
                   
                    rowData.VL_COMISSAO = newValue;                   
                    table_comissao.row(row).data(rowData).draw();
                });

            });
    </script>
@endsection
