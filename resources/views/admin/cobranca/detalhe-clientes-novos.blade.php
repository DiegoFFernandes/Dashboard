@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Detalhe Clientes Novos</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12">
                            <table class="table table-responsive">
                                <thead>
                                    <th>Cd. Pessoa</th>
                                    <th>Nome</th>
                                    <th>CNPJ</th>
                                </thead>
                                <tbody>
                                    @foreach ( $detalhesOperador as $d)
                                    <tr>
                                        <td>{{$d->CD_PESSOA}}</td>
                                        <td>{{$d->NM_PESSOA}}</td>
                                        <td>{{$d->NR_CNPJCPF}}</td>
                                    </tr>
                                    @endforeach                                    
                                </tbody>
                            </table>
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
    <script src="{{ asset('js/scripts.js') }}"></script>

@endsection
