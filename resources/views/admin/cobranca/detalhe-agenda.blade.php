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
                    <div class="box-body no-padding">
                        <table class="table display">
                            <thead>
                                <tr>
                                    <th>Agenda</th>
                                    <th>Cliente</th>
                                    <th>Usuario</th>
                                    <th>Descrição</th>
                                    <th>Data Contato</th>
                                    <th>Status Contato</th>
                                </tr>
                            </thead>
                            <tbody>
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
    <script src="{{ asset('js/scripts.js') }}"></script>
@endsection
