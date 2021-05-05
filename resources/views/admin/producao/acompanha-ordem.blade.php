@extends('admin.master.master')

@section('content')
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Qual ordem deseja pesquisar?</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-inline" action="{{route('admin.producao.acompanha.ordem')}}" method="post">
                    @csrf
                    <div class="box-body acompanha-ordem">
                        <div class="form-group m-2">
                            <label for="numeroOrdem">Número Ordem:</label>                            
                            (<a
                                href="http://zxing.appspot.com/scan?ret=http://producao.ivorecap.com.br:8000/producao/acompanha-ordem/{CODE}" data-toggle="tooltip" data-placement="top" title="Exclusivo Celular">Leitor</a>):
                            <input type="number" class="form-control" name="nr_ordem" value="{{isset($codigo_barras) ? $codigo_barras : ''}}" placeholder="123456" required/>


                        </div>
                        <button type="submit" class="btn btn-primary">Pesquisar</button>
                    </div>
                </form>
            </div>
        </div>
        @if(isset($status_etapas))
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header table-responsive">
                    <h3 class="box-title">Informações da Ordem</h3>
                    <table class="table">
                        <tbody>
                            @foreach ($info_pneu as $info)
                            @foreach($info as $chave => $valor)
                            <tr>
                                @if($info->DSLOTE == "L3-AMARELO")
                                <th scope="row" class="bg-warning">{{$chave}}:</th>
                                <td class="bg-warning">{!! $valor!!}</td>
                                @elseif($info->DSLOTE == "L2-VERMELHO")
                                <th scope="row" class="bg-danger">{{$chave}}:</th>
                                <td class="bg-danger">{!! $valor!!}</td>
                                @else
                                <th scope="row">{{$chave}}:</th>
                                <td>{!! $valor!!}</td>
                            </tr>
                            @endif
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-header -->
            </div>
        </div>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body table-responsive">
                    <table class="table display table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Usuario</th>
                                <th>Entrada</th>
                                <th>Saida</th>
                                <th>Detalhes</th>
                                <th>Retrabalho</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($status_etapas as $acompanha)
                            <tr>
                                <th scope="row">{!! utf8_encode($acompanha->O_DS_ETAPA) !!}</th>
                                <td>{!! utf8_encode($acompanha->O_NM_USUARIO) !!}</td>
                                <td>{!! utf8_encode($acompanha->O_DT_ENTRADA).' '.utf8_encode($acompanha->O_HR_ENTRADA)
                                    !!}</td>
                                <td>{!! utf8_encode($acompanha->O_DT_SAIDA).' '.utf8_encode($acompanha->O_HR_SAIDA) !!}
                                </td>
                                <td>{!! utf8_encode($acompanha->O_DS_COMPLEMENTOETAPA) !!}</td>
                                <td>{!! utf8_encode($acompanha->O_ST_RETRABALHO) !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
    @endif
    @if(isset($sem_info))
    <div class="col-md-12">
        <h3>Ordem <strong>{{$nr_ordem}}</strong> não existe...</h3>
        @endif
    </div>
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
@endsection