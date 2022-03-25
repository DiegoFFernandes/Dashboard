@extends('admin.master.master')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <dt>Vencidos 60 Dias</dt>
                        <h3>R$ 1.067.791,25</h3>

                        <div class="small-box-small col-md-6 col-sm-6 bg-aqua-active">
                            <small>Mais de 120 dias</small>
                            <h4>R$ 251.894,00</h4>
                        </div>
                        <div class="small-box-small col-md-6 col-sm-6 bg-aqua-active">
                            <small>% Vencidos</small>
                            <h4> 16,09</h4>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Bookmarks</span>
                        <span class="info-box-number">410</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-files-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Uploads</span>
                        <span class="info-box-number">13,648</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Likes</span>
                        <span class="info-box-number">93,139</span>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
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
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>
@endsection
