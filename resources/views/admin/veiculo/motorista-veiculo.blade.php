@extends('admin.master.master')

@section('content')
<!-- Main content -->
<section class="content">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">{{$title_page }}</h3>
        </div>
        <form role="form" method="post"
            action="{{isset($veiculo[0]->cd_empresa) ? route('admin.editar-do.motorista.veiculos') : route('admin.cadastrar-do.motorista.veiculos')}}"
            id="MotoristaForm" data-marcas-url="{{route('load_marcas')}}" data-modelos-url="{{route('load_modelos')}}">
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
                    @if(isset($veiculo[0]->id))
                    <input type="hidden" name="id" value="{{$veiculo[0]->id}}">
                    @endif
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Empresa</label>
                            <select name="cd_empresa" class="form-control select2" required>
                                @if(isset($veiculo[0]->cd_empresa))
                                @foreach($empresas as $empresa)
                                @if($veiculo[0]->cd_empresa === $empresa->CD_EMPRESA)
                                <option value="{{$veiculo[0]->cd_empresa}}" selected="selected">{{$empresa->NM_EMPRESA}}
                                </option>
                                @endif
                                @endforeach
                                @foreach($empresas as $empresa)
                                @if($veiculo[0]->cd_empresa <> $empresa->CD_EMPRESA)
                                    <option value="{{$empresa->CD_EMPRESA}}">{{$empresa->NM_EMPRESA}}</option>
                                    @endif
                                    @endforeach
                                    @else
                                    <option value="" selected="selected">Selecione</option>
                                    @foreach($empresas as $empresa)
                                    <option value="{{$empresa->CD_EMPRESA}}">{{$empresa->NM_EMPRESA}}</option>
                                    @endforeach
                                    @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Motorista</label>
                            <select name="cd_pessoa" class="form-control select2" required>
                                @if(isset($veiculo[0]->pessoa))
                                <option value="{{$veiculo[0]->cd_pessoa}}" selected="selected">
                                    {{$veiculo[0]->cd_pessoa}} - {{$veiculo[0]->pessoa}}
                                </option>
                                @foreach($motoristas as $motorista)
                                @if($veiculo[0]->cd_pessoa <> $motorista->id)
                                <option value="{{$motorista->id}}">{{$motorista->id}} - {{$motorista->name}}
                                </option>
                                @endif
                                @endforeach

                                @else
                                <option value="" selected="selected">Selecione</option>
                                @foreach($motoristas as $motorista)
                                <option value="{{$motorista->id}}">{{$motorista->id}} - {{$motorista->name}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Placa</label>
                            <input type="text" id="placa" class="form-control" name="placa"
                                value="{{isset($veiculo[0]->placa ) ? $veiculo[0]->placa : '' }}"
                                placeholder="AAA-0000" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="ativo" value="S"
                                        {{ isset($veiculo[0]->pessoa) ? (($veiculo[0]->ativo =="S") ? 'checked' : '')  : 'checked' }}>
                                    Ativado
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="ativo" value="N"
                                        {{ isset($veiculo[0]->pessoa) ? (($veiculo[0]->ativo =="N") ? 'checked' : '')  : '' }}>
                                    Desativado
                                </label>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Tipo Veiculo</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="cd_tipoveiculo" value="1"
                                        {{ isset($veiculo[0]->cd_tipoveiculo) ? (($veiculo[0]->cd_tipoveiculo =='1') ? 'checked' : '') : "" }}>
                                    Frota Carga
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="cd_tipoveiculo" value="2"
                                        {{ isset($veiculo[0]->cd_tipoveiculo) ? (($veiculo[0]->cd_tipoveiculo =="2") ? "checked" : "") : ""  }}>
                                    Frota Utilitário
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="cd_tipoveiculo" value="3"
                                        {{ isset($veiculo[0]->cd_tipoveiculo) ? (($veiculo[0]->cd_tipoveiculo =="3") ? "checked" : "") : "checked" }}>
                                    Particular
                                </label>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Ano veiculo</label>
                            <input id="ano" type="text" class="form-control" name="ano"
                                value="{{ isset($veiculo[0]->ano) ? $veiculo[0]->ano : ''}}" placeholder="2000" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Cor</label>
                            <input type="text" id="cor" class="form-control" name="cor"
                                value="{{isset($veiculo[0]->cor) ? $veiculo[0]->cor : ''}}" placeholder="Cor Veiculo" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Frota Veiculo</label>
                            <select class="form-control select2" id="id_frotaveiculo" style="width: 100%;" required>
                                @empty($veiculo[0]->frota)
                                <option selected="selected">Selecione</option>
                                @endempty
                                @foreach($frotaveiculos as $frotaveiculo)
                                @if (isset($veiculo[0]->frota))
                                @if($frotaveiculo->descricao === $veiculo[0]->frota)
                                <option value="{{$frotaveiculo->id}}" selected="selected">{{$frotaveiculo->descricao}}
                                </option>
                                @else
                                <option value="{{$frotaveiculo->id}}">{{$frotaveiculo->descricao}}</option>
                                @endif
                                @else
                                <option value="{{$frotaveiculo->id}}">{{$frotaveiculo->descricao}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Marca</label>
                            <select class="form-control select2" name="cd_marca" id="id_marca" style="width: 100%;" required>
                                @if(isset($veiculo[0]->marca))
                                <option value="{{$veiculo[0]->cd_marca}}" selected="selected">{{$veiculo[0]->marca}}
                                </option>
                                @else
                                <option selected="selected" value="">Selecione a Frota</option>
                                @foreach($marcas as $marca)
                                <option value="{{$marca->cd_marca}}" selected="selected">{{$marca->descricao}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" required>
                        <div class="form-group">
                            <label>Modelo</label>
                            <select class="form-control select2" name="cd_modelo" id="id_modelo" style="width: 100%;"> required
                                @if(isset($veiculo[0]->marca))
                                <option value="{{$veiculo[0]->cd_modelo}}" selected="selected">{{$veiculo[0]->modelo}}
                                </option>
                                @else
                                <option selected="selected">Selecione a Marca</option>
                                @foreach($modelos as $modelo)
                                <option value="{{$modelo->id}}" selected="selected">{{$modelo->descricao}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="box-footer">
                @if(empty($veiculo))
                <button type="submit" class="btn btn-success">Cadastrar</button>
                @else
                <button type="submit" class="btn btn-warning">Alterar</button>
                @endif
                <a href="" class="btn btn-primary">Cancelar</a>
            </div>
        </form>
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->
@endsection