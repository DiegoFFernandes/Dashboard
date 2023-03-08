@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#follow-up" data-toggle="tab" aria-expanded="true">Follow UP</a>
                        </li>
                        <li class="">
                            <a href="#iagente" data-toggle="tab" aria-expanded="false">Iagente</a>
                        </li>
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="tab-pane active" id="follow-up">
                            <div class="box-body">
                                <div class="col-md-4" style="background-color: #ecf0f5">
                                    <div class="box-header with-border" style="border-bottom: 1px solid #d1d1d1;">
                                        <h3 class="box-title" style="text-align: center">Pesquisar Envios Automaticos
                                        </h3>
                                    </div>
                                    <div class="col-md-6 pt-3">
                                        <div class="form-group" style="padding-top: 15px">
                                            <label for="search-number">Nº Nota/Boleto</label>
                                            <input type="number" class="form-control" id="search-number"
                                                placeholder="Número" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group border-bottom">
                                            <label for="search-number">Pesquisa Avançada</label>
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-box-tool" data-toggle="collapse"
                                                    data-target="#search-advanced"><i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse" id="search-advanced">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="cd_pessoa">Cd. Cliente</label>
                                                <input type="number" class="form-control" id="cd_pessoa"
                                                    placeholder="Código">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="cd_pessoa">Cpf/CNPJ</label>
                                                <input type="text" class="form-control" id="cpf_cnpj"
                                                    placeholder="Cpf/CNPJ">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="nm_pessoa">Razão Social</label>
                                                <input type="text" class="form-control" id="nm_pessoa"
                                                    placeholder="Nome Pessoa">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="ds_email_pessoa">Email</label>
                                                <input type="email" class="form-control" id="ds_email_pessoa"
                                                    placeholder="Email Pessoa">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="nr_contexto">Tipo de Disparo</label>
                                                <select class="form-control select2" name="nr_contexto" id="nr_contexto"
                                                    style="width: 100%;">
                                                    <option value="0" selected="selected">Selecione</option>
                                                    @foreach ($contexto as $c)
                                                        <option value="{{ $c->NR_CONTEXTO }}">{{ $c->DS_CONTEXTO }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Periodo</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                    <input type="text" class="form-control pull-right" id="daterange"
                                                        value="" autocomplete="off">
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success pull-right"
                                        id="submit-seach">Pesquisar</button>
                                </div>
                                <div class="col-md-8" style="background-color: #ecf0f5">
                                    <div class="box-header with-border" style="border-bottom: 1px solid #d1d1d1;">
                                        <h3 class="box-title">Resultado pesquisa - Envios Automaticos</h3>
                                        <div class="box-tools pull-right">
                                            <a href="https://chrome.google.com/webstore/detail/enable-local-file-links/nikfmfgobenbhmocjaaboihbeocackld"
                                                class="btn btn-box-tool btn-xs btn-warning"
                                                style="color: rgb(2, 0, 0)">Instale
                                                a Extensão
                                                para ver o Anexo</a>
                                        </div>
                                    </div>
                                    <div id="search" style="padding-top: 15px">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="iagente">
                            {{-- <input id="token" name="_token" type="hidden" value="{{ csrf_token() }}"> --}}
                            <div class="box-body">
                                <div class="col-md-12" style="background-color: #ecf0f5">
                                    <div style="padding-bottom: 15px">
                                        <div class="box-header with-border" style="border-bottom: 1px solid #d1d1d1;">
                                            <h3 class="box-title" style="text-align: center;">Emails Inexistentes</h3>
                                        </div>
                                    </div>
                                    <table class="table compact" id="table-iagente">
                                        <thead>
                                            <th>Ações</th>
                                            <th>Cod.</th>
                                            <th>Cliente</th>
                                            <th>Email</th>                                            
                                            <th>Modulo</th>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




        </div>
        <div class="modal fade" id="modal-email" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Disparo Automático</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted"><i class='fa fa-thumb-tack'></i>
                            Essa mensagem e de um disparo automátivo, cliente deve verificar se não esta na caixa de spam,
                            ou no lixo eletrônico, caso ele não receber!
                        </p>
                        <div class="form-group">
                            <label for="assunto">Assunto:</label>
                            <input type="text" class="form-control" id="assunto" disabled>
                        </div>
                        <div class="form-group">
                            <label for="from">De:</label>
                            <input type="text" class="form-control" id="from" disabled>
                        </div>
                        <div class="form-group">
                            <label for="to">Para:</label>
                            <input type="text" class="form-control" id="to" disabled>
                        </div>
                        <div class="form-group">
                            <label for="message">Mensagem:</label>
                            <textarea class="form-control" type="textarea" id="message" rows="7" disabled></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var inicioData = 0;
            var fimData = 0;
            var update_email = 0;
            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY HH:mm'));
                inicioData = picker.startDate.format('MM/DD/YYYY HH:mm');
                fimData = picker.endDate.format('MM/DD/YYYY HH:mm');
            });
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val("");
                inicioData = 0;
                fimData = 0;
            });

            $('#submit-seach').click(function() {
                let cd_number = $("#search-number").val();
                if (cd_number == "") {
                    $('#search-number').attr('title', 'Código para buscar é obrigatório!').tooltip('show');
                    return false;
                }
                let cd_pessoa = $("#cd_pessoa").val();
                let nm_pessoa = $("#nm_pessoa").val();
                let cpf_cnpj = $("#cpf_cnpj").val();
                let nr_contexto = $("#nr_contexto").val();
                let ds_email_pessoa = $("#ds_email_pessoa").val();

                $.ajax({
                    url: "{{ route('get-search-envio') }}",
                    method: "GET",
                    data: {
                        cd_number: cd_number,
                        cd_pessoa: cd_pessoa,
                        nm_pessoa: nm_pessoa,
                        cpf_cnpj: cpf_cnpj,
                        ds_email: ds_email_pessoa,
                        nr_contexto: nr_contexto,
                        inicio_data: inicioData,
                        fim_data: fimData,
                    },
                    beforeSend: function() {
                        $("#table-search").DataTable().destroy();
                        $("#loading").removeClass('hidden');
                    },
                    success: function(result) {
                        $("#loading").addClass('hidden');
                        $("#table-search").remove();
                        $("#search").append(result);
                        $("#table-search").DataTable({
                            language: {
                                url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                            },
                            responsive: true,
                            "order": [
                                [1, "desc"]
                            ],
                        });
                    }
                });
            });

            $('#daterange').daterangepicker({
                //opens: 'left',
                autoUpdateInput: false,
                // timePicker: true,
                //timePickerIncrement: 30,
                // locale: {
                //     format: 'MM/DD/YYYY HH:mm',

                // }
            });
            $(document).on('click', '.ver-email', function(e) {
                let nr_envio = $(this).data('id');
                let url = "{{ route('get-email-follow', ':nr_envio') }}";
                url = url.replace(":nr_envio", nr_envio);
                $.ajax({
                    url: url,
                    method: 'GET',
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(result) {
                        $("#loading").addClass('hidden');
                        $('#modal-email').modal('show');
                        // $('.modal-title').text();
                        // let ds_mensagem = result[0].DS_MENSAGEM;
                        // //ds_mensagem = ds_mensagem.replace(/[#10]/g, "");
                        // $('.modal-body').html(ds_mensagem);
                        $('#assunto').val(result[0].DS_ASSUNTO);
                        $('#from').val(result[0].DS_EMAILREM);
                        $('#to').val(result[0].DS_EMAILDEST);
                        // $('#message').val(text().html());
                        $('#message').val($('<div/>').html(result[0].DS_MENSAGEM).text());
                    }
                });
            });
            $(document).on('click', '.reenviar-email', function(e) {
                let nr_envio = $(this).data('id');    
                
                toastr.warning(
                    "<button type='button' id='confirmationButtonYes' class='btn btn-success clear'>Sim</button> " +
                    "<button type='button' id='confirmationButtonNo' class='btn btn-primary clear'>Não</button>",
                    'Deseja encaminhar uma copia para o seu email, para ter certeza que chegou?', {
                        closeButton: false,
                        allowHtml: true,
                        progressBar: false,
                        timeOut: 0,
                        positionClass: "toast-top-center",
                        onShown: function(toast) {
                            $("#confirmationButtonYes").click(function() {
                                update_email = 1;   
                                ReenviaFollow(nr_envio, update_email);                            
                            });
                            $("#confirmationButtonNo").click(function() {
                                update_email = 0;   
                                ReenviaFollow(nr_envio, update_email);                              

                            });
                        }
                    });
                   
                
            });
            $(document).on('click', '#validar-email', function(e) {
                let email = $(this).data('cdpessoa');
                let token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('delete-email-webhook-iagente') }}",
                    data: {
                        email: email,
                        _token: token
                    },
                    success: function(response) {
                        if (response.error) {
                            // alert(result.error);
                            msgToastr(response.error, 'warning');
                            return false;
                        } else {
                            msgToastr(response.success, 'success');
                            $('#table-iagente').DataTable().ajax.reload();
                        }
                    }
                });
            });

            //Cliques nas tabs
            $('.nav-tabs a[href="#iagente"]').on('click', function() {
                $('#table-iagente').DataTable().destroy();
                $('#table-iagente').DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    pagingType: "simple",
                    processing: false,
                    dom: 'Blfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    ajax: {
                        url: "{{ route('get-envio-iagente') }}"
                    },
                    columns: [{
                            data: 'action',
                            name: 'action',

                        },
                        {
                            data: 'CD_PESSOA',
                            name: 'CD_PESSOA'
                        },
                        {
                            data: 'NM_PESSOA',
                            name: 'NM_PESSOA'
                        },
                        {
                            data: 'DS_EMAIL',
                            name: 'DS_EMAIL'
                        },
                        {
                            data: 'MODULO',
                            name: 'MODULO'
                        },
                    ],
                    columnDefs: [{
                            width: '1%',
                            targets: 0
                        },
                        {
                            width: '1%',
                            targets: 1
                        },
                        {
                            width: '35%',
                            targets: 2
                        }
                    ],
                });
            });

            function ReenviaFollow(nr_envio, update_email){
                $.ajax({
                    url: "{{ route('reenvia-follow') }}",
                    method: 'POST',
                    data:{
                        _token: $("[name=csrf-token]").attr("content"),
                        nr_envio: nr_envio,
                        email: update_email
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');
                        if(response.error){
                            msgToastr(response.error, 'warning');
                        }else{
                            msgToastr(response.success, 'success');
                        }
                    }
                });
            }

        });
    </script>
@endsection
