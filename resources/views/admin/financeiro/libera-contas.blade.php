@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                    </div>
                    <div class="box-body">
                        <table class="table stripe compact" id="table-contas-bloqueadas" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Emp</th>
                                    <th>Pessoa</th>
                                    <th>Emissão</th>
                                    <th>Docto</th>
                                    <th>Parcelas</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                        </table>
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
    <script id="details-item-historico" type="text/x-handlebars-template">
        @verbatim
            <div class="label label-info">{{ DS_TIPOCONTA }}</div>
            <table class="table row-border" id="conta-{{ NR_LANCAMENTO }}" style="width:80%; font-size:12px" >
                <thead>
                    <tr>
                        <th>Historico</th>
                        <th>Valor</th>                       
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr style="">
                        <td colspan="2" id="tfooter" class="text-center" style="padding-top: 5px; padding-bottom: 15px;">
                            <div class="col-md-6 col-sm-2" style="padding-top: 10px;">
                                <button class="btn btn-success btn-sm btn-block" id="btn-open-modal-aproover">Aprovar</button>
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
        var template = Handlebars.compile($("#details-item-historico").html());

        var table = $('#table-contas-bloqueadas').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
            },
            "searching": true,
            "paging": false,
            "bInfo": false,
            processing: false,
            serverSide: false,
            scrollX: true,
            ajax: "{{ route('contas-bloqueadas.list') }}",
            columns: [{
                    "className": 'details-control',
                    "orderable": false,
                    "searchable": false,
                    "data": 'null',
                    "defaultContent": '<i class="fa fa-plus-circle"></i>',
                    "width": "1%"
                }, {
                    data: 'CD_EMPRESA',
                    name: 'CD_EMPRESA',
                    "width": "1%",
                    visible: true,
                },
                {
                    data: 'NM_PESSOA',
                    name: 'NM_PESSOA',
                    visible: true,
                },
                {
                    data: 'NR_DOCUMENTO',
                    name: 'NR_DOCUMENTO',
                    visible: true,
                },
                {
                    data: 'DS_TIPOCONTA',
                    name: 'DS_TIPOCONTA',
                    visible: true,
                },
                {
                    data: 'NM_PESSOA',
                    name: 'NM_PESSOA',
                    visible: true,
                },
                {
                    data: 'VL_DOCUMENTO',
                    name: 'VL_DOCUMENTO',
                    visible: true,
                },
            ],
            rowCallback: function(row, data) {
                console.log(data);
                $(row).attr('title', data['DS_OBSERVACAO'])
            }
        });
        console.log($("[name=csrf-token]").attr("content"));
        $('#table-contas-bloqueadas tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var tableId = 'conta-' + row.data().NR_LANCAMENTO;
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
                tr.next().find('td').addClass('no-padding bg-gray-light')
            }
        });

        function initTable(tableId, data) {
            var tableHistorico = $('#' + tableId).DataTable({
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
                    url: " {{ route('historico-contas-bloqueadas.list') }}",
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        nr_lancamento: data.NR_LANCAMENTO,
                        cd_empresa: data.CD_EMPRESA
                    }
                },
                columns: [{
                        data: 'DS_HISTORICO',
                        name: 'DS_HISTORICO',
                        width: '20%'

                    },
                    {
                        data: 'VL_DOCUMENTO',
                        name: 'VL_DOCUMENTO',
                        width: '1%'

                    },
                ],
                "footerCallback": function(tfoot, data, start, end, display) {
                    $(tfoot).find('td').removeClass('no-padding');
                }

            });
        }
    </script>
@endsection
