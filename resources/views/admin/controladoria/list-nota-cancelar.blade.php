@extends('admin.master.master')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-10">
                <div class="box box-primary">
                    <div class="box-header">

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-sm" id="table-nota" style="width: 100%; font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Emp.</th>
                                    <th>Nota</th>
                                    <th>Cnpj</th>
                                    <th>Pessoa</th>
                                    <th>Motivo</th>
                                    <th>Requerente</th>
                                    <th>Observação</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-2 pr-0">
                <div class="box box-default">
                    <div class="box-header with-border">
                      <h3 class="box-title">Estatistica</h3>
        
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="chart-responsive">
                            {!! $chart->container() !!}
                          </div>
                          <!-- ./chart-responsive -->
                        </div>
                        <!-- /.col -->                       
                        
                      </div>
                      <!-- /.row -->
                    </div>
                    <!-- /.box-body -->                    
                  </div>
            </div>
            <!-- /.col -->
        </div>
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    @includeIf('admin.master.datatables')
    <script type="text/javascript">
        $(document).ready(function() {
            // init datatable.    
            $('#table-nota').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                "pageLength": 10,
                ajax: "{{ route('comercial.get-list-nota-all') }}",
                columns: [{
                        data: 'cd_empresa',
                        name: 'cd_empresa',
                        "width": '1%'
                    },
                    {
                        data: 'nr_nota',
                        name: 'nr_nota'
                    },
                    {
                        data: 'nr_cnpjcpf',
                        name: 'nr_cnpjcpf'
                    },
                    {
                        data: 'nm_pessoa',
                        name: 'nm_pessoa',
                    },
                    {
                        data: 'motivo',
                        name: 'motivo'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'observacao',
                        name: 'observacao',

                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    }
                ],
                columnDefs: [{
                    "width": "10px",
                    "targets": 0
                }],

            });
        });
    </script>
    {!! $chart->script() !!}
    <script type="text/javascript">
        var chartEl = document.getElementById("{{ $chart->id }}");
        chartEl.height = 250;
    </script>
@endsection
