@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                @includeIf('admin.master.messages')
            </div>
            @role('admin|diretoria|gerencia')
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Demonstrativo de Recape Mensal - Sul</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-center">
                                    @isset($recapMounth)
                                        <strong>Recapes:
                                            {{ $recapMounth[0]->MES_NOME .' - ' .$recapMounth[0]->ANO .', ' .$recapMounth[12]->MES_NOME .' - ' .$recapMounth[12]->ANO }}</strong>
                                    @endisset
                                </p>
                                <div class="chart">
                                    @isset($chart)
                                        {{ $chart->container() }}
                                    @endisset
                                </div>
                                <!-- /.chart-responsive -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-4" style="height: 400px; overflow-y: scroll;">
                                @isset($recapMounth)
                                    @foreach ($recapMounth as $r)
                                        <div class="progress-group">
                                            <span class="progress-text">{{ $r->MES_NOME . ' - ' . $r->ANO }}</span>
                                            <span class="progress-number"><b>{{ $r->QTDE }}</b>/10000</span>
                                            <div class="progress sm">
                                                @if ($r->QTDE >= 10000)
                                                    <div class="progress-bar progress-bar-green"
                                                        style="width: {{ ($r->QTDE * 100) / 10000 }}%"></div>
                                                @elseif($r->QTDE >= 9000)
                                                    <div class="progress-bar progress-bar-yellow"
                                                        style="width: {{ ($r->QTDE * 100) / 10000 }}%"></div>
                                                @else
                                                    <div class="progress-bar progress-bar-red"
                                                        style="width: {{ ($r->QTDE * 100) / 10000 }}%"></div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endisset
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- ./box-body -->
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-green"><i class="fa fa-caret-up"></i>
                                        17%</span>
                                    <h5 class="description-header">$35,210.43</h5>
                                    <span class="description-text">TOTAL PRE</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i>
                                        0%</span>
                                    <h5 class="description-header">$10,390.90</h5>
                                    <span class="description-text">TOTAL VULC</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-green"><i class="fa fa-caret-up"></i>
                                        20%</span>
                                    <h5 class="description-header">$24,813.53</h5>
                                    <span class="description-text">TOTAL UTI</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block">
                                    <span class="description-percentage text-red"><i class="fa fa-caret-down"></i>
                                        18%</span>
                                    <h5 class="description-header">1200</h5>
                                    <span class="description-text">PRODUÇÃO PENDENTES</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-footer -->
                </div>
            </div>
            @endrole
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>150</h3>

                        <p>Vendas</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">Mais informações <i
                            class="fa
                    fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>53<sup style="font-size: 20px">%</sup></h3>

                        <p>Gráficos</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">Mais informações <i
                            class="fa
                    fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>
                            @isset($vendedor)
                                {{ $vendedor[0]->QTD }}
                            @endisset
                        </h3>
                        <p>Vendedores Ativos</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">Mais informações <i
                            class="fa
                    fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>65</h3>

                        <p>Acompanhamento de Ordens</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    @includeIf('admin.master.datatables')
    @isset($chart)
        {!! $chart->script() !!}
    @endisset
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endsection
