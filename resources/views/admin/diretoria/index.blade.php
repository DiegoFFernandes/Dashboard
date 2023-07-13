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
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    <script type="text/javascript"></script>
@endsection
