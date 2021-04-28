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
                <div class="box-body table-responsive no-padding">
                    <table class="table display table-striped">
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
                                <th>DS Lote</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pneus_lote as $pneus) 
                            <tr>
                                <td>{{$pneus->PEDIDO}}</td>
                                <td>{{$pneus->ORDEM}}</td>
                                <td>{{$pneus->ID}}</td>
                                <td>{{$pneus->CLIENTE}}</td>
                                <td>{{$pneus->SERVICO}}</td>
                                <td>{{$pneus->MODELO}}</td>                                
                                <td>{{$pneus->SERIE}}</td>
                                <td>{{$pneus->FOGO}}</td>
                                <td>{{$pneus->DOT}}</td>
                                <td>{{$pneus->LOTE}}</td>
                                <td>{{$pneus->DSLOTE}}</td>
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