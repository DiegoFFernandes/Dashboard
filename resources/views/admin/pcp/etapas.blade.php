@extends('admin.master.master')

@section('content')
<!-- Main content -->
<section class="content">

    <div class="row">
        @foreach($etapas as $etapa)
        @foreach($etapa as $chave => $valor)
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box">
                <div class="inner">
                    <h3>{{$valor}}</h3>
                    @if($chave == 'NR_EXINI')
                    <p>Exame Inicial</p>
                    @elseif($chave == 'NR_RASPA')
                    <p>Raspa</p>
                    @else
                    <p>{{$chave}}</p>
                    @endif
                </div>
                <a href="#" class="small-box-footer">
                    Mais Informações<i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        @endforeach
        @endforeach
    </div>

</section>
<button type="button" class="icon-pesquisa btn btn-default" data-toggle="modal" data-target="#modal-default">
    Pesquisar
</button>
<div class="modal fade" id="modal-default" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('admin.producao.etapas')}}" method="post">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Informe uma Data:</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Data:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="date" name="date" class="form-control" required>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Consultar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- /.content -->
@endsection