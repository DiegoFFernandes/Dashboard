@extends('admin.master.master')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info collapsed-box">
                    <div class="box-header with-border">
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-plus"></i>
                            </button>
                        </div>
                        <h4 class="box-title">Filtros</h4>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal">
                            <div class="col-md-2 col-sm-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Rede</label>
                                    <div class="col-sm-10">
                                        <select class="form-control input-sm select2" style="width: 100%;">
                                            <option selected="selected">Sul</option>
                                            <option>Norte</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Empresa</label>
                                    <div class="col-sm-9">
                                        <select class="form-control input-sm select2" style="width: 100%;">
                                            <option selected="selected">Alabama</option>
                                            <option>Alaska</option>
                                            <option>California</option>
                                            <option>Delaware</option>
                                            <option>Tennessee</option>
                                            <option>Texas</option>
                                            <option>Washington</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-2 control-label">Loja</label>
                                    <div class="col-sm-10">
                                        <select class="form-control input-sm select2" style="width: 100%;">
                                            <option selected="selected">Alabama</option>
                                            <option>Alaska</option>
                                            <option>California</option>
                                            <option>Delaware</option>
                                            <option>Tennessee</option>
                                            <option>Texas</option>
                                            <option>Washington</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-2 control-label">Area</label>
                                    <div class="col-sm-10">
                                        <select class="form-control input-sm select2" style="width: 100%;">
                                            <option selected="selected">Alabama</option>
                                            <option>Alaska</option>
                                            <option>California</option>
                                            <option>Delaware</option>
                                            <option>Tennessee</option>
                                            <option>Texas</option>
                                            <option>Washington</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="button" class="btn btn-block btn-warning btn-flat">Atualizar</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="small-box-info bg-green">
                    <div class="inner" style="padding-top: 15%">
                        <dt>Á vencer</dt>
                        <h4>{{ number_format($vvencer, 2, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box-info bg-aqua">
                    <div class="inner">
                        <dt>Vencidos até 60 Dias</dt>
                        <h4>{{ number_format($vvtotal, 2, ',', '.') }}</h4>

                        <div class="small-box-small col-md-6 col-sm-6 bg-aqua-active">
                            <small>Mais de 60 dias</small>
                            <h5>{{ number_format($vvencer120, 2, ',', '.') }}</h5>
                        </div>
                        <div class="small-box-small col-md-6 col-sm-6 bg-aqua-active">
                            <small>% Vencidos</small>
                            <h5>{{ number_format($porcent120, 2, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="small-box-info bg-light-blue">
                    <div class="inner" style="padding-top: 15%">
                        <dt>% Inadimplência</dt>
                        <h3>{{ number_format(($vvtotal / ($vvtotal + $vvencer)) * 100, 2, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box-info bg-light-blue">
                    <div class="inner">
                        <dt>Total Cheques</dt>
                        <h4>{{ number_format($vchequedesc + $vchequepre, 2, ',', '.') }}</h4>

                        <div class="small-box-small col-md-6 col-sm-6 bg-aqua-active">
                            <small>Ch Descontados</small>
                            <h5>{{ number_format($vchequedesc, 2, ',', '.') }}</h5>
                        </div>
                        <div class="small-box-small col-md-6 col-sm-6 bg-aqua-active">
                            <small>Ch Pré-datado</small>
                            <h5>{{ number_format($vchequepre, 2, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="small-box-info bg-gray">
                    <div class="inner" style="padding-top: 15%">
                        <dt>Juridico</dt>
                        <h3>1.000,00</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-gray">
                    <div class="inner">
                        <p>Total Carteira</p>
                        <h4>{{ number_format($vvtotal + $vvencer, 2, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="small-box bg-gray">
                    <div class="inner">
                        <p>(-) DP Descontadas</p>
                        <h4>{{ number_format($dpdescontada, 2, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="small-box bg-gray">
                    <div class="inner">
                        <p>(-) CH Descontados</p>
                        <h4>{{ number_format($vchequedesc, 2, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="small-box bg-gray">
                    <div class="inner">
                        <p>Total Livre</p>
                        <h4>{{ number_format($vvtotal + $vvencer - $vchequedesc - $dpdescontada, 2, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-info">
                    <div class="box-header">
                        <h4 class="box-title">Inadimplência</h4>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12">
                            <table class="table stripe compact nowrap" id="vvencer" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Area Comercial</th>
                                        <th>Vencido +60</th>
                                        <th>Vencido até 60</th>
                                        <th>%</th>
                                        <th>Á Vencer</th>
                                    </tr>
                                </thead>
                            </table>
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
    <script id="details-template" type="text/x-handlebars-template">
        @verbatim
            <div class="label label-info">{{ DS_AREACOMERCIAL }}</div>
            <table class="table details-table row-border" id="area-{{ CD_AREACOMERCIAL }}" style="width:100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>id</th>
                        <th>Região Comercial</th>
                        <th>Vencido +60</th>
                        <th>Vencido até 60</th>
                        <th>%</th>
                        <th>Á vencer</th>
                    </tr>
                </thead>
            </table>
        @endverbatim
    </script>
    <script id="details-regiao-template" type="text/x-handlebars-template">
        @verbatim
            <div class="label label-success">{{ DS_REGIAOCOMERCIAL }}</div>
            <table class="table details-regiao-table" id="regiao-{{ CD_REGIAOCOMERCIAL }}" style="width:100%">
                <thead>
                    <tr>
                        <th>Emp</th>
                        <th>Cliente</th>
                        <th>Documento</th>
                        <th>Cód Reg.</th>
                        <th>Ds Reg.</th>
                        <th>Vencido +60</th>
                        <th>Vencido até 60</th>
                        <th>%</th>
                        <th>Á vencer</th>
                    </tr>
                </thead>
            </table>
        @endverbatim
                    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2();
            var template = Handlebars.compile($("#details-template").html());
            var template_detais_regiao = Handlebars.compile($("#details-regiao-template").html());
            var table_regiao;
            var formt_number = $.fn.dataTable.render.number('.', ',', 2, '');
            var table = $('#vvencer').DataTable({
                language: {
                    url: "http://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                processing: false,
                serverSide: false,
                pageLength: 25,
                scrollX: true,
                ajax: "{{ route('inadimplencia.get-vencer') }}",
                columns: [{
                        "className": 'details-control',
                        "orderable": false,
                        "searchable": false,
                        "data": 'null',
                        "defaultContent": '<i class="fa fa-plus-circle"></i>',
                        "width": "1%"
                    },
                    {
                        data: 'DS_AREACOMERCIAL',
                        name: 'DS_AREACOMERCIAL',
                        "width": "25%"
                    },
                    {
                        data: 'VENCIDO120',
                        name: 'VENCIDO120',
                        render: formt_number
                    },
                    {
                        data: 'VENCIDO60',
                        name: 'VENCIDO60',
                        render: formt_number
                    },
                    {
                        data: 'porcent',
                        name: 'porcent'
                    },
                    {
                        data: 'AVENCER',
                        name: 'AVENCER',
                        render: formt_number
                    }
                ],
                order: [
                    [1, 'asc']
                ]
            });
            $('#vvencer tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var tableId = 'area-' + row.data().CD_AREACOMERCIAL;
                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                    $(this).find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
                } else {
                    // Open this row
                    row.child(template(row.data())).show();
                    initTable(tableId, row.data());
                    // console.log(row.data());
                    tr.addClass('shown');
                    $(this).find('i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
                    tr.next().find('td').addClass('no-padding bg-gray');
                }
            });

            function initTable(tableId, data) {
                table_regiao = $('#' + tableId).DataTable({
                    language: {
                        url: "http://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    "searching": false,
                    "paging": false,
                    "bInfo": false,
                    processing: false,
                    serverSide: false,
                    ajax: data.details_url,
                    columns: [{
                            "className": 'details-regiao-control',
                            "orderable": false,
                            "searchable": false,
                            "data": 'null',
                            "defaultContent": '<i class="fa fa-plus-square-o"></i>',
                            "width": "1%"
                        },
                        {
                            data: 'CD_REGIAOCOMERCIAL',
                            name: 'CD_REGIAOCOMERCIAL',
                            "width": "10%",
                            visible: false
                        },
                        {
                            data: 'DS_REGIAOCOMERCIAL',
                            name: 'DS_REGIAOCOMERCIAL',
                            "width": "25%"
                        },
                        {
                            data: 'VENCIDO120',
                            name: 'VENCIDO120',
                            render: formt_number
                        },
                        {
                            data: 'VENCIDO60',
                            name: 'VENCIDO60',
                            render: formt_number
                        },
                        {
                            data: 'porcent',
                            name: 'porcent'
                        },
                        {
                            data: 'AVENCER',
                            name: 'AVENCER',
                            render: formt_number
                        }
                    ]
                });
            }
            $('#vvencer tbody').on('click', 'td.details-regiao-control', function() {
                var tr_regiao = $(this).closest('tr');
                var row_regiao = table_regiao.row(tr_regiao);
                var tableId_regiao = 'regiao-' + row_regiao.data().CD_REGIAOCOMERCIAL;

                if (row_regiao.child.isShown()) {
                    // This row is already open - close it
                    row_regiao.child.hide();
                    tr_regiao.removeClass('shown');
                    $(this).find('i').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
                } else {
                    // Open this row
                    row_regiao.child(template_detais_regiao(row_regiao.data())).show();
                    // console.log(row_regiao.data().details_area_url);
                    initTableClient(tableId_regiao, row_regiao.data());
                    tr_regiao.addClass('shown');
                    $(this).find('i').removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
                    tr_regiao.next().find('td').addClass('no-padding bg-gray');
                }
            });

            function initTableClient(tableId, data) {
                $('#' + tableId).DataTable({
                    language: {
                        url: "http://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    "searching": true,
                    "paging": true,
                    "bInfo": true,
                    "pagingType": "simple",
                    processing: false,
                    serverSide: false,
                    ajax: data.details_area_url,
                    columns: [{
                            data: 'CD_EMPRESA',
                            name: 'CD_EMPRESA',
                            "width": "1%",
                        },
                        {
                            data: 'NM_PESSOA',
                            name: 'NM_PESSOA'
                        },
                        {
                            data: 'NR_DOCUMENTO',
                            name: 'NR_DOCUMENTO'
                        },
                        {
                            data: 'CD_REGIAOCOMERCIAL',
                            name: 'CD_REGIAOCOMERCIAL',
                            visible: false
                        },
                        {
                            data: 'DS_REGIAOCOMERCIAL',
                            name: 'DS_REGIAOCOMERCIAL',
                            visible: false
                        },
                        {
                            data: 'VENCIDO120',
                            name: 'VENCIDO120',
                            render: formt_number
                        },
                        {
                            data: 'VENCIDO60',
                            name: 'VENCIDO60',
                            render: formt_number
                        },
                        {
                            data: 'porcent',
                            name: 'porcent'
                        },
                        {
                            data: 'AVENCER',
                            name: 'AVENCER',
                            render: formt_number
                        }
                    ]
                });
            }
        });
    </script>
@endsection
