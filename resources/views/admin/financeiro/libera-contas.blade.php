@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
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
                            <table class="table stripe compact" id="table-contas-bloqueadas-pendentes" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>Emp</th>
                                        <th>Pessoa</th>
                                        <th>Docto</th>
                                        <th>Parcelas</th>
                                        <th>Total</th>
                                        <th>Emissão</th>
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
                                        <th>Parcelas</th>
                                        <th>Total</th>
                                        <th>Emissão</th>
                                        <th>Ds Liberacao</th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>
                        <div class="tab-pane" id="vistos">
                            <table class="table stripe compact" id="table-contas-bloqueadas-vistos" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>Emp</th>
                                        <th>Pessoa</th>
                                        <th>Docto</th>
                                        <th>Parcelas</th>
                                        <th>Total</th>
                                        <th>Emissão</th>
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
                                        <th>Parcelas</th>
                                        <th>Total</th>
                                        <th>Emissão</th>
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
            <table class="table row-border" id="conta-{{ NR_LANCAMENTO }}" style="width:80%; font-size:12px" >
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

    <script id="details-centro-resultado" type="text/x-handlebars-template">
        @verbatim
            <div class="label label-info">{{ DS_TIPOCONTA }}</div>
            <table class="table row-border" id="conta-{{ NR_LANCAMENTO }}" style="width:80%; font-size:12px" >
                <thead>
                    <tr>
                        <th>Historico</th>
                        <th>Valor</th>                       
                    </tr>
                </thead>
                
            </table>
        @endverbatim
    </script>

    <script id="details-vencimento" type="text/x-handlebars-template">
        @verbatim
            <div class="label label-info">{{ DS_TIPOCONTA }}</div>
            <table class="table row-border" id="conta-{{ NR_LANCAMENTO }}" style="width:80%; font-size:12px" >
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
        var tableContas;
        var template_historico = Handlebars.compile($("#details-item-historico").html());
        var template_motivo = Handlebars.compile($("#details-motivo").html());
        var template_vencimento = Handlebars.compile($("#details-vencimento").html());
        var templatecentro_resultado = Handlebars.compile($("#details-centro-resultado").html());

        tableContas = initableContas('table-contas-bloqueadas-pendentes', 'N');


        //Cliques nas tabs
        $('.nav-tabs a[href="#pendentes"]').on('click', function() {
            $('#table-contas-bloqueadas-pendentes').DataTable().destroy();
            tableContas = initableContas('table-contas-bloqueadas-pendentes', 'N');
        });

        $('.nav-tabs a[href="#vistos"]').on('click', function() {
            $('#table-contas-bloqueadas-vistos').DataTable().destroy();
            tableContas = initableContas('table-contas-bloqueadas-vistos', 'S');
        });


        $('tbody').on('click', '.details-control', function() {
            var tr = $(this).closest('tr');
            var row = tableContas.row(tr);

            var tableId = 'conta-' + row.data().NR_LANCAMENTO;
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                $(this).removeClass('fa-minus-circle').addClass('fa-plus-circle');
                $('.details-motivo').removeClass('btn-close').addClass('btn-open');
            } else {
                // Open this row
                row.child(template_historico(row.data())).show();
                initTableHistorico(tableId, row.data());
                // console.log(row.data());
                tr.addClass('shown');
                $(this).removeClass('fa-plus-circle').addClass('fa-minus-circle');
                tr.next().find('td').addClass('no-padding bg-gray-light')
                $('.details-motivo').removeClass('btn-close').addClass('btn-open');
            }
        });

        $('tbody').on('click', '.details-motivo', function() {
            var tr = $(this).closest('tr');
            var row = tableContas.row(tr);

            var nm_pessoa = row.data().NM_PESSOA;

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                $(this).removeClass('btn-close').addClass('btn-open');
                $('.details-control').removeClass('fa-minus-circle').addClass('fa-plus-circle');

            } else {
                // Open this row
                row.child(template_motivo(row.data())).show();
                // console.log(row.data());
                tr.addClass('shown');
                tr.next().find('td').addClass('no-padding bg-gray-light')
                $('.details-control').removeClass('fa-minus-circle').addClass('fa-plus-circle');
                $(this).removeClass('btn-open').addClass('btn-close');
            }
        });

        $('.btn-aproover').click(function() {
            var dsLiberacao = $('#liberacao').val(); // Captura o valor do textarea       

            if (dsLiberacao == "") {
                alert("Motivo da liberação/Bloqueio e obrigatorio!");
                return false;
            }
            // libera a conta para pagamento
            loadData('N', dsLiberacao)
        });

        $('.btn-blocker').click(function() {
            var dsLiberacao = $('#liberacao').val(); // Captura o valor do textarea     
            if (dsLiberacao == "") {
                alert("Motivo da liberação/Bloqueio e obrigatorio!");
                return false;
            }
            // Mantem a conta bloqueada, mas muda a conta a aba bloqueadas pendentes.
            loadData('S', dsLiberacao)
        });

        function loadData(status, dsLiberacao) {
            var rows = tableContas.rows({
                selected: true
            }).data().toArray();

            var contas = [];

            if (rows.length > 0) {
                rows.forEach(function(row) {
                    contas.push({
                        nr_lancamento: row.NR_LANCAMENTO,
                        cd_empresa: row.CD_EMPRESA,
                        status: status,
                        ds_liberacao: row.DS_LIBERACAO,
                    });

                });
                $.ajax({
                    method: "get",
                    url: "{{ route('contas-bloqueadas.update') }}",
                    data: {
                        contas: contas,
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
                        $('#table-contas-bloqueadas-pendentes').DataTable().ajax.reload();
                        $('#table-contas-bloqueadas-vistos').DataTable().ajax.reload();
                    }
                });

            } else {
                alert('Nenhuma conta foi selecionada!');
            }
        }

        function initableContas(tableID, status) {
            tableContas = $('#' + tableID).DataTable({
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
                select: {
                    style: 'multi',
                },
                ajax: {
                    url: '{{ route('contas-bloqueadas.list') }}',
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
                        visible: false,
                    },
                    {
                        data: 'VL_DOCUMENTO',
                        name: 'VL_DOCUMENTO',
                        visible: true,
                    },
                    {
                        data: 'DT_LANCAMENTO',
                        name: 'DT_LANCAMENTO',
                        visible: true,
                    },
                    {
                        data: 'DS_LIBERACAO',
                        name: 'DS_LIBERACAO',
                        visible: false,
                    },
                ],
                columnDefs: [{
                    targets: [7],
                    render: $.fn.dataTable.render.moment('DD/MM/YYYY')
                }, {
                    targets: [6],
                    render: $.fn.dataTable.render.number('.', ',', 2),
                }],
                order: [
                    [2, 'asc']
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
            return tableContas;
        };

        function initTableHistorico(tableId, data) {
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
                    method: "POST",
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


                    },
                    {
                        data: 'NR_PARCELA',
                        name: 'NR_PARCELA',
                        width: '1%'
                    },
                    {
                        data: 'DT_LANCAMENTO',
                        name: 'DT_LANCAMENTO',
                    },
                    {
                        data: 'DT_VENCIMENTO',
                        name: 'DT_VENCIMENTO',
                    },
                    {
                        data: 'VL_DOCUMENTO',
                        name: 'VL_DOCUMENTO'

                    }
                ],
                columnDefs: [{
                        targets: [2, 3],
                        render: $.fn.dataTable.render.moment('DD/MM/YYYY')
                    },
                    {
                        targets: [4],
                        render: $.fn.dataTable.render.number('.', ',', 2),
                    }
                ],
                "footerCallback": function(tfoot, data, start, end, display) {
                    $(tfoot).find('td').removeClass('no-padding');
                }

            });
        };
    </script>
@endsection
