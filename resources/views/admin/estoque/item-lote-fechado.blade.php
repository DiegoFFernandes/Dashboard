@extends('admin.master.master')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">            
            <div class="col-md-6">
                <div class="nav-tabs-custom" style="cursor: move;">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">                        
                        <li class=""><a href="#table-resumo" data-toggle="tab"
                                aria-expanded="false">Resumo</a>
                        </li>
                        <li class="active"><a href="#table-itens" data-toggle="tab" aria-expanded="true">Itens</a>
                        </li>
                        <li class="pull-left header"><i class="fa fa-inbox"></i>{{'Itens / Lote: '.$lote->id.' / Criado em: '.$lote->created_at}} </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="table-itens">
                            <table id="table-item" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Cód. Item</th>
                                        <th>Descrição</th>
                                        <th>Peso</th>
                                        <th>Entrada em</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($itemlote as $i)
                                        <tr>
                                            <td>{{ $i->cd_produto }}</td>
                                            <td>{{ $i->ds_item }}</td>
                                            @if ($i->peso < $i->ps_liquido)
                                                <td class="bg-red color-palette">{{ number_format($i->peso, 2) }}</td>
                                            @else
                                                <td class="bg-green color-palette">{{ number_format($i->peso, 2) }}</td>
                                            @endif
                                            <td>{{ \Carbon\Carbon::parse($i->created_at)->format('d/m/Y H:i:s') }}</td>                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>                                        
                                        <th></th>
                                        <th style="text-align: center">Total peso</th>
                                        <th>{{$itemlote->sum(function($i){
                                            return $i->peso;
                                        })}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="tab-pane" id="table-resumo">
                            <table id="table-item-group" class="table">
                                <thead>
                                    <tr>
                                        <th>Cód. Item</th>
                                        <th>Descrição</th>
                                        <th>Quantidade</th>
                                        <th>Soma Peso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($itemgroup as $i)
                                        <tr>
                                            <td>{{ $i->cd_produto }}</td>
                                            <td>{{ $i->ds_item }}</td>   
                                            <td>{{ $i->qtditem }}</td>                                          
                                            <td>{{ number_format($i->peso, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>                                        
                                        <th></th>
                                        <th style="text-align: center">Total</th>
                                        <th>{{$itemgroup->sum(function($i){
                                            return $i->qtditem;
                                        })}}</th>
                                        <th>{{$itemgroup->sum(function($i){
                                            return $i->peso;
                                        })}}</th>
                                    </tr>
                                </tfoot>
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
    <script type="text/javascript">        
        $("#table-item").DataTable({
            language: {
                url: "http://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
            },
            responsive: true,
            "order": [
                [3, "desc"]
            ],
        });        
    </script>
@endsection
