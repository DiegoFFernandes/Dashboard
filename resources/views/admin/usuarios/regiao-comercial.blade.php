@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Vincular</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="alert alert-fixed hidden">
                            <p></p>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Regiões Junsoft</label>
                                    <select class="form-control select2" id="cd_regiaocomercial" style="width: 100%;">
                                        <option selected="selected">Selecione</option>
                                        @foreach ($regiao as $r)
                                            <option value="{{ $r->CD_REGIAOCOMERCIAL }}">{{ $r->DS_REGIAOCOMERCIAL }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Usúarios Dashboard</label>
                                    <select class="form-control select2" id="cd_usuario" style="width: 100%; ">
                                        <option selected="selected">Selecione</option>
                                        @foreach ($user as $u)
                                            <option value="{{ $u->id }}">{{ strtoupper($u->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2" align="center" style="padding-top: 24px">
                                <div class="form-group">
                                    <button type="submit" id="btn-vincular"
                                        class="btn btn-primary btn-block">Vincular</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Vendedores Associados</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table display table-sm" id="table-regiao" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Cód.</th>
                                    <th>Cd. Usúario</th>
                                    <th>Usúario</th>
                                    <th>Cd. Região</th>
                                    <th>Região</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
        {{-- Modal Editar --}}
        <div class="modal" id="CreatePessoaModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <button type="button" class="close btn-cancel" data-dismiss="modal" data-keyboard="false"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Editar Região</h4>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <input id="token" name="_token" type="hidden" value="{{ csrf_token() }}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Cód.</label>
                                <input type="text" class="form-control" name="id" id="id" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Úsuário Dashboard Junsoft</label>
                                <select class="form-control" name="cd_usuario_modal" id="cd_usuario_modal"
                                    style="width: 100%">
                                    @foreach ($user as $u)
                                        <option value="{{ $u->id }}">{{ strtoupper($u->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input id="token" name="_token" type="hidden" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label>Região Junsoft</label>
                                <select class="form-control" name="cd_regiaocomercial_modal" id="cd_regiaocomercial_modal"
                                    style="width: 100%;">
                                    @foreach ($regiao as $r)
                                        <option value="{{ $r->CD_REGIAOCOMERCIAL }}">{{ $r->DS_REGIAOCOMERCIAL }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning btn-update">Editar</button>
                        <button type="button" class="btn btn-danger btn-cancel" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    @includeIf('admin.master.datatables')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#cd_regiaocomercial').select2();
            $('#cd_regiaocomercial_modal').select2();
            $('#cd_usuario').select2();
            $('#cd_usuario_modal').select2();
            $('#btn-vincular').click(function() {
                let ds_regiaocomercial = $("#cd_regiaocomercial option:selected").text()
                let cd_regiaocomercial = $('#cd_regiaocomercial').val();
                let cd_usuario = $('#cd_usuario').val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('get-regiao-comercial.create') }}",
                    data: {
                        cd_regiaocomercial: cd_regiaocomercial,
                        cd_usuario: cd_usuario,
                        ds_regiaocomercial: ds_regiaocomercial
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(result) {
                        $("#loading").addClass('hidden');
                        if (result.errors) {
                            setTimeout(function() {
                                $(".alert").removeClass('alert-success hidden');
                                $(".alert").addClass('alert-warning');
                                $(".alert p").html('<i class="icon fa fa-ban"></i> ' +
                                    result.errors);
                            }, 400);
                            window.setTimeout(function() {
                                //$(".alert").alert('close');
                                $(".alert").removeClass('alert-warning');
                                $(".alert").addClass('hidden');
                            }, 3000);
                        } else {
                            //alert(result.success);                            
                            setTimeout(function() {
                                $(".alert").removeClass('alert-warning hidden');
                                $(".alert").addClass('alert-success');
                                $(".alert p").html('<i class="icon fa fa-check"></i> ' +
                                    result.success);
                            }, 400);
                            window.setTimeout(function() {
                                //$(".alert").alert('close');
                                $(".alert").addClass('hidden');
                                $('#table-regiao').DataTable().ajax.reload()
                            }, 3000);
                        }
                    }
                });
            });
            var dataTable = $('#table-regiao').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('get-table-regiao-usuario') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'cd_usuario',
                        name: 'cd_usuario',
                        visible: false,
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'cd_regiaocomercial',
                        name: 'cd_regiaocomercial',
                        visible: false,
                    },
                    {
                        data: 'ds_regiaocomercial',
                        name: 'ds_regiaocomercial'
                    },
                    {
                        data: 'Actions',
                        name: 'Actions'
                    }
                ],
                pageLength: 20,
                order: [2, 'asc'],
                language: {
                    url: "http://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                responsive: true,
            });
            //dataTable e um variavel que tras a informação da tabela.
            $('#table-regiao').on('click', '.btn-edit', function() {
                var rowData = dataTable.row($(this).parents('tr')).data();
                console.log(rowData);
                $('#id').val(rowData.id);
                $('#cd_regiaocomercial_modal').val(rowData.cd_regiaocomercial).trigger('change');
                $('#cd_usuario_modal').val(rowData.cd_usuario).trigger('change');
                $('.modal').modal('show');
            });
            $('.btn-update').click(function() {
                if (!confirm("Você tem certeza que deseja atualizar?")) return;
                let id = $('#id').val(),
                    cd_regiaocomercial = $('#cd_regiaocomercial_modal').val(),
                    ds_regiaocomercial = $("#cd_regiaocomercial_modal option:selected").text(),
                    cd_usuario = $('#cd_usuario_modal').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('edit-regiao-usuario') }}',
                    data: {
                        id: id,
                        cd_regiaocomercial: cd_regiaocomercial,
                        cd_usuario: cd_usuario,
                        ds_regiaocomercial: ds_regiaocomercial,
                        _token: $('#token').val(),
                    },
                    beforeSend: function() {

                    },
                    success: function(result) {
                        setTimeout(function() {
                            $(".alert").removeClass('alert-warning hidden');
                            $(".alert").addClass('alert-success');
                            $(".alert p").html('<i class="icon fa fa-check"></i> ' +
                                result.success);
                        }, 400);
                        window.setTimeout(function() {
                            //$(".alert").alert('close');
                            $(".alert").addClass('hidden');
                            $('.modal').modal('hide');
                            $('#table-regiao').DataTable().ajax.reload()
                        }, 3000);
                    }
                });
            });
            //Delete Região/usuario
            var deleteId;
            $('body').on('click', '#getDeleteId', function() {
                deleteId = $(this).data('id');
                if (!confirm('Deseja realmente excluir o item ' + deleteId + ' ?')) return;
                console.log(deleteId);

                $.ajax({
                    url: "{{route('regiao-usuario.delete')}}",
                    method: 'DELETE',
                    data: {
                        id: deleteId,
                        "_token": $("[name=csrf-token]").attr("content"),
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(result) {
                        if (result.alert) {
                            $("#loading").addClass('hidden');
                            alert(result.alert);

                        } else {
                            $("#loading").addClass('hidden');
                            $('#table-regiao').DataTable().ajax.reload();
                            alert(result);
                        }
                    }
                });
            });

        });
    </script>
@endsection
