@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-4">
                <div class="box box-info collapsed-box">
                    <div class="box-header with-border">
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                        <h4 class="box-title">Atualizar Executores</h4>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <select class="form-control" name="local" id="local">
                                <option value="NORTE">Funcionarios Norte</option>
                                <option value="SUL">Funcionarios Sul</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-success mb-2 pull-right"
                            id="btn-search-executores">Atualizar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                @includeIf('admin.master.messages')
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left active"><a href="#operador-epi" data-toggle="tab" aria-expanded="true">Operador
                                x Epi</a>
                        </li>
                        <li class="pull-left"><a href="#relatorio-epi" data-toggle="tab" aria-expanded="false">Relatório</a>
                        </li>

                        {{-- <li class="header"><i class="fa fa-inbox"></i> Controle Epi</li> --}}
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="tab-pane active" id="operador-epi">
                            <div class="box-body" id="body-select">
                                @includeIf('admin.master.executores')
                                @includeIf('admin.master.etapas-produtivas')
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-success hidden" id="btn-save">Salvar</button>
                            </div>
                        </div>
                        <div class="tab-pane" id="relatorio-epi">
                            <div class="box-body">
                                @includeIf('admin.master.executores')
                                @includeIf('admin.master.etapas-produtivas')
                                <div class="form-group">
                                    <label for="list-epis">Epis:</label>
                                    <select class="form-control select2" id="list-epis" style="width: 100%;">
                                        <option selected="selected" value="0">TODOS</option>
                                        @foreach ($epis as $e)
                                            <option value="{{ $e->id }}">{{ $e->ds_epi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="uso-epis">Uso Epis:</label>
                                    <select class="form-control multiple" id="uso-epis" style="width: 100%;">
                                        <option selected="selected" value="0">TODOS</option>
                                        <option value="CF">CONFORME</option>
                                        <option value="NF">NÃO CONFORME</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="localizacao">Rede:</label>
                                    <select class="form-control" id="localizacao" style="width: 100%;">
                                        <option selected="selected" value="0">LOCAL</option>
                                        <option value="SUL">SUL</option>
                                        <option value="NORTE">NORTE</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="empresa">Empresa:</label>
                                    <select class="form-control" id="empresa" style="width: 100%;">
                                        <option selected="selected" value="0">TODOS</option>
                                        @foreach ($empresas as $e)
                                            <option value="{{ $e->CD_EMPRESA}}">{{ $e->NM_EMPRESA }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="periodo">Periodo:</label>
                                    <input type="text" class="form-control pull-right" id="daterange" value=""
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-primary mb-2" id="btn-search">Consultar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 hidden" id="relatorio">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Relatório Epis</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered compact" id="table-controle-epi"
                            style="width:100%; font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Sq</th>
                                    <th>Empresa</th>
                                    <th>Executor</th>
                                    <th>Epi</th>
                                    <th>Etapa</th>
                                    <th>Uso</th>
                                    <th>Rede</th>
                                    <th>Registro</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var executor, etapa;
            $('.executor').select2();
            $('.etapas').select2();
            $('#empresa').select2();
            $('#list-epis').select2();
            // $('option[value=0]').text('teste');
            var inicioData = 0;
            var fimData = 0;
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
            $('#daterange').daterangepicker({
                //opens: 'left',
                autoUpdateInput: false,
                // timePicker: true,
                //timePickerIncrement: 30,
                // locale: {
                //     format: 'MM/DD/YYYY HH:mm',

                // }
            });
            $('.nav-tabs a[href="#relatorio-epi"]').on('click', function() {
                $('#relatorio-epi .executor option[value=0]').text('TODOS').trigger('change');
                $('#relatorio-epi .etapas option[value=0]').text('TODOS').trigger('change');
                $('#uso-epis').select2();
                $('.etapas').select2();
                $('.executor').select2();
                $('#localizacao').select2();
            });
            $('.nav-tabs a[href="#operador-epi"]').on('click', function() {
                $('#relatorio').addClass('hidden');
            });
            $("#operador-epi .etapas").change(function() {
                var id_etapa = $(this).val();
                $.ajax({
                    url: '{{ route('search-etapas-producao') }}',
                    method: 'GET',
                    data: {
                        idetapa: id_etapa,
                    },
                    beforeSend: function() {
                        $('#epis-etapa').remove();
                    },
                    success: function(result) {
                        $('#btn-save').removeClass('hidden');
                        $('#body-select').append(result.html);
                    }
                });
            });
            $('#btn-save').click(function() {
                var epis = new Array();
                $('input[name="epis[]"]:checked').each(function() {
                    epis.push($(this).val());
                });

                // let executor = $('.executor').val();
                // alert(executor);
                // return false;
                $.ajax({
                    url: '{{ route("save-epi-etapas-operador") }}',
                    method: 'GET',
                    data: {
                        executor: $('.executor').val(),
                        etapa: $('.etapas').val(),
                        epis: epis,
                    },
                    beforeSend: function() {
                        // $('#epis-etapa').remove();
                    },
                    success: function(result) {
                        if (result.error) {
                            msgToastr(result.error, 'error');
                        } else {
                            msgToastr(result.success, 'success');
                        }
                    }
                });
            });
            $('#btn-search').click(function() {
                $('#table-controle-epi').DataTable().destroy();
                $('#relatorio').removeClass('hidden');
                var etapa, epis, uso, empresa;
                etapa = $('#relatorio-epi .etapas').val();
                epis = $('#list-epis').val();
                executor = $('#relatorio-epi .executor').val();
                uso = $('#uso-epis').val();
                localizacao = $('#localizacao').val();
                empresa = $('#empresa').val();
                $('#table-controle-epi').DataTable({
                    responsive: true,
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    // processing: true,
                    //serverSide: true,
                    autoWidth: false,
                    "pageLength": 25,
                    ajax: {
                        url: "{{ route('get-uso-epis') }}",
                        data: {
                            'etapa': etapa,
                            'epis': epis,
                            'executor': executor,
                            'uso': uso,
                            'localizacao': localizacao,
                            'data_ini': inicioData,
                            'data_fim': fimData,
                            'empresa': empresa
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'ds_local',
                            name: 'ds_local',
                        },
                        {
                            data: 'nmexecutor',
                            name: 'nmexecutor'
                        },
                        {
                            data: 'ds_epi',
                            name: 'ds_epi'
                        },
                        {
                            data: 'dsetapaempresa',
                            name: 'dsetapaempresa'
                        },
                        {
                            data: 'uso',
                            name: 'uso'
                        },
                        {
                            data: 'localizacao',
                            name: 'localizacao'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        }
                    ],
                    columnDefs: [{
                            width: '1%',
                            targets: 0
                        },
                        {
                            width: '2%',
                            targets: [5, 6]
                        },
                    ],
                    dom: 'Blfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                });
            });
            $('#btn-search-executores').click(function() {

                let local = $('#local').val();               
                var url = "{{ route('get-buscar-executor', ':local') }}";
                url = url.replace(':local', local);                
                $.ajax({
                    method: "GET",
                    url: url,
                    beforeSend: function() {
                        $('#loading').removeClass('hidden');
                    },
                    success: function(result) {
                        $('#loading').addClass('hidden');
                        msgToastr(result.success, 'success');
                    }
                });
            });
        });
    </script>
@endsection
