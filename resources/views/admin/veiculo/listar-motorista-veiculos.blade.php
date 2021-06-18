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
                    <h3 class="box-title">Veiculos Cadastrados</h3>
                </div>
                <!-- /.box-header -->
                <table class="table display table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Pessoa</th>
                            <th>Placa</th>                            
                            <th>Modelo</th>
                            <th>Frota</th>                           
                            <th>Tipo</th>
                            <th>Ativo</th>   
                            <th>Cadastro</th> 
                            <th>Ações</th>                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($motoristas as $m)
                        <tr>
                            <td>{{$m->id}}</td>
                            <td>{{$m->name}}</td>
                            <td>{{$m->placa}}</td>                            
                            <td>{{$m->modelo}}</td>
                            <td>{{$m->frota}}</td>
                            <td>{{$m->tipo}}</td>
                            <td>{{$m->ativo}}</td>
                            <td>{{$m->cadastro}}</td>
                            <td>
                                <a href="{{route('admin.editar.motorista.veiculos', $m->id)}}">Editar</a>
                            </td>                                                       
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
@endsection