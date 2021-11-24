@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Usúario</h3>
                    </div>
                    <div class="box-body box-profile">
                        <h3 class="profile-username text-center">Diego Ferreira</h3>
                        <p class="text-muted text-center">ti.campina@ivorecap.com.br</p>
                        <div class="form-group">
                            <label for="newPassword">Alterar Senha</label>
                            <input type="password" class="form-control" id="newPassword" placeholder="Alterar senha">
                        </div>
                        <div class="form-group">
                            <label for="confirmNewPassword">Confirmar Senha</label>
                            <input type="password" class="form-control" id="confirmNewPassword" placeholder="Confirmar senha">
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-primary pull-right">Alterar</button>
                      </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
           $("#newPassword").keyup(){
            console.log('newPassword');
           });
            
        });
    </script>
@endsection
