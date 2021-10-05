@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- right column -->
            <div class="col-md-6">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dados do Motorista</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="post" action="">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="placa" class="col-md-1 control-label">Placa:</label>
                                <div class="col-md-10">
                                    <input type="text" name="placa" class="form-control" id="placa" placeholder="AAA0000" disabled>
                                </div>                                
                            </div>
                            
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Entrada</button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section('scripts')    
    <!-- My Scripts -->
    <script src="{{ asset('js/portaria/scripts.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

@endsection
