@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Buscar:</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-5">
                            <div class="form-group">                                
                                @includeIf('admin.master.empresas')
                            </div>
                        </div>
                        <div class="col-md-5">
                            @includeIf('admin.master.filtro-data')
                        </div>
                        <div class="col-md-2" align="center">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block" id="submit-seach">
                                    <i class="fa fa-download"></i> Gerar Arquivo</button>
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
