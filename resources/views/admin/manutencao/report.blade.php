@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Buscar por Empresa/Período:</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="col-md-5">
                            <div class="form-group">
                                <select class="form-control select2" name="cd_empresa" id="cd_empresa" style="width: 100%;">
                                    <option value="0" selected="selected">TODAS EMPRESAS</option>
                                    <option value="1">AM MORENO PNEUS LTDA</option>
                                    <option value="21">EMAX RECAPAGENS EIRELI</option>
                                    <option value="3">SUPER RODAS - CAMPINA</option>
                                    <option value="4">SUPER RODAS - GUARAPUAVA</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            @includeIf('admin.master.filtro-data')
                        </div>
                        <div class="col-md-2" align="center">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block" id="submit-seach">
                                    Pesquisar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-primary" id="maq-defeito">
                    <div class="box-header with-border">
                        <h3 class="box-title">Maquinas com mais Paradas</h3>
                        <div class="box-tools pull-right">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" class="minimal" name="status"
                                        onclick="ClickSelect('#table-maq-defeito')" value="S" checked>
                                    Sim
                                </label>
                                <label>
                                    <input type="checkbox" class="minimal" name="status"
                                        onclick="ClickSelect('#table-maq-defeito')" value="N" checked>
                                    Não
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered" id="table-maq-defeito" style="width: 100%; font-size: 12px">
                            <thead>
                                <tr>
                                    <th>Emp</th>
                                    <th>Cód.</th>
                                    <th>Maquina</th>
                                    <th>Qtd</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-primary" id="time-ticket">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tempo de Chamados</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered" id="table-time-tickets" style="width: 100%; font-size: 12px">
                            <thead>
                                <tr>
                                    <th>Emp</th>
                                    <th>Chamado</th>
                                    <th>Dias</th>
                                    <th>Horas</th>
                                    <th>Min</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-primary" id="time-ticket-average">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tempo Médio Chamados</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered" id="table-ticket-average" style="width: 100%; font-size: 12px">
                            <thead>
                                <tr>
                                    <th>Emp</th>                                    
                                    <th>Média(Min)</th>                                    
                                    <th>Status</th>
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
    <script>
        var inicioData = 0;
        var fimData = 0;
        var empresa = 0;

        $('#daterange').daterangepicker({
            autoUpdateInput: false,
        });
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm') + ' - ' + picker.endDate.format(
                'DD/MM/YYYY HH:mm'));
            inicioData = picker.startDate.format('MM/DD/YYYY HH:mm');
            fimData = picker.endDate.format('MM/DD/YYYY HH:mm');
        });
        $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val("");
            inicioData = 0;
            fimData = 0;
        });

        var status = ArrCheck(); // retorna os itens selecionados função está em script...    
        statusArray = status.replace("[", "").replace("]", "").split(','); // converte para Array as selecões

        initTable(statusArray, inicioData, fimData, empresa);
        initTableTimeTickets(inicioData, fimData, empresa);
        initTableAverageTickets(inicioData, fimData, empresa);
        
        $('#submit-seach').click(function(){
            var empresa = $('#cd_empresa').val();
            $('#table-maq-defeito').DataTable().clear().destroy();  
            initTable(statusArray, inicioData, fimData, empresa);
            $('#table-time-tickets').DataTable().clear().destroy();
            initTableTimeTickets(inicioData, fimData, empresa);
            $('#table-ticket-average').DataTable().clear().destroy();
            initTableAverageTickets(inicioData, fimData, empresa)
        })

        function initTable(status, inicioData, fimData, empresa) {
            $('#table-maq-defeito').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                searching: false,
                bPaginate: false,
                processing: false,

                ajax: {
                    url: "{{ route('manutencao.problem') }}",
                    data: {
                        status: status,
                        inicio: inicioData,
                        fim: fimData,
                        empresa: empresa
                    }
                },
                columns: [{
                        data: 'cd_empresa',
                        name: 'cd_empresa'
                    },
                    {
                        data: 'cd_barras',
                        name: 'cd_barras'
                    },
                    {
                        data: 'ds_maquina',
                        name: 'ds_maquina'
                    },
                    {
                        data: 'qtd',
                        name: 'qtd'
                    },
                ],
                columnDefs: [{
                        targets: [0,1],
                        width: '1%'
                    },
                    // {
                    //     targets: [1],
                    //     width: '1%'
                    // }

                ],
                order: [
                    [3, 'desc']
                ],
            });
        }

        function initTableTimeTickets(inicioData, fimData, empresa) {
            $('#table-time-tickets').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                searching: false,
                bPaginate: true,
                processing: false,
                info: false,
                ajax: {
                    url: "{{ route('manutencao.time-tickets') }}",
                    data: {
                        inicio: inicioData,
                        fim: fimData,
                        empresa: empresa
                    }
                },
                columns: [{
                        data: 'cd_empresa',
                        name: 'cd_empresa'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'dias',
                        name: 'dias'
                    },
                    {
                        data: 'horas',
                        name: 'horas'
                    },
                    {
                        data: 'minutos',
                        name: 'minutos'
                    },
                ],
                columnDefs: [{
                        targets: [0,1,2,3,4],
                        width: '1%'
                    }

                ],
                order: [
                    [1, 'desc']
                ],
            });
        }
        function initTableAverageTickets(inicioData, fimData, empresa) {
            $('#table-ticket-average').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                searching: false,
                bPaginate: false,
                info: false,
                processing: false,

                ajax: {
                    url: "{{ route('manutencao.average-tickets') }}",
                    data: {
                        inicio: inicioData,
                        fim: fimData,
                        empresa: empresa
                    }
                },
                columns: [{
                        data: 'cd_empresa',
                        name: 'cd_empresa'
                    },
                    {
                        data: 'espera_media',
                        name: 'espera_media'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    }
                ],
                // columnDefs: [{
                //         targets: [0,1,2,3,4],
                //         width: '1%'
                //     }

                // ],
                order: [
                    [1, 'desc']
                ],
            });
        }

    </script>
@endsection
