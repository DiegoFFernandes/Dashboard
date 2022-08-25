@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4 pull-right">
                @includeIf('admin.master.messages')
            </div>
            <div class="col-md-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Liberação de Procedimentos</h3>
                    </div>
                    <div class="box-body">
                        <table id="table-procedimento" class="table">
                            <thead>
                                <th>Cód.</th>
                                <th>Setor</th>
                                <th>Titulo</th>
                                <th>Descrição</th>                                
                                <th>Resp.</th>
                                <th>Status</th>
                                <th>Acões</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Modal Autorizar Procedimento --}}
            <div class="modal modal-aut-procedimento fade" id="modal-aut-procedimento">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Autorizar Procedimento</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_procedimento">Cód.</label>
                                    <input class="form-control" type="number" id="id_procedimento" name="id_procedimento"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="setor">Setor designado</label>
                                    <input id="setor" class="form-control" type="text" readonly>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="user">Status</label>
                                    <select class="form-control bg-light-blue" id="status">
                                        <option selected value="L">LIBERAR</option>                                        
                                        <option value="R">RECUSAR</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_user">Cód. Aprovador</label>
                                    <input id="id_user" class="form-control" type="text" readonly>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="user">Usuario Responsavel</label>
                                    <input id="user" class="form-control" type="text" readonly>
                                </div>
                            </div>                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Titulo</label>
                                    <input id="title" type="text" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Descrição</label>
                                    <textarea id="description" class="form-control" rows="4" cols="50" readonly></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 hidden" id="recusa">
                                <div class="form-group">
                                    <label for="description_recusa">Motivo Recusa</label>
                                    <textarea id="description_recusa" class="form-control" rows="4" cols="50"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <button class="btn btn-success btn-block" id="btnSave">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Salvar Decisão</button>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $(".users").select2();
            var status, recusa;
            var dataTable = $("#table-procedimento").DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                pagingType: "simple",
                processing: false,
                responsive: true,
                serverSide: false,
                autoWidth: false,
                order: [
                    [0, "desc"]
                ],
                "pageLength": 10,
                ajax: "{{ route('procedimento.get-procedimento-aprovador') }}",
                columns: [{
                        data: 'id_procedimento',
                        name: 'id_procedimento'
                    },
                    {
                        data: 'nm_setor',
                        name: 'nm_setor'
                    },
                    {
                        data: 'title',
                        name: 'title',
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },                    
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'aprovado',
                        name: 'aprovado'
                    },
                    {
                        data: 'Actions',
                        name: 'Actions',
                        orderable: false,
                        serachable: false,
                        sClass: 'text-center'
                    }
                ],
                columnDefs: [{
                    "visible": false,
                    "targets": [2, 3]
                }]
            });
            $('body').on('click', '#getSave', function(e) {
                e.preventDefault();
                var rowData = dataTable.row($(this).parents('tr')).data();
                $("#id_procedimento").val(rowData.id_procedimento);
                $("#setor").val(rowData.nm_setor);
                $("#user").val(rowData.name);
                $("#id_user").val(rowData.id_user)
                $("#title").val(rowData.title);
                $("#description").val(rowData.description);
                $('#modal-aut-procedimento').modal('show');
            });
            $("#btnSave").click(function() {
                var id = $("#id_procedimento").val();                
                var id_user = $("#id_user").val();
                var description_recusa = $('#description_recusa').val();
                status = $("#status").val();
                if(status == 'R' && description_recusa == ""){
                    $('#description_recusa').attr('title', 'Para recusar um procedimento é obrigatório informar um motivo!')
                                .tooltip('show');
                    return false;
                }
                $.ajax({
                    type: "GET",
                    url: "{{ route('procedimento.aprovador.store') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        user: id_user,
                        status: status,
                        id: id,
                        description_recusa: description_recusa
                    },
                    success: function(response) {
                        if (response.alert) {
                            $("#loading").addClass('hidden');
                            $('#modal-aut-procedimento').modal('hide');                            
                            msg(response.alert, 'alert-warning', 'fa-warning');
                            dataTable.ajax.reload();
                        } else {
                            $("#loading").addClass('hidden'); 
                            $('#modal-aut-procedimento').modal('hide');                            
                            msg(response.success, 'alert-success', 'fa fa-check');
                            dataTable.ajax.reload();
                        }
                    }
                });
            });
            $( "#status").change(function(){
                status = $('#status').val();
                if(status == 'R'){
                    $('#recusa').removeClass('hidden');
                }else{
                    $('#recusa').addClass('hidden');
                }
            });
        });
    </script>
@endsection
