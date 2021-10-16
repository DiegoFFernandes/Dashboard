@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Entrada/Saída</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table datatable cell-border compact stripe">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Motorista</th>
                                        <th>Placa</th>
                                        <th>Linha</th>
                                        <th>Resp. Entrada</th>
                                        <th>Data Entrada</th>
                                        <th>Resp. Saída</th>
                                        <th>Data Saída</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- /.box -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    @includeIf('admin.master.datatables')
    <script>
        $(document).ready(function() {

            // init datatable.    
            var dataTable = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 5,
                // scrollX: true,
                "order": [
                    [0, "desc"]
                ],

                "pageLength": 10,
                ajax: "{{ route('get.portaria.movimentos') }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'motorista',
                        name: 'motorista'
                    },
                    {
                        data: 'placa',
                        name: 'placa'
                    },
                    {
                        data: 'linha',
                        name: 'linha'
                    },
                    {
                        data: 'resp_entrada',
                        name: 'resp_entrada'
                    },
                    {
                        data: 'entrada',
                        name: 'entrada'
                    },
                    {
                        data: 'resp_saida',
                        name: 'resp_saida'
                    },
                    {
                        data: 'saida',
                        name: 'saida'
                    }
                ],
                columnDefs: [
                    {
                    "className": "background-green",
                    targets: [4, 5],
                    },
                    {
                    "className": "background-red",
                    targets: [6, 7],
                    }
                    ],



            });




        });
    </script>
    <script src="{{ asset('js/scripts.js') }}"></script>

@endsection
