@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary img-box-indicador-left">
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="text-center">

                                <a class="btn btn-lg btn-primary" href="{{ route('diretoria.ivo-norte', Crypt::encrypt('norte')) }}"></i>Indicadores
                                    Norte</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-primary img-box-indicador-right">
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="text-center">
                                <a class="btn btn-lg btn-primary" href="{{ route('diretoria.ivo-sul', Crypt::encrypt('sul')) }}"></i>Indicadores
                                    Sul</a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @canany(['ver-diretoria-rede'])
            <div class="col-md-12">
                <div class="box box-primary img-box-indicador-down">
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="text-center">
                                <a class="btn btn-lg btn-success" href="{{ route('diretoria.rede', [Crypt::encrypt('rede'), 0]) }}"></i>Indicadores
                                    Rede</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcanany
        </div>
    </section>
    <!-- /.content -->
@endsection


