@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Maquinas Paradas</span>
                        <span class="info-box-number">{{ $parada }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class=""></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Chamados Abertos/Andamento</span>
                        <span class="info-box-number">{{ $aberto }}</span>
                    </div>
                </div>
            </div>


            <div class="clearfix visible-sm-block"></div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Chamados Finalizados</span>
                        <span class="info-box-number">{{ $finalizado }}</span>
                    </div>
                </div>

            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Chamados Total</span>
                        <span class="info-box-number">{{ $total }}</span>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li>
                            <a href="#new-ticket" data-toggle="tab" aria-expanded="true">Criar Chamado</a>
                        </li>
                        <li class="active">
                            <a href="#tickets-existing" data-toggle="tab" aria-expanded="false">Chamados</a>
                        </li>
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="tab-pane" id="new-ticket">
                            <form action="{{ route('manutencao.store') }}" id="form-ticket" method="post"
                                enctype="multipart/form-data">
                                <div class="box-body">
                                    @csrf
                                    <div class="col-md-12" style="background-color: #ecf0f5; padding-top:15px">

                                        <div class="col-md-5">
                                            <label for="">Urgência:</label>
                                            <div class="form-group">
                                                <select class="form-control" name="urgencia" id="prioridade"
                                                    style="width: 100%;" required>
                                                    <option value="B" selected>Baixa</option>
                                                    <option value="M">Média</option>
                                                    <option value="A">Alta</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="cd_etapa"><button type="button"
                                                    class="btn btn-block btn-primary btn-xs btn-qr" data-toggle="modal"
                                                    data-target="#modal-default">Cód. Maq. (QR
                                                    Code)*</button></label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name='cd_maq' id="cd_maq"
                                                    width="100" placeholder="1111" required>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="etapas">Etapa Maquinas:</label>
                                                <select class="form-control select maquina" style="width: 100%;">
                                                    <option value="0" selected>Selecione uma Maquina</option>
                                                    @foreach ($maquinas as $m)
                                                        <option value="{{ $m->cd_barras }}">{{ $m->ds_maquina }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">Maquina parada:</label>
                                            <div class="form-group">
                                                <label class="radio-inline">
                                                    <input name="mq_parada" type="radio" value="S" checked>
                                                    Sim
                                                </label>

                                                <label class="radio-inline">
                                                    <input name="mq_parada" type="radio" value="N">
                                                    Não
                                                </label>

                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <label for="">Tipo problema:</label>
                                            <div class="form-group">
                                                <select class="form-control" name="tp_chamado" id="tp_chamado"
                                                    style="width: 100%;" required>
                                                    <option value="0" selected="selected">Selecione</option>
                                                    <option value="E">Eletrico</option>
                                                    <option value="M">Mecânica</option>
                                                    <option value="P">Pneumatica</option>
                                                    <option value="O">Outro</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="arquivo">Buscar imagens:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-pdf-o"></i></span>
                                                <input type="file" name="file[]" class="form-control"
                                                    placeholder="Clique/Arraste e Solte aqui"
                                                    accept="image/.jpg, .jpeg, .png" multiple>

                                            </div>
                                            <p class="help-block">Somente imagens.</p>
                                        </div>
                                        <div class="col-md-12" style="padding-bottom: 15px">
                                            <label for="observacao">Breve descrição:</label>
                                            <div class="form-group">
                                                <textarea class="form-control" name="observacao" id="observacao" cols="30" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button class="btn btn-success pull-right" id="btn-send">Enviar Chamado</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane active" id="tickets-existing">
                            {{-- <input id="token" name="_token" type="hidden" value="{{ csrf_token() }}"> --}}
                            <div class="box-body">

                                <div class="col-md-12" style="background-color: #ecf0f5">
                                    <div style="padding-bottom: 15px">
                                        <div class="box-header with-border" style="border-bottom: 1px solid #d1d1d1;">
                                            <h3 class="box-title" style="text-align: center;">Lista de chamados</h3>
                                            <div class="box-tools pull-right">
                                                <!-- checkbox -->
                                                <div class="form-group">
                                                    <label>
                                                        <input type="checkbox" class="minimal" name="status"
                                                            value="R" checked>
                                                        Reaberto
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" class="minimal" name="status"
                                                            value="P" checked>
                                                        Pendentes
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" class="minimal" name="status"
                                                            value="A" checked>
                                                        Andamento
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" class="minimal" name="status"
                                                            value="F">
                                                        Finalizado
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table compact" id="table-tickets" style="font-size: 12px">
                                        <thead>
                                            <th>Cód</th>
                                            <th>Máquina</th>
                                            <th>Urgencia</th>
                                            <th>Tipo</th>
                                            <th>Parada</th>
                                            <th>Solicitante</th>
                                            <th>Emp</th>
                                            <th>Status</th>
                                            <th>Criado em</th>
                                            <th>#</th>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal View Leitura QrCode --}}
        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Aponte a Camera para o QR Code</h4>
                    </div>
                    <div class="modal-body">
                        <div id="qr-reader" style="width: auto; display: none"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left btn-close"
                            data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal View Andamento Tickets --}}
        <div class="modal fade" id="modal-andamento">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <div class="">
                            <button class="btn btn-primary" id="acompanhamento">Acompanhamento</button>
                            <button class="btn btn-warning" id="finalizar">Finalizar</button>
                            <button class="btn btn-danger" id="reabrir" style="display: none">Reabrir</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('manutencao.status') }}" method="post" style="display: none"
                            id="form-acompanhamento">
                            <div style="padding-bottom: 30px;">
                                @csrf
                                <input type="text" class="hidden" name="status_ticket" id="status_ticket">
                                <div class="col-md-3" style="padding-bottom: 15px">
                                    <label for="cd_ticket">Cód:</label>
                                    <div class="form-group">
                                        <input class="form-control" name="cd_ticket" id="cd_ticket" readonly>
                                    </div>
                                </div>
                                <div class="col-md-9" style="padding-bottom: 15px">
                                    <label for="observacao">Maquina:</label>
                                    <div class="form-group">
                                        <input class="form-control" name="maquina" id="maquina" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12" style="padding-bottom: 15px">
                                    <label for="observacao" class="label-acompanhamento">Acompanhamento:</label>
                                    <div class="form-group">
                                        <textarea class="form-control" name="observacao" id="ds_acompanhamento" cols="30" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-success" id="btn-save"
                                        data-dismiss="modal">Salvar</button>
                                </div>
                            </div>
                        </form>
                        <div class="col-md-12" style="padding-top: 15px;">
                            <div class="box box-primary direct-chat direct-chat-warning">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Histórico de Ações:</h3>
                                </div>
                                <div class="box-body direct-chat-success" id="box-chat">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left btn-close"
                            data-dismiss="modal">Sair</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal View Pìctures Tickets --}}
        <div class="modal modal-pictures fade" id="modal-pictures">
            <div class="modal-dialog" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title"><i class="fa fa-picture-o"></i> Imagens
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="post">
                            <div class="row margin-bottom">
                                <div class="col-sm-12">
                                    <div class="row" id="response-pictures">

                                    </div>
                                </div>
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
            // // iCheck for checkbox and radio inputs
            // $('input[type="checkbox"].minimal').on('ifChecked ifUnchecked', function(event){
            //   console.log(this.id)
            // }).iCheck({
            //     checkboxClass: 'icheckbox_minimal-red',
            //     radioClass: 'iradio_minimal-red'
            // });
            var status;

            status = ArrCheck();

            $('.minimal').click(function() {
                status = ArrCheck();
                console.log(status);
                if (status.length === 0) {
                    msgToastr('Algum campo de status deve estar preenchido', 'warning');
                    event.preventDefault();
                } else {
                    $('#table-tickets').DataTable().clear().destroy();
                    console.log(status);
                    initTable(status);
                }

            });

            $('#btn-send').click(function() {
                // console.log($('.maquina').val());
                // return false;
                if ($('#tp_chamado').val() == 0 || $('#observacao').val() === '' || $('#cd_maq').val() ===
                    '') {
                    msgToastr(
                        'Selecione os campos obrigatorios Tipo problema / Breve descrição / Cód Maquina!',
                        'warning');
                    event.preventDefault();
                } else if ($('.maquina').val() === null) {
                    msgToastr('Etapa maquina deve ser selecionada!', 'warning');
                    event.preventDefault();
                } else {
                    $('#form-ticket').submit();
                }
            })
            const html5QrCode = new Html5Qrcode("qr-reader");
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                $('#cd_maq').val(decodedText);
                var teclaEnter = jQuery.Event("keypress");
                teclaEnter.ctrlKey = false;
                teclaEnter.which = 9; //Código da tecla Enter
                console.log(decodedText);
                $("#cd_maq").trigger(teclaEnter);
                $(".btn-close").trigger('click');
            };
            const config = {
                fps: 10,
                qrbox: 250
            };
            $("#cd_maq").on("keypress focusout", function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                var cd_barras = $("#cd_maq").val();
                if (keycode == '9' || keycode == '13' || event.type == "focusout") {
                    $('.maquina').val(cd_barras).trigger('change');
                    return false;

                }
            });
            $('.maquina').select2();
            $('.maquina').change(function(){
                if($(this).val() == 0){
                    $('#cd_maq').val('');
                }else{
                    $('#cd_maq').val($(this).val());
                }             
            })

            $('.btn-qr').click(function() {
                $('#qr-reader').css("display", "block");
                // Select front camera or fail with `OverconstrainedError`.
                // html5QrCode.start({ facingMode: { exact: "environment"} }, config, qrCodeSuccessCallback);
                html5QrCode.start({
                    facingMode: {
                        exact: "environment" //environment user - Camera traseira

                    }
                }, config, qrCodeSuccessCallback);
            });
            $('.btn-close').click(function() {

                html5QrCode.stop().then(ignore => {
                    // QR Code scanning is stopped.                     
                    console.log('QR Code scanning stopped.');
                    $('#modal-default').modal('hide');

                }).catch(err => {
                    // Stop failed, handle it. 
                    console.log('Unable to stop scanning.');
                });
                html5QrCode.clear().then(_ => {
                    // the UI should be cleared here      
                }).catch(error => {
                    // Could not stop scanning for reasons specified in `error`.
                    // This conditions should ideally not happen.
                });

                $('#qr-reader').css("display", "none");

            });
            $('#submit-seach').click(function() {
                let cd_empresa = $("#cd_empresa").val();
                let nm_empresa = $("#cd_empresa :selected").text();

                if (cd_empresa == 0) {
                    $('#cd_empresa').attr('title', 'Empresa é obrigatório!').tooltip('show');
                    return false;
                } else if (inicioData == "") {
                    alert('Período deve ser preenchida!');
                    $('#daterange').attr('title', 'Período é obrigatório!').tooltip('show');
                    return false;
                }

            });
            initTable(status);

            function ArrCheck() {
                var arr = [];
                $.each($("input[name='status']:checked"), function() {
                    arr.push($(this).val());
                });
                return arr;
            }

            function initTable(status) {
                var table = $('#table-tickets');
                table.DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    "pageLength": 50,
                    responsive: true,
                    pagingType: "simple",
                    processing: false,
                    ajax: {
                        url: "{{ route('manutencao.get-tickets') }}",
                        data: {
                            status: status
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id',
                        },
                        {
                            data: 'maquina',
                            name: 'maquina',
                        },
                        {
                            data: 'prioridade',
                            name: 'prioridade',
                        },
                        {
                            data: 'tp_problema',
                            name: 'tp_problema',
                        },
                        {
                            data: 'parada',
                            name: 'parada',
                        },
                        {
                            data: 'name',
                            name: 'name',
                        },
                        {
                            data: 'empresa',
                            name: 'empresa',
                        },
                        {
                            data: 'status',
                            name: 'status',
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                        },
                    ],
                    order: [
                        [0, "desc"]
                    ],
                });
            }

            //Cliques nas tabs
            $('.nav-tabs a[href="#tickets-existing"]').on('click', function() {
                $('#table-tickets').DataTable().ajax.reload();
            });
            $('body').on('click', '#ticket-andamento', function(e) {
                var ticketId = $(this).data('id');
                var maquina = $(this).data('maquina');
                var status = $(this).data('status');

                $.ajax({
                    type: "GET",
                    url: "{{ route('manutencao.chat') }}",
                    data: {
                        id: ticketId
                    },
                    success: function(response) {
                        $('.direct-chat-messages').remove();

                        $('#box-chat').append(response.html);
                    }
                });

                if (status == 'Finalizado') {
                    $('#reabrir').show();
                    $('#finalizar').hide();
                    $('#acompanhamento').hide();
                } else {
                    $('#reabrir').hide();
                    $('#finalizar').show();
                    $('#acompanhamento').show();
                }
                $('#cd_ticket').val(ticketId);
                $('#maquina').val(maquina);
                $('#modal-andamento').modal('show');

            });
            $('body').on('click', '#pictures', function(e) {
                var ticketId = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "{{ route('manutencao.pictures') }}",
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        id: ticketId,
                    },
                    success: function(response) {
                        $('#pictures-img').remove();
                        $('#response-pictures').append(response.html);
                        $('#modal-pictures').modal('show');
                    }
                });
            });
            $('#finalizar').click(function() {
                $('#form-acompanhamento').show();
                $('.label-acompanhamento').html('Solução:');
                $('#status_ticket').val('F');
            });
            $('#acompanhamento').click(function() {
                $('#form-acompanhamento').show();
                $('.label-acompanhamento').html('Acompanhamento:')
                $('#status_ticket').val('A');
            });
            $('#reabrir').click(function() {
                var id = $('#cd_ticket').val();
                if (confirm('Deseja realmente reabrir o chamado ' + id + '?')) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('manutencao.reopen') }}",
                        data: {
                            _token: $("[name=csrf-token]").attr("content"),
                            id: id,
                            status_ticket: "R"
                        },
                        success: function(response) {
                            msg(response.success, 'alert-success');
                            $('#modal-andamento').modal('hide');
                            $('#table-tickets').DataTable().ajax.reload();
                        }
                    });
                };

            });
            $('#btn-save').click(function() {
                var status = $('#status_ticket').val();
                var ds_acompanhamento = $('#ds_acompanhamento').val();
                if (ds_acompanhamento === '') {
                    msgToastr('Escreva uma breve descrição!', 'warning');
                    return false;
                } else {
                    $('#form-acompanhamento').submit();
                }

            });

        });
    </script>
@endsection
