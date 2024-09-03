@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs ui-sortable-handle">
                        <li class="active"><a id="acompanhamento" href="#acompanhamento-pedido" data-toggle="tab"
                                aria-expanded="false">Acompanhamento</a>
                        </li>
                        <li class=""><a id="bloqueio" href="#bloqueio-pedido" data-toggle="tab"
                                aria-expanded="true">Pedidos
                                Bloqueados</a>
                        </li>
                        {{-- <li class="pull-left header"><i class="fa fa-inbox"></i> Pedidos</li> --}}
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="bloqueio-pedido">
                            <table class="table stripe compact nowrap" id="bloqueio-pedidos" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Emp</th>
                                        <th>Pedido</th>
                                        <th>Pedido Palm</th>
                                        <th>Cliente</th>
                                        <th>Data</th>
                                        <th>Motivo</th>
                                        <th>Ativo</th>
                                        <th>Scpc</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane active" id="acompanhamento-pedido">
                            <table class="table stripe compact nowrap" id="pedido-acompanhar" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Emp</th>
                                        <th>Pedido</th>
                                        <th>Pedido Palm</th>
                                        <th>Cliente</th>
                                        <th>Dt Emissão</th>
                                        <th>Dt Entrega</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                            </table>
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
            <div class="label label-info">{{ PESSOA }}</div>
            <table class="table row-border" id="pedido-{{ ID }}" style="width:100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>Sq</th>
                        <th>Nr Ordem</th>
                        <th>Serviço</th>
                        <th>Valor</th>
                    </tr>
                </thead>
            </table>
        @endverbatim
    </script>
    <script id="details-item-pedido" type="text/x-handlebars-template">
        @verbatim
            <div class="label label-info">{{ NRORDEM }} - {{DSSERVICO}}</div>
            <table class="table row-border" id="item-pedido-{{ ID }}" style="width:100%">
                <thead>
                    <tr>
                        <th>Etapa</th>
                        <th>Usúario</th>
                        <th>Entrada</th>
                        <th>Saida</th>
                        <th>Detalhes</th>
                        <th>Retrabalho</th>
                    </tr>
                </thead>
            </table>
        @endverbatim
    </script>
    <script type="text/javascript">
        var template = Handlebars.compile($("#details-template").html());
        var details_item_pedido = Handlebars.compile($("#details-item-pedido").html());

        $('#bloqueio').click(function() {
            //Rever essa rotina atualiza caso o usuario voltar para aba bloqueio
            $('#bloqueio-pedidos').DataTable().destroy();
            $('#bloqueio-pedidos').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                pagingType: "simple",
                processing: false,
                serverSide: false,
                pageLength: 25,
                // responsive: true,
                scrollX: true,
                ajax: "{{ route('get-bloqueio-pedidos') }}",
                columns: [{
                        data: 'IDEMPRESA',
                        name: 'IDEMPRESA',
                        "width": "1%"
                    },
                    {
                        data: 'PEDIDO',
                        name: 'PEDIDO',
                        "width": "1%",
                        visible: false,
                    },
                    {
                        data: 'MOBILE',
                        name: 'MOBILE',
                    },
                    {
                        data: 'CLIENTE',
                        name: 'CLIENTE',
                    },
                    {
                        data: 'DATA',
                        name: 'DATA',
                    },
                    {
                        data: 'MOTIVO',
                        name: 'MOTIVO',
                    },
                    {
                        data: 'ST_ATIVA',
                        name: 'ST_ATIVA',
                    },
                    {
                        data: 'ST_SCPC',
                        name: 'ST_SCPC',
                    },
                    {
                        data: 'STPEDIDO',
                        name: 'STPEDIDO',
                    },
                    {
                        data: 'action',
                        name: 'action',
                    }
                ],
                columnDefs: [{
                    targets: [4],
                    render: $.fn.dataTable.render.moment('DD/MM/YYYY')
                }],
                createdRow: (row, data, dataIndex, cells) => {
                    $(cells[6]).css('background-color', data.status_cliente);
                    $(cells[7]).css('background-color', data.status_scpc);
                    $(cells[8]).css('background-color', data.status_pedido);
                }
            });

        });

        $('#acompanhamento').click(function() {
            $('#pedido-acompanhar').DataTable().ajax.reload();
        });

        $('#title-page').text('Acompanhameto Pedido');
        $('#pedido-acompanhar').DataTable().destroy();
        // $('#pedido-acompanhar tbody').empty();
        var table = $('#pedido-acompanhar').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
            },
            pagingType: "simple",
            processing: false,
            serverSide: false,
            pageLength: 25,
            retrieve: true,
            scrollX: true,
            ajax: "{{ route('get-pedido-acompanhar') }}",
            columns: [{
                    data: 'actions',
                    name: 'actions',
                    "width": "1%"
                },
                {
                    data: 'CD_EMPRESA',
                    name: 'CD_EMPRESA',
                    "width": "1%"
                },
                {
                    data: 'ID',
                    name: 'ID',
                    visible: true
                },
                {
                    data: 'IDPEDIDOMOVEL',
                    name: 'IDPEDIDOMOVEL',
                    "width": "10%"
                },
                {
                    data: 'PESSOA',
                    name: 'PESSOA',
                    "width": "40%"
                },
                {
                    data: 'DTEMISSAO',
                    name: 'DTEMISSAO',
                },
                {
                    data: 'DTENTREGAPED',
                    name: 'DTENTREGAPED',
                },
                {
                    data: 'STPEDIDO',
                    name: 'STPEDIDO',
                },
            ],
            columnDefs: [{
                targets: [5, 6],
                render: $.fn.dataTable.render.moment('DD/MM/YYYY')
            }],
            "order": [5, 'desc']
        });
        $('#pedido-acompanhar tbody').on('click', '.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var tableId = 'pedido-' + row.data().ID;
            console.log(tableId);
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                $(this).removeClass('fa-minus-circle').addClass('fa-plus-circle');
            } else {
                // Open this row
                row.child(template(row.data())).show();
                initTable(tableId, row.data());
                // console.log(row.data());
                tr.addClass('shown');
                $(this).removeClass('fa-plus-circle').addClass('fa-minus-circle');
                tr.next().find('td').addClass('no-padding bg-gray');
            }
        });

        function initTable(tableId, data) {

            table_item_pedido = $('#' + tableId).DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                "searching": false,
                "paging": false,
                "bInfo": false,
                processing: false,
                serverSide: false,
                ajax: {
                    method: "GET",
                    url: " {{ route('get-item-pedido-acompanhar') }}",
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        id: data.ID
                    }
                },
                columns: [{
                        "className": 'details-item-control',
                        "orderable": false,
                        "searchable": false,
                        "data": 'null',
                        "defaultContent": '<i class="fa fa-plus-square-o"></i>',
                        "width": "1%"
                    },
                    {
                        data: 'NRSEQUENCIA',
                        name: 'NRSEQUENCIA'
                    },
                    {
                        data: 'NRORDEM',
                        name: 'NRORDEM'
                    },
                    {
                        data: 'DSSERVICO',
                        name: 'DSSERVICO'
                    },
                    {
                        data: 'VLUNITARIO',
                        name: 'VLUNITARIO'
                    }
                ]
            });
        }

        $('#pedido-acompanhar tbody').on('click', 'td.details-item-control', function() {
            var tr_item = $(this).closest('tr');
            var row_item = table_item_pedido.row(tr_item);
            var tableId = 'item-pedido-' + row_item.data().ID;
            if (row_item.child.isShown()) {
                // This row is already open - close it
                row_item.child.hide();
                tr_item.removeClass('shown');
                $(this).find('i').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
            } else {
                // Open this row
                row_item.child(details_item_pedido(row_item.data())).show();
                initTableItemPedido(tableId, row_item.data());
                console.log(row_item.data());
                tr_item.addClass('shown');
                $(this).find('i').removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
                tr_item.next().find('td').addClass('no-padding bg-gray');
            }
        });

        function initTableItemPedido(tableId, data) {
            $('#' + tableId).DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                "searching": false,
                "paging": false,
                "bInfo": false,
                processing: false,
                serverSide: false,
                ajax: data.details_item_pedido_url,
                columns: [{
                        data: 'O_DS_ETAPA',
                        name: 'O_DS_ETAPA'
                    },
                    {
                        data: 'O_NM_USUARIO',
                        name: 'O_NM_USUARIO'
                    },
                    {
                        data: 'entrada',
                        name: 'entrada'
                    },
                    {
                        data: 'saida',
                        name: 'saida'
                    },
                    {
                        data: 'O_DS_COMPLEMENTOETAPA',
                        name: 'O_DS_COMPLEMENTOETAPA'
                    },
                    {
                        data: 'O_ST_RETRABALHO',
                        name: 'O_ST_RETRABALHO'
                    }
                ],
                "order": [2, 'asc']
            });
        }
    </script>
@endsection
