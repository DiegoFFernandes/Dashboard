@extends('admin.master.master')

@section('content')
<!-- Main content -->
<section class="content">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">{{$title_page }}</h3>
        </div>
        <form role="form" method="post" action="{{route('marca-veiculo.salvar')}}">
            @csrf
            <div class="box-body">
                @if($errors->all())
                @foreach($errors->all() as $error)
                <!-- alert -->
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-check"></i>{{$error}}
                </div>
                <!-- /alert -->
                @endforeach
                @endif
                @if (session('warning'))
                <!-- alert -->
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-check"></i>{{session('warning')}}
                </div>
                <!-- /alert -->
                @endif
                @if (session('status'))
                <!-- alert -->
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-check"></i>{{session('status')}}
                </div>
                <!-- /alert -->
                @endif
                <div class="row">

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Número Marca</label>
                            <input type="number" class="form-control" name="cd_marca" placeholder="Informe um número para Marca">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Descrição Marca</label>
                            <input type="text" class="form-control" name="descricao" placeholder="Descrição Marca">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Frota Veiculo</label>
                            <select class="form-control select2" name="cd_frotaveiculos" style="width: 100%;" required>
                                <option selected="selected" value="">Selecione a Frota</option>
                                @foreach($frotaveiculos as $f)
                                <option value="{{$f->id}}">{{$f->descricao}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

            </div>
            <div class="box-footer">

                <button type="submit" class="btn btn-success">Cadastrar</button>

                <button type="submit" class="btn btn-warning">Alterar</button>

                <a href="" class="btn btn-primary">Cancelar</a>
            </div>
        </form>
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->
@endsection