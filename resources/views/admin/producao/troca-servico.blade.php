@extends('admin.master.master')

@section('content')
    <section class="content">
        <div class="row">
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
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>Ordem </th>
                                        <th>Pessoa</th>
                                        <th>Etapa</th>
                                        <th>Operador</th>
                                        <th>Banda Antiga</th>
                                        <th>Banda Nova</th>
                                        <th>Alterado em</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($troca as $t)
                                        <tr>
                                            <td>{{ $t->ORDEM }}</td>
                                            <td>{{ $t->PESSOA }}</td>
                                            <td>{{ $t->DSETAPA }}</td>
                                            <td>{{ $t->OPERADOR }}</td>
                                            <td>{{ $t->DSANTIGA }}</td>
                                            <td>{{ $t->DSNOVA }}</td>
                                            <td>{{ $t->DTALTERACAO }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                    </div>
                    <!-- /.box-footer -->
                </div>

            </div>
        </div>
    </section>
@endsection
