@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pesquisar Envios Automaticos</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="search-number">Nº Nota/Boleto</label>
                                <input type="number" class="form-control" id="search-number" placeholder="Número" required>
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
                                    <input type="number" class="form-control" id="cd_pessoa" placeholder="Código">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="cd_pessoa">Cpf/CNPJ</label>
                                    <input type="text" class="form-control" id="cpf_cnpj" placeholder="Cpf/CNPJ">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nm_pessoa">Razão Social</label>
                                    <input type="text" class="form-control" id="nm_pessoa" placeholder="Nome Pessoa">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nr_contexto">Tipo de Disparo</label>
                                    <select class="form-control select2" name="nr_contexto"
                                        id="nr_contexto" style="width: 100%;">
                                        <option value="0" selected="selected">Selecione</option>
                                        @foreach ($contexto as $c)
                                            <option value="{{ $c->NR_CONTEXTO }}">{{ $c->DS_CONTEXTO }}</option>
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
                                        <input type="text" class="form-control pull-right" id="daterange" value=""
                                            autocomplete="off">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success pull-right" id="submit-seach">Pesquisar</button>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Resultado pesquisa - Envios Automaticos</h3>
                        <div class="box-tools pull-right">
                            <a href="https://chrome.google.com/webstore/detail/enable-local-file-links/nikfmfgobenbhmocjaaboihbeocackld"
                                class="btn btn-box-tool btn-xs btn-warning">Instale a Extensão para ver o Anexo</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="search">

                        </div>
                    </div>
                </div>
            </div>
            {{-- Icon loading --}}
            <div class="hidden" id="loading">
                <img id="loading-image" class="mb-4" src="{{ Asset('img/loader.gif') }}" alt="Loading...">
            </div>
        </div>
        <div class="modal fade" id="modal-email" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Assunto</h4>
                    </div>
                    <div class="modal-body">
                        Mensagem
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
                
                $.ajax({
                    url: "{{ route('get-search-envio') }}",
                    method: "GET",
                    data: {
                        cd_number: cd_number,
                        cd_pessoa: cd_pessoa,
                        nm_pessoa: nm_pessoa,
                        cpf_cnpj: cpf_cnpj,
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
                                url: "http://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
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
                        $('.modal-title').text(result[0].DS_ASSUNTO);
                        let ds_mensagem = result[0].DS_MENSAGEM;
                        //ds_mensagem = ds_mensagem.replace(/[#10]/g, "");
                        $('.modal-body').html(ds_mensagem);
                    },

                });
            });

        });
    </script>
@endsection
