@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @includeIf('admin.master.filtro-por-empresa')
            </div>
            <div class="col-md-12">
                @includeIf('admin.master.messages')
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#boletos" data-toggle="tab" aria-expanded="true">Boletos</a>
                        </li>
                        <li class="">
                            <a href="#notafiscal" data-toggle="tab" aria-expanded="false">Nota Fiscal</a>
                        </li>
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="tab-pane active" id="boletos">
                            <div class="box-body">
                                <div style="padding-bottom: 15px">
                                    <div class="box-header with-border" style="border-bottom: 1px solid #d1d1d1;">
                                        <h3 class="box-title" style="text-align: center">Pagamentos Pendentes
                                        </h3>
                                    </div>
                                </div>
                                <div id="info-bl">
                                    <p>Use o filtro acima para obter os <b>boletos pendentes</b>!</p>
                                </div>
                                <table class="table compact hidden stripe" id="table-tickets-pendents" style="width: 100%">
                                    <thead>
                                        <th>Beneficiário</th>
                                        <th>Cnpj</th>
                                        <th>Cliente</th>
                                        <th>Documento</th>
                                        <th>Valor</th>
                                        <th style="width: 80px">Vencimento</th>
                                        <th style="width: 50px">Status</th>
                                        <th style="width: 25px">2º Via</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="notafiscal">
                            <div class="box-body">
                                <div style="padding-bottom: 15px">
                                    <div class="box-header with-border" style="border-bottom: 1px solid #d1d1d1;">
                                        <h3 class="box-title" style="text-align: center;">Notas Fiscais Emitidas
                                        </h3>
                                    </div>
                                </div>
                                <div id="info-nf">
                                    <p>Use o filtro acima para obter as <b>notas emitidas</b>!
                                    </p>
                                </div>
                                <table class="table compact hidden stripe" id="table-nota-fiscal" style="width: 100%">
                                    <thead>
                                        <th>Emissor</th>
                                        <th style="width: 80px">Dt Emissão</th>
                                        <th style="width: 50px">Nº Nota</th>
                                        <th style="width: 25px">Série</th>
                                        <th>Pessoa</th>
                                        <th>Valor</th>
                                        <th class="text-center"></th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade in" id="modal-fidic" style="display: none; padding-right: 17px;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Boleto Fidic</h4>
                    </div>
                    <div class="modal-body">
                        <p>Esse boleto pertence a uma fidic, essa empresa envia boleto através dos correios! Para mais
                            informações ligue no telefone <b>41 3162-7921</b> ou Através do Whatsapp.</p>
                        <div class="text-center">
                            <a href="https://api.whatsapp.com/send?phone=554138887921" class="btn btn-success">Whatsapp</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="https://cdn.datatables.net/plug-ins/1.10.19/dataRender/datetime.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var inicioData = 0,
                fimData = 0,
                emp = 0;
            _token = $('#token').val();
            $('#form-group-date').addClass('hidden');
            $('.periodo').daterangepicker({
                autoUpdateInput: false,
                "opens": "left",
                startDate: moment().startOf('month').format('MM/DD/YYYY'),
                endDate: moment().format('L')
            });
            $('.periodo').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate
                    .format(
                        'DD/MM/YYYY'));
                inicioData = picker.startDate.format('MM/DD/YYYY');
                fimData = picker.endDate.format('MM/DD/YYYY');
            });
            $('.periodo').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val("");
                inicioData = 0;
                fimData = 0;
            });
            $('.empresas').select2({
                placeholder: "Selecione uma empresa",
                allowClear: true,
                ajax: {
                    url: '{{ route('search-empresa-fiscal') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.ds_local,
                                    id: item.cd_empresa,
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            $('body').on('click', '#btn-fidic', function(e) {
                $('#modal-fidic').modal('show');
            });
            //Gera o boleto
            $('body').on('click', '#btnDoc', function(e) {
                var documento = $(this).data('documento');
                console.log(documento);
                $.ajax({
                    type: "GET",
                    url: "{{ route('client-save-tickets') }}",
                    data: {
                        doc: documento
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');
                        if (response.error) {
                            alert(response.error);
                            return false;
                        } else {
                            response;
                            // window.open(response.url, '_blank');   
                        }

                    }
                });
            });
            $('.nav-tabs a[href="#notafiscal"]').on('click', function() {
                $('#form-group-date').removeClass('hidden');
                $('#btn-search-bol').remove();
                $('#btn-action').append(
                    '<button type="button" class="btn btn-success btn-block pull-right" id="btn-search-nf">Buscar NF</button>'
                );
            });
            $('.nav-tabs a[href="#boletos"]').on('click', function() {
                $('#form-group-date').addClass('hidden');
                $('#btn-search-nf').remove();
                $('#btn-action').append(
                    '<button type="button" class="btn btn-success btn-block pull-right" id="btn-search-bol">Buscar Boleto</button>'
                );
            });
            $('body').on('click', '#btn-search-nf', function(e) {
                emp = $('#empresas').val();
                if (inicioData == "") {
                    msg('Período deve ser preenchida!', 'alert-warning');
                    return false;
                } else if (emp == null) {
                    msg('Selecione uma empresa!', 'alert-warning');
                    return false;
                }
                $('#info-nf').addClass('hidden');
                $('#table-nota-fiscal').removeClass('hidden');
                $('#table-nota-fiscal').DataTable().destroy();
                initTableNf(inicioData, fimData, emp);

            });
            $('body').on('click', '#btn-search-bol', function(e) {
                emp = $('#empresas').val();
                if (emp == null) {
                    msg('Selecione uma empresa!', 'alert-warning');
                    return false;
                }
                $('#info-bl').addClass('hidden');
                $('#table-tickets-pendents').removeClass('hidden');
                $('#table-tickets-pendents').DataTable().destroy();
                initTableBl(emp);
            });

            function initTableNf(dt_ini, dt_fim, emp) {
                $('#table-nota-fiscal').DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    responsive: true,
                    pagingType: "simple",
                    processing: false,
                    ajax: {
                        type: 'GET',
                        url: "{{ route('client-invoice') }}",
                        data: {
                            _token: _token,
                            dt_ini: dt_ini,
                            dt_fim: dt_fim,
                            emp,
                        }
                    },
                    columns: [{
                            data: 'EMITENTE',
                            name: 'EMITENTE'
                        },
                        {
                            data: 'DS_DTEMISSAO',
                            name: 'DS_DTEMISSAO'
                        },
                        {
                            data: 'NR_NOTASERVICO',
                            name: 'NR_NOTASERVICO'
                        },
                        {
                            data: 'CD_SERIE',
                            name: 'CD_SERIE'
                        },
                        {
                            data: 'NM_PESSOA',
                            name: 'NM_PESSOA'
                        },
                        {
                            data: 'valor_nf',
                            name: 'valor_nf'
                        },
                        {
                            data: 'action',
                            name: 'action',
                        },
                    ],
                    columnDefs: [{
                            targets: [1],
                            render: $.fn.dataTable.render.moment('DD/MM/YYYY'),
                            width: '1%'
                        },
                        {
                            targets: 5,
                            render: $.fn.dataTable.render.number('.', ',', 2, 'R$ '),
                            width: '1%'
                        },
                    ],
                    order: [0, 'desc']
                });
            }

            function initTableBl(emp) {
                $('#table-tickets-pendents').DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    responsive: true,
                    pagingType: "simple",
                    processing: false,
                    ajax: {
                        type: "GET",
                        url: "{{ route('client.tickets-pendents-enterprise') }}",
                        data: {
                            _token: _token,
                            emp,
                        }
                    },
                    columns: [{
                            data: 'EMPRESA',
                            name: 'EMPRESA',
                        },
                        {
                            data: 'NR_CNPJCPF',
                            name: 'NR_CNPJCPF',
                        },
                        {
                            data: 'PESSOA',
                            name: 'PESSOA',
                        },
                        {
                            data: 'NR_DOCUMENTO',
                            name: 'NR_DOCUMENTO',
                        },
                        {
                            data: 'valor_nf',
                            name: 'valor_nf',
                        },
                        {
                            data: 'DT_VENCIMENTO',
                            name: 'DT_VENCIMENTO',
                        },
                        {
                            data: 'STATUS',
                            name: 'STATUS',
                        },
                        {
                            data: 'action',
                            name: 'action',
                        },
                    ],
                    columnDefs: [{
                            targets: [5],
                            render: $.fn.dataTable.render.moment('DD/MM/YYYY'),
                            width: '1%'
                        },
                        {
                            targets: 4,
                            render: $.fn.dataTable.render.number('.', ',', 2, 'R$ '),
                            width: '1%'
                        },
                        {
                            targets: 2,
                            width: '20%'
                        },
                        {
                            targets: 3,
                            width: '1%'
                        }

                    ],
                    order: [5, 'asc']

                });
            }
        });
    </script>
@endsection
