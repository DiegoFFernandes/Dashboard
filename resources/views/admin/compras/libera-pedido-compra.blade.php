@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#pendentes" data-toggle="tab" aria-expanded="true">Aguardando Analise</a>
                        </li>
                        <li class="">
                            <a href="#vistos" data-toggle="tab" aria-expanded="false">Pendentes Bloqueados</a>
                        </li>
                    </ul>
                    <div class="tab-content" style="padding: 0 10px;">
                        <div class="tab-pane active" id="pendentes">
                            <table class="table stripe compact" id="table-pedido-compra-bloqueadas-pendentes"
                                style="width:100%;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>Emp</th>
                                        <th>Pessoa</th>
                                        <th>Nr Docto</th>
                                        <th>Dt Solicitação</th>
                                        <th>Ds Liberacao</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>Emp</th>
                                        <th>Pessoa</th>
                                        <th>Docto</th>
                                        <th>Dt Solicitação</th>
                                        <th>Ds Liberacao</th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>
                        <div class="tab-pane" id="vistos">
                            <table class="table stripe compact" id="table-pedido-compra-bloqueadas-vistos"
                                style="width:100%;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>Emp</th>
                                        <th>Pessoa</th>
                                        <th>Nr Docto</th>
                                        <th>Dt Solicitação</th>
                                        <th>Ds Liberacao</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>Emp</th>
                                        <th>Pessoa</th>
                                        <th>Nr Docto</th>
                                        <th>Dt Solicitação</th>
                                        <th>Ds Liberacao</th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="liberacao">*Motivo Liberação/Bloqueio</label>
                        <textarea class="form-control" id="liberacao" rows="2" cols="50"></textarea>
                    </div>
                </div>

                <div class="col-md-6 col-sm-2" style="padding-top: 10px;">
                    <button class="btn btn-success btn-sm btn-block btn-aproover" id="">Aprovar</button>
                </div>
                <div class="col-md-6 col-sm-2" style="padding-top: 10px;">
                    <button class="btn btn-primary btn-sm btn-block btn-blocker" id="">Manter Bloquear</button>
                </div>
            </div>

        </div>

    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script id="details-item-historico" type="text/x-handlebars-template">
        @verbatim
            <div class="label label-info">{{ DS_TIPOCONTA }}</div>
            <table class="table row-border" id="solicitacao-{{ NR_SOLICITACAO }}" style="width:80%; font-size:12px" >
                <thead>
                    <tr>
                        <th>Historico</th>
                        <th>Parcela</th> 
                        <th>Emissão</th>
                        <th>Vencimento</th>
                        <th>Valor</th>                       
                    </tr>
                </thead>
                
            </table>
        @endverbatim
    </script>

    <script id="details-solicitacao" type="text/x-handlebars-template">
        @verbatim
            <div class="label label-info">{{ USUARIO }}</div>
            <table class="table row-border" id="solicitacao-{{ NR_SOLICITACAO }}" style="width:90%; font-size:12px" >
                <thead>
                    <tr>
                        <th>Ds item</th>
                        <th>Qtd</th>     
                        <th>Observação</th>                   
                    </tr>
                </thead>
                
            </table>
        @endverbatim
    </script>

    <script id="details-vencimento" type="text/x-handlebars-template">
        @verbatim
            <div class="label label-info">{{ USUARIO }}</div>
            <table class="table row-border" id="solicitacao-{{ NR_SOLICITACAO }}" style="width:80%; font-size:12px" >
                <thead>
                    <tr>
                        <th>Historico</th>
                        <th>Valor</th>                       
                    </tr>
                </thead>
                
            </table>
        @endverbatim
    </script>

    <script id="details-motivo" type="text/x-handlebars-template">
        @verbatim
            <div class="label label-info">{{ NM_PESSOA }}</div>
            <div class="col-md-12"><b>Observação:</b> {{ DS_OBSERVACAO }}</div> 
            <div class="col-md-12"><b>Motivo Bloqueio:</b> {{ DS_LIBERACAO }}</div>            
            
        @endverbatim
    </script>

    <script type="text/javascript">
        var tableSolicitacao;
        var template_historico = Handlebars.compile($("#details-item-historico").html());
        var template_motivo = Handlebars.compile($("#details-motivo").html());
        var template_vencimento = Handlebars.compile($("#details-vencimento").html());
        var template_ds_solicitacao = Handlebars.compile($("#details-solicitacao").html());

        tableSolicitacao = initableSolicitacao('table-pedido-compra-bloqueadas-pendentes', 'N');

        //Cliques nas tabs
        $('.nav-tabs a[href="#pendentes"]').on('click', function() {
            $('#table-pedido-compra-bloqueadas-pendentes').DataTable().destroy();
            tableSolicitacao = initableSolicitacao('table-pedido-compra-bloqueadas-pendentes', 'N');
        });

        $('.nav-tabs a[href="#vistos"]').on('click', function() {
            $('#table-pedido-compra-bloqueadas-vistos').DataTable().destroy();
            tableSolicitacao = initableSolicitacao('table-pedido-compra-bloqueadas-vistos', 'S');
        });

        $('tbody').on('click', '.details-motivo', function() {
            var tr = $(this).closest('tr');
            var row = tableSolicitacao.row(tr);

            var nm_pessoa = row.data().USUARIO;

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                $(this).removeClass('btn-close').addClass('btn-open');
                $('.details-centrocusto').removeClass('btn-close').addClass('btn-open');

            } else {
                // Open this row
                row.child(template_motivo(row.data())).show();
                // console.log(row.data());
                tr.addClass('shown');
                tr.next().find('td').addClass('no-padding bg-gray-light')
                $('.details-centrocusto').removeClass('btn-close').addClass('btn-open');
                $(this).removeClass('btn-open').addClass('btn-close');
            }
        });

        $('tbody').on('click', '.details-solicitacao', function() {
            var tr = $(this).closest('tr');
            var row = tableSolicitacao.row(tr);
            var tableId = 'solicitacao-' + row.data().NR_SOLICITACAO;

            var nm_pessoa = row.data().USUARIO;

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                $(this).removeClass('btn-close').addClass('btn-open');
                $('.details-motivo').removeClass('btn-close').addClass('btn-open');


            } else {
                // Open this row
                row.child(template_ds_solicitacao(row.data())).show();
                initTableItemSolicitacao(tableId, row.data());

                tr.addClass('shown');
                tr.next().find('td').addClass('no-padding bg-gray-light')
                $('.details-motivo').removeClass('btn-close').addClass('btn-open');
                $(this).removeClass('btn-open').addClass('btn-close');
            }
        });

        $('.btn-aproover').click(function() {
            var dsLiberacao = $('#liberacao').val(); // Captura o valor do textarea       

            if (dsLiberacao == "") {
                msgToastr('Motivo Liberação/Bloqueio deve ser informado!', 'warning');
                return false;
            }
            // libera a solicitação para compra
            loadData('N', dsLiberacao)
        });

        $('.btn-blocker').click(function() {
            var dsLiberacao = $('#liberacao').val(); // Captura o valor do textarea     
            if (dsLiberacao == "") {
                msgToastr('Motivo Liberação/Bloqueio deve ser informado!', 'warning');
                return false;
            }
            // Mantem a conta bloqueada, mas muda a solicitação para aba bloqueadas pendentes.
            loadData('B', dsLiberacao)
        });

        function loadData(status, dsLiberacao) {
            var rows = tableSolicitacao.rows({
                selected: true
            }).data().toArray();

            var pedidos = [];

            if (rows.length > 0) {
                rows.forEach(function(row) {
                    pedidos.push({
                        nr_solicitacao: row.NR_SOLICITACAO,
                        cd_empresa: row.CD_EMPRESA,
                        status: status,
                        ds_observacao: row.DS_OBSERVACAO
                    });

                });
                $.ajax({
                    method: "post",
                    url: "{{ route('compras-bloqueadas.update') }}",
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        pedidos: pedidos,
                        ds_liberacao: dsLiberacao
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');
                        if (response.success) {
                            msgToastr(response.success, 'success');
                        } else {
                            msgToastr(response.warning, 'warning');
                        }
                        $('#table-pedido-compra-bloqueadas-pendentes').DataTable().ajax.reload();
                        $('#table-pedido-compra-bloqueadas-vistos').DataTable().ajax.reload();
                    }
                });

            } else {
                msgToastr('Nenhuma conta foi selecionada!', 'warning');                
            }
        };

        function initTableItemSolicitacao(tableId, data) {
            var tableItemSolicitacao = $('#' + tableId).DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                "searching": false,
                "paging": false,
                "bInfo": false,
                processing: false,
                serverSide: false,
                ordering: false,
                ajax: {
                    method: "GET",
                    url: " {{ route('pedidos-compra-bloqueadas-item.list') }}",
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        nr_solicitacao: data.NR_SOLICITACAO,
                        cd_empresa: data.CD_EMPRESA
                    }
                },
                columns: [{
                        data: 'DS_ITEM',
                        name: 'DS_ITEM',
                    },
                    {
                        data: 'QT_SOLICITADA',
                        name: 'QT_SOLICITADA',
                    },
                    {
                        data: 'DS_OBSERVACAO',
                        name: 'DS_OBSERVACAO',
                        width: '60%'
                    }
                ],
                columnDefs: [{
                    targets: [1],
                    render: $.fn.dataTable.render.number('.', ',', 2),
                }],
                "footerCallback": function(tfoot, data, start, end, display) {
                    $(tfoot).find('td').removeClass('no-padding');
                }

            });
        };

        function initableSolicitacao(tableID, status) {
            tableSolicitacao = $('#' + tableID).DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                "searching": true,
                "paging": false,
                "bInfo": false,
                orderCellsTop: true,
                processing: false,
                serverSide: false,
                scrollX: true,
                scrollY: '50vh',
                select: 'single',
                ajax: {
                    url: '{{ route('pedidos-compra-bloqueadas.list') }}',
                    data: {
                        st_visto: status,
                    }
                },
                columns: [{
                        data: null,
                        "width": "1%",
                        render: DataTable.render.select(),
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        serachable: false,
                        sClass: 'text-center',
                    }, {
                        data: 'CD_EMPRESA',
                        name: 'CD_EMPRESA',
                        "width": "1%",
                        visible: true,
                    },
                    {
                        data: 'USUARIO',
                        name: 'USUARIO',
                        visible: true,
                    },
                    {
                        data: 'NR_SOLICITACAO',
                        name: 'NR_SOLICITACAO',
                        visible: true,
                    },
                    {
                        data: 'DT_SOLICITACAO',
                        name: 'DT_SOLICITACAO',
                        visible: true,
                    },
                    {
                        data: 'DS_OBSERVACAO',
                        name: 'DS_OBSERVACAO',
                        visible: false,
                    },
                ],
                columnDefs: [{
                    targets: [5],
                    render: $.fn.dataTable.render.moment('DD/MM/YYYY')
                }],
                order: [
                    [4, 'desc']
                ],
                initComplete: function() {
                    this.api()
                        .columns()
                        .every(function() {
                            var column = this;
                            var title = column.footer().textContent;

                            if (title != '') {
                                // Create input element and add event listener
                                $('<input type="text" placeholder="' + title + '" />')
                                    .appendTo($(column.footer()).empty())
                                    .on('keyup change clear', function() {
                                        if (column.search() !== this.value) {
                                            column.search(this.value).draw();
                                        }
                                    });
                            }

                        });
                },
                rowCallback: function(row, data) {
                    $(row).attr('data-toggle', 'tooltip').attr('title', data['DS_OBSERVACAO']);
                }

            });
            return tableSolicitacao;
        };
    </script>
@endsection
