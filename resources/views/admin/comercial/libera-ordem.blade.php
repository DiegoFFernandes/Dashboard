@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pedidos</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table stripe compact" style="width:100%" id="table-ordem-block">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Emp</th>
                                    <th>Pedido</th>
                                    <th>Cliente</th>
                                    <th>Vendedor</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.row -->
    <div class="modal modal-default fade" id="modal-pedido">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Liberar Pedido</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nr_pedido">Pedido</label>
                                <input id="nr_pedido" class="form-control" type="text" readonly>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="pessoa">Pessoa</label>
                                <input id="pessoa" class="form-control" type="text" readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="liberacao">Motivo Liberação</label>
                                <textarea id="liberacao" class="form-control" rows="4" cols="50"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alert pull-left" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btn-save-confirm">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script id="details-template" type="text/x-handlebars-template">
        @verbatim
            <div class="label label-info">{{ PESSOA }} - {{ PEDIDO}}</div>
            <table class="table row-border" id="pedido-{{ PEDIDO }}" style="width:80%; font-size:12px" >
                <thead>
                    <tr>                        
                        <th style="">Sq</th>
                        <th>Item</th>
                        <th style="">Vl Venda</th>
                        <th style="">Vl Tabela</th>
                        <th style="">%Desc</th>                        
                    </tr>
                </thead>
                <tbody></tbody> 
                <tfoot>
                    <tr style="">
                        <td colspan="5" id="tfooter" class="text-center" style="padding-top: 5px; padding-bottom: 15px;">
                            <div class="col-md-6 col-sm-2" style="padding-top: 10px;">
                                <button class="btn btn-success btn-sm btn-block" data-pedido="{{ PEDIDO }}" data-pessoa="{{ PESSOA }}" id="btn-open-modal-aproover">Aprovar</button>
                            </div>
                            <div class="col-md-6 col-sm-2" style="padding-top: 10px;">
                                <button class="btn btn-primary btn-sm btn-block">Cancelar</button>
                            </div>                          
                        </td>
                    </tr>
                </tfoot>            
            </table>
        @endverbatim
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var template = Handlebars.compile($("#details-template").html());
            var table = $('#table-ordem-block').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                "searching": true,
                "paging": false,
                "bInfo": false,
                processing: false,
                serverSide: false,
                scrollX: true,
                ajax: "{{ route('get-ordens-bloqueadas-comercial') }}",
                columns: [{
                        "className": 'details-control',
                        "orderable": false,
                        "searchable": false,
                        "data": 'null',
                        "defaultContent": '<i class="fa fa-plus-circle"></i>',
                        "width": "1%"
                    },
                    {
                        data: 'EMP',
                        name: 'EMP',
                        "width": "1%",
                        visible: true
                    },
                    {
                        data: 'PEDIDO',
                        name: 'PEDIDO'
                    },
                    {
                        data: 'PESSOA',
                        name: 'PESSOA'
                    },
                    {
                        data: 'VENDEDOR',
                        name: 'VENDEDOR',
                        visible: true
                    }
                ],
                order: [2, 'asc']
            });
            $('#table-ordem-block tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var tableId = 'pedido-' + row.data().PEDIDO;
                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                    $(this).find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
                } else {
                    // Open this row
                    row.child(template(row.data())).show();
                    initTable(tableId, row.data());
                    tr.addClass('shown');
                    $(this).find('i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
                    tr.next().find('td').addClass('no-padding bg-gray-light');
                }
            });

            function initTable(tableId, data) {
                var url = "{{ route('get-pneus-ordens-bloqueadas-comercial', ':pedido') }}";
                url = url.replace(':pedido', data.PEDIDO);
                table_item_pedido = $('#' + tableId).DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    "searching": false,
                    "paging": false,
                    "bInfo": false,
                    processing: false,
                    serverSide: false,
                    ordering: false,
                    ajax: url,
                    columns: [{
                            data: 'SEQ',
                            name: 'SEQ',
                            width: '1%'
                        },
                        {
                            data: 'DS_ITEM',
                            name: 'DS_ITEM',
                            width: '20%'
                        },
                        {
                            data: 'VL_VENDA',
                            name: 'VL_VENDA',
                            width: '1%'
                        },
                        {
                            data: 'VL_PRECO',
                            name: 'VL_PRECO',
                            width: '1%'
                        },
                        {
                            data: 'PC_DESCONTO',
                            name: 'PC_DESCONTO',
                            width: '1%'
                        }
                    ],
                    "footerCallback": function(tfoot, data, start, end, display) {
                        $(tfoot).find('td').removeClass('no-padding');
                    }

                });


            }
            $(document).on('click', '#btn-open-modal-aproover', function() {
                var pessoa = $(this).data('pessoa');
                var pedido = $(this).data('pedido');
                $('#nr_pedido').val(pedido);
                $('#pessoa').val(pessoa);
                $('#modal-pedido').modal('show');
            });
            $(document).on('click', '#btn-save-confirm', function() {
                $.ajax({
                    url: "{{ route('save-libera-pedido') }}",
                    method: 'GET',
                    data: {
                        pedido: $('#nr_pedido').val(),
                        liberacao: $('#liberacao').val()
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');

                        if (response.success) {
                            msgToastr(response.success,
                                'success');
                            $('#table-ordem-block').DataTable().ajax.reload();
                            $('#modal-pedido').modal('hide');
                        } else {
                            msgToastr(response.error,
                                'warning');
                        }
                    }
                });
            });


        });
    </script>
@endsection
