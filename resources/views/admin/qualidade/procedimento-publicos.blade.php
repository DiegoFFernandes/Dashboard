@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4 pull-right">
                @includeIf('admin.master.messages')
            </div>
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Procedimentos</h3>
                    </div>
                    <div class="box-body">
                        <table id="table-procedimento" class="table" width="100%">
                            <thead>
                                <th>Cód.</th>
                                <th>Setor</th>
                                <th>Titulo</th>
                                <th>Descrição</th>
                                <th>Resp.</th>
                                <th>Status</th>
                                <th>Versão</th>
                                <th>Acões</th>
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
    <script type="text/javascript">
        $(document).ready(function() {
            var dataTable = initTable('table-procedimento', 'P');

            function initTable(tableId, data) {
                $("#" + tableId).DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
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
                        url: "{{ route('get-procedimento.publish') }}",
                        data: {
                            status: data
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'nm_setor',
                            name: 'nm_setor'
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
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'criado',
                            name: 'criado'
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
        });
    </script>
@endsection
