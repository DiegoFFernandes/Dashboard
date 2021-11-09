@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Detalhe agenda por Operador</h3>
                        <div class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <li><a href="#">«</a></li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">»</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered compact" id="table-detalhe-agenda">
                            <thead  style="font-size: 12px">
                                <tr>
                                    <th>Agenda</th>
                                    <th style="width: 20px">Cliente</th>
                                    <th>Usuario</th>
                                    <th>Descrição</th>
                                    <th>Data Contato</th>
                                    <th>Status Contato</th>
                                </tr>
                            </thead>
                            <tbody  style="font-size: 11px">
                                @foreach ($detalhes as $d)
                                    <tr>
                                        <td>{{ $d->NR_SEQUENCIA }}</td>
                                        <td>{{ $d->NM_PESSOA }}</td>
                                        <td>{{ $d->CD_USUARIO }}</td>
                                        <td>{{ $d->DS_AGENDA }}</td>
                                        <td>{{ $d->DT_REGISTRO }}</td>
                                        <td>{{ $d->ST_CONTATO }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
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
    
    <script>
        $('#table-detalhe-agenda').DataTable({        
        "columnDefs": [
            { width: '15%', targets: 1 },
            { width: '5%', targets: 2 },
            { width: '40%', targets: 3 },
        ]
    });
    </script>
@endsection
