@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Comissao Sobre Liquidação</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-xs btn-success" id="btn-liquidacao">Liquidação
                            </button>
                            <button type="button" class="btn btn-xs btn-primary" id="btn-faturamento">Faturamento
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        @csrf
                        <div class="col-md-12" style="background-color: #ecf0f5; padding-top:15px">

                            <div class="col-md-5">
                                <label for="">Empresa:</label>
                                <div class="form-group">
                                    @includeIf('admin.master.empresas')
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label for="" class="label-data">Data Liquidação:</label>
                                <div class="form-group">
                                    @includeIf('admin.master.filtro-data')
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Area Comercial:</label>
                                <div class="form-group">
                                    <select class="form-control select2" id="cd_areacomercial" style="width: 100%;">
                                        <option selected="selected">Selecione</option>
                                        @foreach ($area as $r)
                                            <option value="{{ $r['CD_AREACOMERCIAL'] }}">{{ $r['DS_AREACOMERCIAL'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Região Comercial:</label>
                                <div class="form-group">
                                    <select class="form-control select2" id="cd_regiaocomercial" style="width: 100%;">
                                        <option selected="selected">Selecione</option>
                                        @foreach ($regiao as $r)
                                            <option value="{{ $r['CD_REGIAOCOMERCIAL'] }}">{{ $r['DS_REGIAOCOMERCIAL'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Tipo Vendedor:</label>
                                <div class="form-group">
                                    <select class="form-control" name="tp_despesa" id="tp_despesa" style="width: 100%;"
                                        required>
                                        <option value="0" selected="selected">Selecione</option>
                                        <option value="C">Vendedor/Região Comercial</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Vendedor:</label>
                                <div class="form-group">
                                    <select class="form-control select2" id="cd_vendedor" style="width: 100%;">
                                        <option selected="selected">Selecione</option>
                                        @foreach ($vendedor as $v)
                                            <option value="{{ $v['CD_VENDEDOR'] }}">{{ $v['NM_PESSOA'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-success" id="listComissaoLiq">Listar Liquidação</button>
                        <button class="btn btn-primary hidden" id="listComissaoFat">Listar Faturamento</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body" style="padding: 0 10px">
                        <table class="table stripe compact" style="width:100%" id="table-comissao">
                            <thead>
                                <tr>
                                    <th>CD</th>
                                    {{-- <th>Emp</th>
                                    <th>Area</th>
                                    <th>Vendedor</th>
                                    <th>Região</th>
                                    <th>Emissão</th>
                                    <th>Pessoa</th>
                                    <th>Liquidação</th>
                                    <th>Nota</th>
                                    <th>Vl Comissão</th>
                                    <th>Ds Item</th> --}}
                                </tr>
                            </thead>
                            <tbody></tbody>
                            {{-- <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>Emp</th>
                                        <th>Area</th>
                                        <th>Vendedor</th>
                                        <th>Região</th>
                                        <th>Emissão</th>
                                        <th>Pessoa</th>
                                        <th>Liquidação</th>
                                        <th>Nota</th>
                                        <th>Vl Comissão</th>
                                        <th>Ds Item</th>
                                    </tr>
                                </tfoot> --}}
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>


    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    @includeIf('admin.master.datatables')
    <script type="text/javascript">
        var cd_empresa;
        $('#cd_empresa').select2();
        $('#cd_areacomercial').select2();
        $('#cd_regiaocomercial').select2();
        $('#cd_vendedor').select2();

        var inicioData = 0;
        var fimData = 0;
        $('#daterange').daterangepicker({
            autoUpdateInput: false,
        });
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                'DD/MM/YYYY'));
            inicioData = picker.startDate.format('MM/DD/YYYY');
            fimData = picker.endDate.format('MM/DD/YYYY');
        });
        $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val("");
            inicioData = 0;
            fimData = 0;
        });

        $('#btn-liquidacao').click(function() {
            $('.label-data').text('Data Liquidação:');
            $('#listComissaoFat').addClass('hidden');
            $('#listComissaoLiq').removeClass('hidden');
        });
        $('#btn-faturamento').click(function() {
            $('.label-data').text('Data Faturamento:');
            $('#listComissaoLiq').addClass('hidden');
            $('#listComissaoFat').removeClass('hidden');
        });

        $('#listComissaoLiq').click(function() {

            cd_empresa = $('#cd_empresa').val();

            $("#table-comissao").DataTable().clear().destroy();

            $("#table-comissao").DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                "pageLength": 50,
                // responsive: true,
                // pagingType: "simple",
                processing: false,
                layout: {
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                },
                ajax: {
                    method: "GET",
                    url: " {{ route('comissao-liquidacao.list') }}",
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        cd_empresa: cd_empresa,
                        data_ini: inicioData,
                        data_fim: fimData,
                    }
                },
                columns: [{
                    data: 'cd_empresa_new',
                    name: 'cd_empresa_new',
                }]
            });
        });
    </script>
@endsection