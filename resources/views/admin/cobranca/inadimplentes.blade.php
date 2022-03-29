@extends('admin.master.master')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="small-box-info bg-green">
                    <div class="inner" style="padding-top: 15%">
                        <dt>Á vencer</dt>
                        <h3>R${{ number_format($vvencer, 2, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box-info bg-aqua">
                    <div class="inner">
                        <dt>Vencidos 60 Dias</dt>
                        <h3>R$ {{ number_format($vvtotal, 2, ',', '.') }}</h3>

                        <div class="small-box-small col-md-6 col-sm-6 bg-aqua-active">
                            <small>Mais de 120 dias</small>
                            <h4>R$ {{ number_format($vvencer120, 2, ',', '.') }}</h4>
                        </div>
                        <div class="small-box-small col-md-6 col-sm-6 bg-aqua-active">
                            <small>% Vencidos</small>
                            <h4>{{ $porcent120 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="small-box-info bg-light-blue">
                    <div class="inner" style="padding-top: 15%">
                        <dt>% Inadimplência</dt>
                        <h3>{{ number_format(($vvtotal/($vvtotal + $vvencer))*100, 2, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Likes</span>
                        <span class="info-box-number">93,139</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-gray">
                    <div class="inner">
                        <p>Total Carteira</p>
                        <h3>R$ {{ number_format($vvtotal + $vvencer, 2, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Inadimplência</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12">

                        </div>
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
