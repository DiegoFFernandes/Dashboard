@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs ui-sortable-handle">
                        <li class="active"><a id="bloqueio" href="#bloqueio-pedido" data-toggle="tab" aria-expanded="true">Pedidos
                                Bloqueados</a>
                        </li>
                        <li class=""><a href="#acompanhamento" data-toggle="tab"
                                aria-expanded="false">Acompanhamento</a>
                        </li>
                        {{-- <li class="pull-left header"><i class="fa fa-inbox"></i> Pedidos</li> --}}
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="bloqueio-pedido">
                            <div>
                                <table class="table stripe compact" id="bloqueio-pedidos" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Emp</th>
                                            <th>Pedido</th>
                                            <th>Pedido Palm</th>
                                            <th>Cliente</th>
                                            <th>Data</th>
                                            <th>Motivo</th>
                                            <th>Ativo</th>
                                            <th>Scpc</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="acompanhamento">
                            
                        </div>
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
    <script type="text/javascript">    
        $('#bloqueio-pedidos').DataTable({
            language: {
                url: "http://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
            },
            processing: false,
            serverSide: false,
            pageLength: 25,
            responsive: true,
            // scrollX: true,
            ajax: "{{ route('get-bloqueio-pedidos') }}",
            columns: [{
                    data: 'IDEMPRESA',
                    name: 'IDEMPRESA',
                    "width": "1%"
                },
                {
                    data: 'PEDIDO',
                    name: 'PEDIDO',
                    "width": "1%"
                },
                {
                    data: 'MOBILE',
                    name: 'MOBILE',
                },
                {
                    data: 'CLIENTE',
                    name: 'CLIENTE',
                },
                {
                    data: 'DATA',
                    name: 'DATA',
                },
                {
                    data: 'MOTIVO',
                    name: 'MOTIVO',
                },
                {
                    data: 'ST_ATIVA',
                    name: 'ST_ATIVA',
                },
                {
                    data: 'ST_SCPC',
                    name: 'ST_SCPC',
                },
                {
                    data: 'STPEDIDO',
                    name: 'STPEDIDO',
                },
                {
                    data: 'action',
                    name: 'action',
                }
            ],
            columnDefs: [{
                targets: [4],
                render: $.fn.dataTable.render.moment('DD/MM/YYYY')
            }],
        });
    </script>
@endsection
