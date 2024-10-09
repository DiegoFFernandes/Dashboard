@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                @includeIf('admin.master.messages')
            </div>
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-body">
                        <h2>Olá seja bem vindo(a), {{ $user_auth->name }}!</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">                        
                        <h3 class="box-title">View 2.0</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12 text-center">
                            <h4>000 - GRUPO</h4>
                            <a href="{{ route('view.coleta-rede') }}"><button class="btn btn-default" href="">000 - Coletas Rede</button></a>
                            <button class="btn btn-default">000 - Faturamento Rede</button>
                            <button class="btn btn-default">000 - Diretoria - Mes(Atual)</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
@endsection
