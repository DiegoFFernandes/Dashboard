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
              <h3 class="box-title">Informe o veiculo</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" action="{{route('admin.portaria.entrada.do')}}">
                @csrf
              <div class="box-body">
                <div class="form-group">
                  <label for="inputPlaca" class="col-sm-2 control-label">Placa:</label>
                  <div class="col-sm-6">
                    <input type="text" name="placa" class="form-control" id="inputPlaca" placeholder="AAA-0000">
                  </div>
                </div>                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-success">Pesquisar</button>                
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