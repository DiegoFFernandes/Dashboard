@extends('admin.master.master')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Buscar por periodo:</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="col-md-6">
                            @includeIf('admin.master.empresas')
                        </div>
                        <div class="col-md-6">
                            @includeIf('admin.master.filtro-data')
                        </div>
                        <button type="submit" class="btn btn-success pull-right" id="submit-seach">
                            Buscar</button>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Trocas de Serviço</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                    class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table compact" id="table-troca-servico" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Emp</th>
                                    <th>Coletador</th>
                                    <th>Ordem</th>
                                    <th>Pessoa</th>
                                    <th>Servico Coleta</th>
                                    <th>Serviço Atual</th>
                                    <th>Alterado</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                </div>
                <!-- /.box-footer -->

            </div>
            {{-- Modal change Service Ordem --}}
            <div class="modal fade" id="modal-change-servi-ordem">
                <div class="modal-dialog modal-lg" style="">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Movimentações/Alterações Ordem</h4>
                        </div>
                        <div class="modal-body" id='modal-table-chande-ordem'>

                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
    @includeIf('admin.master.datatables')
    <script>
        $(document).ready(function() {
            var inicioData = 0;
            var fimData = 0;
            initDatatable("A", 0, 0, '3');
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
            $('#submit-seach').click(function() {
                var cd_empresa = $('#empresas').val();
                if (cd_empresa == 0) {
                    $('#empresas').attr('title', 'Empresa é obrigatório!').tooltip('show');
                    return false;
                } else if (inicioData == "") {
                    alert('Período deve ser preenchida!');
                    $('#daterange').attr('title', 'Período é obrigatório!').tooltip('show');
                    return false;
                }
                $("#table-troca-servico").DataTable().destroy();
                initDatatable('U', inicioData, fimData, cd_empresa);
            });
            $('body').on('click', '#view-grid-change', function(e) {
                e.preventDefault();
                var ordem = $(this).data('id');

                $.ajax({
                    type: "GET",
                    url: "{{ route('producao.get-troca-servico-ordem') }}",
                    data: {
                        ordem: ordem
                    },
                    beforeSend: function() {
                        $("#modal-table-chande-ordem").empty();
                    },
                    success: function(response) {
                        $('#modal-change-servi-ordem').modal('show');
                        $('#modal-table-chande-ordem').append(response);
                        $('#table-change-ordem').DataTable();



                    }
                });
            });

            function initDatatable(i, dtini, dtfim, cdempresa) {
                $("#table-troca-servico").DataTable({
                    responsive: true,
                    pagingType: "simple",
                    language: {
                        url: "http://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    ajax: {
                        url: "{{ route('producao.get-troca-servico') }}",
                        data: {
                            i: i,
                            dtini: dtini,
                            dtfim: dtfim,
                            cdempresa: cdempresa,
                        }
                    },
                    columns: [{
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            serachable: false,
                            sClass: 'text-center'
                        },
                        {
                            name: 'idempresa',
                            data: 'IDEMPRESA'
                        },
                        {
                            name: 'coletor',
                            data: 'COLETOR'
                        },
                        {
                            name: 'ordem',
                            data: 'ORDEM'
                        },
                        {
                            name: 'pessoa',
                            data: 'PESSOA'
                        },
                        {
                            name: 'servantigo',
                            data: 'SERVANTIGO'
                        },
                        {
                            name: 'servatual',
                            data: 'SERVATUAL'
                        },
                        {
                            name: 'dt_registo',
                            data: 'DT_REGISTRO'
                        }
                    ],
                    columnDefs: [{
                            className: 'dt-center',
                            width: '1%',
                            targets: 0
                        },
                        {
                            className: 'dt-center',
                            width: '1%',
                            targets: 1
                        },
                        {
                            width: '2%',
                            targets: 3
                        },
                        {
                            width: '15%',
                            targets: 4
                        },
                        {
                            className: 'dt-center',
                            width: '2%',
                            targets: 7
                        },

                    ],
                    dom: 'Blfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                });
            }


        });
    </script>
@endsection
