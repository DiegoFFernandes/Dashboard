@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info collapsed-box">
                    <div class="box-header with-border">
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-plus"></i>
                            </button>
                        </div>
                        <h4 class="box-title">Buscar Pneus para Envio</h4>
                    </div>
                    <div class="box-body">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-building"></i></span>
                                    <select class="form-control select2" id="empresas" style="width: 100%;">
                                        <option selected="selected" value="0">Selecione uma empresa</option>
                                        @foreach ($empresas as $e)
                                            <option value="{{ $e->cd_empresa }}">{{ $e->ds_local }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="daterange" value=""
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success mb-2" id="btn-search">Buscar</button>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                @includeIf('admin.master.messages')
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left active"><a href="#pneus-bgw" data-toggle="tab" aria-expanded="true">Aguardando
                                Envio</a>
                        </li>
                        <li class="pull-left"><a href="#pneus-bgw-enviados" data-toggle="tab"
                                aria-expanded="false">Enviados</a>
                        </li>
                        <li class="pull-left"><a href="#divergencias" data-toggle="tab"
                                aria-expanded="false">Divergências</a>
                        </li>
                        <li class="pull-left"><a href="#logs-bgw" data-toggle="tab" aria-expanded="false">Logs
                                Transmissão</a>
                        </li>
                        <li class="header"><i class="fa fa-inbox"></i> Pneus Ouro - BGW</li>
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="tab-pane active" id="pneus-bgw">
                            <table class="table table-striped table-bordered compact" id="table-bgw"
                                style="width:100%; font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>Ordem</th>
                                        <th>Cliente</th>
                                        <th>Nota</th>
                                        <th>Medida</th>
                                        <th>Serie</th>
                                        <th>Fogo</th>
                                        <th>Dot</th>
                                        <th>Desenho</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Ciclo</th>
                                        <th>Preço</th>
                                        <th>Exp.</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="mx-auto">
                                <button class="btn btn-block btn-success" id="pross-pneus">Processar Pneus</button>
                            </div>
                        </div>
                        <div class="tab-pane log-bgw" id="logs-bgw">
                        </div>
                        <div class="tab-pane" id="pneus-bgw-enviados">
                            <table class="table table-striped table-bordered compact" id="table-bgw-enviados"
                                style="width:100%; font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>Ordem</th>
                                        <th>Cliente</th>
                                        <th>Nota</th>
                                        <th>Medida</th>
                                        <th>Serie</th>
                                        <th>Fogo</th>
                                        <th>Dot</th>
                                        <th>Desenho</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Ciclo</th>
                                        <th>Preço</th>
                                        <th>Exp.</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="divergencias">
                            <table class="table table-striped table-bordered compact" id="table-bgw-divergencias"
                                style="width:100%; font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>Ordem</th>
                                        <th>Cliente</th>
                                        <th>Nota</th>
                                        <th>Medida</th>
                                        <th>Serie</th>
                                        <th>Fogo</th>
                                        <th>Dot</th>
                                        <th>Desenho</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Ciclo</th>
                                        <th>Preço</th>
                                        <th>Exp.</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- Edit Modal -->
            <div class="modal" id="Editar">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Editar Informações Pneus</h4>
                            <button type="button" class="close modelClose" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Modelo Atual</label>
                                        <input type="text" id="modelo-atual" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Modelo Novo</label>
                                        <select data-width="100%" class="form-control" id="modelo_novo">
                                            <option value="0">Selecione modelo para atualizar</option>
                                            @foreach ($modelo as $m)
                                                <option value="{{ $m->cd_modelobandag }}">{{ $m->dsmodelo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Medida Atual</label>
                                        <input type="text" id="medida-atual" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Medida Nova</label>
                                        <select data-width="100%" class="form-control" id="medida_novo">
                                            <option value="0">Selecione medida para atualizar</option>
                                            @foreach ($medida as $m)
                                                <option value="{{ $m->cd_medidabandag }}">{{ $m->dsmedida }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="SubmitEdit">Atualizar</button>
                            <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit Modal -->
            <div class="modal" id="falhas">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Informações Pneus - Divergência</h4>
                            <button type="button" class="close modelClose" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Empresa</label>
                                        <input type="text" id="diver-empresa" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ordem:</label>
                                        <input type="text" id="diver-ordem" class="form-control" disabled>
                                    </div>
                                </div>                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Pessoa:</label>
                                        <input type="text" id="diver-pessoa" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nota:</label>
                                        <input type="text" id="diver-nota" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Data Emissão:</label>
                                        <input type="text" id="diver-emissao" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Ocorrência:</label>
                                        <textarea class="form-control" type="textarea" id="ocorrencia" rows="7" disabled></textarea>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">                            
                            <button type="button" class="btn btn-primary modelClose" data-dismiss="modal">Sair</button>
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
            $('#btn-search').click(function() {
                var empresa = $('#empresas').val();
                if (inicioData == 0 || empresa == '') {
                    alert('Empresa e Periodo deve ser informado!')
                    return false;
                }
                $.ajax({
                    url: "{{ route('api-new-age-import-pneus') }}",
                    method: "GET",
                    data: {
                        empresa: empresa,
                        inicio_data: inicioData,
                        fim_data: fimData,
                    },
                    beforeSend: function() {
                        // $("#table-search").DataTable().destroy();
                        $("#loading").removeClass('hidden');
                    },
                    success: function(result) {
                        $("#loading").addClass('hidden');
                        $('#table-bgw').DataTable().ajax.reload();
                    }
                });
            });
            $('#medida_novo').select2();
            $('#modelo_novo').select2();
            $('#empresas').select2();
            initTable('table-bgw', 'N')
            $('#pross-pneus').click(function() {
                $.ajax({
                    url: '{{ route('NewAgecallXmlProcess') }}',
                    method: "GET",
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                        $("#pross-pneus").text('Processando...')
                    },
                    success: function(result) {
                        $("#pross-pneus").text('Processar Pneus')
                        $("#loading").addClass('hidden');
                        // console.log(result);
                        $("#table-log").remove();
                        $(".log-bgw").append(result);
                        $('.nav-tabs a[href="#logs-bgw"]').tab('show')
                        $('#table-log1').DataTable({
                            scrollY: "300px",
                            scrollX: true,
                            scrollCollapse: true,
                            paging: false,
                            columnDefs: [{
                                width: '20%',
                                targets: 2
                            }],
                            fixedColumns: true
                        });
                    }

                });
            });
            $('.nav-tabs a[href="#pneus-bgw-enviados"]').on('click', function() {
                $('#table-bgw-enviados').DataTable().destroy();
                initTable('table-bgw-enviados', 'S');
            });
            $('.nav-tabs a[href="#divergencias"]').on('click', function() {
                $('#table-bgw-divergencias').DataTable().destroy();
                initTable('table-bgw-divergencias', 'C');
            });
            $('.nav-tabs a[href="#pneus-bgw"]').on('click', function() {
                $('#table-bgw').DataTable().ajax.reload();
            });
            function initTable(tableId, data) {
                $('#' + tableId).DataTable({
                    responsive: true,
                    language: {
                        url: "http://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    // processing: true,
                    //serverSide: true,
                    autoWidth: false,
                    "pageLength": 25,
                    ajax: {
                        url: "{{ route('get-pneus-bandag') }}",
                        data: {
                            'exportado': data
                        }
                    },
                    columns: [{

                            data: 'ORD_NUMERO',
                            name: 'ord_numero'
                        },
                        {
                            data: 'CLI_NOME',
                            name: 'cli_nome'
                        },
                        {
                            data: 'NUM_NF',
                            name: 'num_nf'
                        },
                        {
                            data: 'MEDIDA',
                            name: 'medida'
                        },
                        {
                            data: 'MATRICULA',
                            name: 'matricula'
                        },
                        {
                            data: 'FOGO',
                            name: 'fogo'
                        },
                        {
                            data: 'DOT',
                            name: 'dot'
                        },
                        {
                            data: 'DESENHOPNEU',
                            name: 'desenhopneu'
                        },
                        {
                            data: 'MARCA',
                            name: 'marca'
                        },
                        {
                            data: 'MODELO',
                            name: 'modelo'
                        },
                        {
                            data: 'COD_I_CICLO',
                            name: 'cod_i_ciclo'
                        },
                        {
                            data: 'PRECO',
                            name: 'preco'
                        },
                        {
                            data: 'EXPORTADO',
                            name: 'exportado'
                        },
                        {
                            data: 'Actions',
                            name: 'Actions',
                            orderable: false,
                            serachable: false,
                            sClass: 'text-center'
                        },
                    ],
                    columnDefs: [{
                        width: '20%',
                        targets: 1
                    }],
                });
            };
            var id, modelo_novo, ordem;
            $('body').on('click', '#getEdit', function(e) {
                e.preventDefault();
                $('.alert-danger').html('');
                $('.alert-danger').addClass('hidden');
                id = $(this).data('id');
                $('#modelo-atual').val($(this).data('modelo'));
                $('#medida-atual').val($(this).data('medida'));
                $('#Editar').modal('show');
            });
            $('body').on('click', '#getFalhas', function(e) {
                e.preventDefault();    
                ordem = $(this).data('ordem');    
                $.ajax({
                    url: '{{route("api-new-age-divergencia-pneus")}}',
                    method: 'GET',
                    data: {
                        ordem: ordem,
                    }, 
                    beforeSend: function(){
                        $("#loading").removeClass('hidden');
                    }, 
                    success: function(result){
                        $("#loading").addClass('hidden');
                        $('#diver-empresa').val(result[0]['CD_EMP']);
                        $('#diver-ordem').val(result[0]['ordem']);
                        $('#ocorrencia').val(result[0]['ocorrencia']);
                        $('#diver-nota').val(result[0]['NUM_NF']);
                        $('#diver-pessoa').val(result[0]['CLI_NOME']);
                        $('#diver-emissao').val(result[0]['DATA_NF']);
                    }
                });       
                $('#falhas').modal('show');
            });
            $('#SubmitEdit').on('click', function() {
                modelo_novo = $('#modelo_novo').val();
                medida_novo = $('#medida_novo').val();
                if (confirm('Deseja realmente atualizar?')) {
                    $.ajax({
                        url: "{{ route('api-new-age-edit-pneus') }}",
                        method: 'GET',
                        data: {
                            id: id,
                            modelo: modelo_novo,
                            medida: medida_novo,
                        },
                        beforeSend: function() {
                            $("#loading").removeClass('hidden');
                        },
                        success: function(result) {
                            $('#Editar').modal('hide')
                            $("#loading").addClass('hidden');
                            if (result.errors) {
                                alert(result.errors);
                            } else {
                                // alert(result.success);
                                setTimeout(function() {
                                    $(".alert").removeClass('hidden');
                                    $(".alert p").text(result.success);
                                }, 400);
                                window.setTimeout(function() {
                                    $(".alert").alert('close');
                                    $('#table-bgw').DataTable().ajax.reload();
                                }, 2000);
                            }
                        }
                    });
                } else {
                    return false;
                }
            });
        });
    </script>
@endsection
