@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row" style="background: rgb(255, 255, 255); padding-top: 12px;">
            <div class="col-md-4 pull-right">
                @includeIf('admin.master.messages')
            </div>
            <div class="col-md-3">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Filtrar</h3>


                    </div>

                    <div class="box-body" style="">
                        <ul class="nav nav-stacked">

                            <li><a data-id="all" class="option-sgi"><i class="fa fa-folder-open-o text-yellow"></i>
                                    Todos<span class="pull-right badge bg-green">{{ $filtro->sum('qtd') }}</span></a></li>

                            @foreach ($filtro as $f)
                                <li><a data-id="{{ $f->cd_empresa }}" class="option-sgi"><i
                                            class="fa fa-folder-open-o text-yellow"></i> {{ $f->ds_local }}<span
                                            class="pull-right badge bg-blue">{{ $f->qtd }}</span></a></li>
                            @endforeach

                        </ul>
                    </div>

                </div>

            </div>

            <div class="col-md-9">

                <div class="box-body">
                    <table id="table-sgi-publish" class="table" width="100%">
                        <thead>
                            <th>Cód.</th>
                            <th>Unidade</th>
                            <th>Nome Documento</th>
                            <th>Descrição</th>
                            <th>Validade</th>
                            <th>Status</th>
                            <th>Acões</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var dataTable;

            initTable('table-sgi-publish', 'pub', 'all');           
            

            function initTable(tableId, data, setor) {
                dataTable = $("#" + tableId).DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    searchPanes: {
                        viewTotal: true,
                        columns: [1]
                    },
                    dom: 'Plfrtip',
                    pagingType: "simple",
                    processing: false,
                    responsive: true,
                    serverSide: false,
                    autoWidth: false,
                    order: [
                        [0, "desc"]
                    ],
                    "pageLength": 10,
                    ajax: {
                        url: "{{ route('get-sgis.publish') }}",
                        data: {
                            public: data,
                            setor: setor
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'ds_local',
                            name: 'ds_local'
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'dt_validade',
                            name: 'dt_validade'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'Actions',
                            name: 'Actions',
                            orderable: false,
                            serachable: false,
                            sClass: 'text-center'
                        }
                    ],
                    columnDefs: [{
                            width: '1%',
                            targets: 0
                        },
                        {
                            width: '30%',
                            targets: 3
                        }

                    ],
                });
            }
            $('.option-sgi').click(function(e) {
                e.preventDefault();
                var unidade = $(this).data('id');
                
                dataTable.destroy();
                initTable('table-sgi-publish', 'pub', unidade);

            });
            $(document).on('click', '.btn-download', function() {
                $("#loading").removeClass('hidden');
                // Inicia o download do arquivo
                $.get($(this).attr('href'), function(response) {
                    // Código para fazer o download do arquivo
                }).done(function() {
                    // Remove a classe "loading" quando o download for concluído
                    $("#loading").addClass('hidden');
                });
            });
        });
    </script>
@endsection
