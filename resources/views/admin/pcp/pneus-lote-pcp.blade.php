@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Pneus que estão em produção</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-striped" id="table-item-lote">
                            <thead class="thead-light">
                                <tr>
                                    <th>Pedido</th>
                                    <th>Ordem</th>
                                    <th>Id</th>
                                    <th>Cliente</th>
                                    <th>Serviço</th>
                                    <th>Modelo</th>
                                    <th>Serie</th>
                                    <th>Fogo</th>
                                    <th>Dot</th>
                                    <th>Lote</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pneus_lote as $pneus)
                                    <tr>
                                        <td>{{ $pneus->PEDIDO }}</td>
                                        <td>{{ $pneus->ORDEM }}</td>
                                        <td>{{ $pneus->ID }}</td>
                                        <td>{{ $pneus->CLIENTE }}</td>
                                        <td>{{ $pneus->SERVICO }}</td>
                                        <td>{{ $pneus->MODELO }}</td>
                                        <td>{{ $pneus->SERIE }}</td>
                                        <td>{{ $pneus->FOGO }}</td>
                                        <td>{{ $pneus->DOT }}</td>
                                        <td>{{ $pneus->LOTE }}</td>                                        
                                    </tr>
                                @endforeach
                            </tbody>
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
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#table-item-lote').DataTable();
        });
    </script>
@endsection
