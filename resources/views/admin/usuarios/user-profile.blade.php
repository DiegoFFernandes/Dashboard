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
                        <h3 class="profile-username text-center">{{$user_auth->name}}</h3>
                        <p class="text-muted text-center">{{$user_auth->email}}</p>
                        <div class="form-group">
                            <label for="newPassword">Alterar Senha</label>
                            <input type="password" class="form-control" id="newPassword" placeholder="Alterar senha">
                        </div>
                        <div class="form-group">
                            <label for="confirmNewPassword">Confirmar Senha</label>
                            <input type="password" class="form-control" id="confirmNewPassword"
                                placeholder="Confirmar senha">
                            <p class="help-block hidden"></p>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button id="update-user" class="btn btn-primary pull-right">Alterar</button>
                    </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            {{-- Icon loading --}}
            <div class="hidden" id="loading">
                <img id="loading-image" class="mb-4" src="{{ Asset('img/loader.gif') }}" alt="Loading...">
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#password, #confirmNewPassword").on("keyup", function() {
                var newpassword = $("#newPassword").val();
                var confirm_password = $("#confirmNewPassword").val();
                let token = $("meta[name='csrf-token']").attr("content");
                if (newpassword.length > 1 || confirm_password.length > 1) {
                    if (newpassword == confirm_password) {
                        $(".help-block").removeClass('hidden').text('Senhas iguais!').css('color',
                            'green');
                        $("#update-user").click(function() {
                            $.ajax({
                                method: "POST",
                                url: "{{route('profile-user.update')}}",
                                data: {
                                    _token: token,
                                    password: newpassword,
                                    confirm_password: confirm_password,
                                }, 
                                beforeSend: function(){
                                    $("#loading").removeClass('hidden')
                                },
                                success: function(result){
                                    alert(result.success);
                                    window.location.replace("{{ route('login') }}");
                                }
                            })
                        });
                    } else {
                        $(".help-block").removeClass('hidden').text(
                            'As senhas não são iguais, favor verificar!').css('color', 'red');
                    }
                } else {
                    $(".help-block").addClass('hidden');
                }
            });



        });
    </script>
@endsection
