@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-archive"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Soma Geral</span>
                        <span class="info-box-number" id="soma-geral">
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($clientesInadimplentes as $c)
                                @php $total += $c->VL_TOTAL @endphp
                            @endforeach
                            R$ {{ number_format($total, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-file-text-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Maior divida</span>
                        <span class="info-box-number" id="maior-divida">
                            R$ {{ number_format($clientesInadimplentes[0]->VL_TOTAL, 2, ',', '.') }}
                        </span>
                        <span class="progress-description" id="maior-pessoa">
                            {{ $clientesInadimplentes[0]->NM_PESSOA }}
                        </span>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-building"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Júridico</span>
                        <span class="info-box-number">
                            @php
                                $total_juridico = 0;
                            @endphp
                            @foreach ($clientesInadimplentes as $c)
                                @if ($c->STATUS == 'J')
                                    @php $total_juridico += $c->VL_TOTAL @endphp
                                @endif
                            @endforeach
                            R$ {{ number_format($total_juridico, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-clock-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Atraso</span>
                        <span class="info-box-number">
                            @php
                                $total_atraso = 0;
                            @endphp
                            @foreach ($clientesInadimplentes as $c)
                                @if ($c->STATUS == 'N')
                                    @php $total_atraso += $c->VL_TOTAL @endphp
                                @endif
                            @endforeach
                            R$ {{ number_format($total_atraso, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Filtros:</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="cd_empresa">Empresa</label>
                                <select class="form-control" name="cd_empresa" id="cd_empresa" style="width: 100%;">
                                    <option value="0">Selecione uma empresa</option>
                                    @foreach ($empresa as $e)
                                        <option value="{{ $e->CD_EMPRESA }}">{{ $e->NM_EMPRESA }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @role('admin|gerencia|diretoria')
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="cd_regiao">Região</label>
                                    <select class="form-control" name="cd_regiao" id="cd_regiao" multiple="multiple"
                                        style="width: 100%;">
                                        @foreach ($regiao as $r)
                                            <option value="{{ $r->CD_REGIAOCOMERCIAL }}">{{ $r->DS_REGIAOCOMERCIAL }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endrole
                        @role('admin|diretoria')
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="cd_area">Área</label>
                                    <select class="form-control" name="cd_area" id="cd_area" style="width: 100%;"
                                        multiple="multiple">
                                        @foreach ($area as $a)
                                            <option value="{{ $a->CD_AREACOMERCIAL }}">{{ $a->DS_AREACOMERCIAL }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endrole
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right btn-submit">Consultar</button>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title title-relatorio">Relatório Inadimplentes
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="list-cobranca">
                            <table class="table table-striped" id="table-rel-cobranca" style="width: 100%">
                                <thead style="font-size: 12px">
                                    <tr>
                                        <th>Emitente</th>
                                        <th>Cnpj/Cpf</th>
                                        <th>Cliente</th>
                                        <th>Area</th>
                                        <th>Região</th>
                                        <th>Valor Total</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 11px">
                                    @foreach ($clientesInadimplentes as $c)
                                        <tr>
                                            <td>{{ $c->CD_EMPRESA . ' - ' . $c->NM_PESSOAEMP }}</td>
                                            <td>{{ $c->NR_CNPJCPF }}</td>
                                            <td>{{ $c->NM_PESSOA }}</td>
                                            <td>{{ $c->AREA }}</td>
                                            <td>{{ $c->DS_REGIAOQ }}</td>
                                            <td>{{ $c->VL_TOTAL }}</td>
                                            <td>
                                                <button class="btn btn-xs btn-info btn-detalhar"
                                                    data-empresa="{{ $c->CD_EMPRESA }}"
                                                    data-pessoa="{{ $c->NR_CNPJCPF }}">Detalhar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-detalhar" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Cliente</h4>
                    </div>
                    <div class="modal-body" style="overflow-x: auto;">
                        <div id="table-list-cobranca" class="table-responsive">

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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#cd_empresa').select2();
            $('#cd_regiao').select2({
                //placeholder: "Selecione uma ou mais Regiões",
                multiple: true,
                allowClear: true
            });
            $('#cd_area').select2({
                //placeholder: "Selecione uma ou mais Áreas",
                multiple: true,
                allowClear: true
            });
            $('.select2-search__field').css({
                'width': '100%'
            });
            InitDatatable();
            $('.btn-submit').click(function() {
                let cd_empresa = $('#cd_empresa').val();
                let cd_regiao = $('#cd_regiao').val();
                let cd_area = $('#cd_area').val();
                var ds_empresa = $("#cd_empresa option:selected").text();
                if (cd_empresa == 0) {
                    $('#cd_empresa').tooltip({
                        title: 'Obrigatório',
                        placement: ($(window).width() < 1368 ? "bottom" : "top"),
                        //container: 'body'
                    }).tooltip('show');
                    return false;
                }
                $('#cd_empresa').tooltip('hide');
                $.ajax({
                    url: "{{ route('get-cobranca-filtro') }}",
                    method: "GET",
                    data: {
                        cd_empresa: cd_empresa,
                        cd_regiao: cd_regiao,
                        cd_area: cd_area
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                        $("#table-rel-cobranca").DataTable().destroy();
                    },
                    success: function(result) {
                        $("#table-rel-cobranca").remove();
                        $(".list-cobranca").append(result['html']);
                        $("#loading").addClass('hidden');
                        $(".title-relatorio").text('Relatório: ' + ds_empresa);
                        $("#maior-divida").text('R$ ' + result['divida'][0]);
                        $("#maior-pessoa").text(result['divida'][1]);
                        $("#soma-geral").text(result['total'])
                        InitDatatable();
                    }
                })
            });
            $(document).on('click', '.btn-detalhar', function(e) {
                let cpfcnpj = $(this).data('pessoa');
                let cd_empresa = $(this).data('empresa');
                $.ajax({
                    url: "{{ route('get-cobranca-filtro-cnpj') }}",
                    method: 'get',
                    data: {
                        cpfcnpj: cpfcnpj,
                        cd_empresa: cd_empresa
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                        $("#table-cobranca-cnpj").DataTable().clear().destroy();
                        $("#table-cobranca-cnpj").remove();
                    },
                    success: function(result) {
                        $("#loading").addClass('hidden');
                        $("#table-list-cobranca").append(result['html']);
                        $(".modal-title").text(result['nm_pessoa']);
                        $('#modal-detalhar').modal('show');
                        $("#table-cobranca-cnpj").DataTable({
                            responsive: true,
                            language: {
                                url: "http://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                                decimal: ",",
                                thousands: "."
                            },
                            pageLength: 100,
                            columnDefs: [{
                                targets: [1, 2],
                                visible: false,
                            }, ],
                            //processing: true,                            
                        });
                    }
                });
            });



            function InitDatatable() {
                $('#table-rel-cobranca').DataTable({
                    language: {
                        url: "http://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                        decimal: ",",
                        thousands: "."
                    },
                    rowReorder: {
                        selector: 'td:nth-child(2)'
                    },
                    //processing: true,
                    responsive: true,
                    //serverSide: false,
                    autoWidth: false,
                    lengthMenu: [
                        [25, 50, 75, -1],
                        [25, 50, 75, "All"]
                    ],
                    pageLength: 25,
                    dom: 'Blfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    columnDefs: [{
                            "width": "130px",
                            "targets": 0
                        },
                        {
                            "width": "80px",
                            "targets": 1
                        },
                        {
                            targets: 5,
                            render: $.fn.dataTable.render.number('.', ',', 2, 'R$ ')
                        }
                    ],
                    "order": [
                        [5, "desc"]
                    ]
                });
            }
        });
    </script>
@endsection
