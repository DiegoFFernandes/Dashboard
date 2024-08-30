@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-5">
                <div class="box box-warning">
                    <div class="box-header withborder">
                        <h3 class="box-title">Deseja cancelar uma nota?</h3>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-warning alert-fixed hidden">
                            <p><i class="icon fa fa-check"></i></p>
                        </div>
                        @includeIf('admin.master.messages')
                        <input type="text" class="hidden" id="nr_lancamento" name="nr_lancamento">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="cd_empresa">Empresa</label>
                                <select name="cd_empresa" id="cd_empresa" class="form-control" style="width: 100%;">
                                    <option value="3">SUPER RODAS RECAPAGENS</option>
                                    <option value="1">AM MORENO PNEUS LTDA</option>
                                    <option value="2">SUPER RODAS L9</option>
                                    <option value="12">AM MORENO L9</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id">Série Nota</label>
                                <select name="nr_serie" id="nr_serie" class="form-control" style="width: 100%;">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="10">10</option>
                                    <option value="F">F</option>
                                    <option value="M">M</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="id">Numero Nota</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="id_nota" placeholder="Número Nota">
                                    <div class="input-group-addon" id="btn-search-nota">
                                        <button><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nm_pessoa">Cliente</label>
                                <input type="text" class="form-control" id="nm_pessoa" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nr_cnpjcpf">CPF/CNPJ</label>
                                <input type="text" class="form-control" id="nr_cnpjcpf" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Motivo</label>
                                <select class="form-control select2" style="width: 100%;" id="motivo">
                                    <option value="0" selected="selected">Selecione o motivo</option>
                                    @foreach ($motivo as $m)
                                        <option value="{{ $m->DS_MOTIVO }}">{{ $m->DS_MOTIVO }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observação</label>
                                <textarea class="form-control" rows="3" placeholder="Deseja comentar mais algo?" id="observacao"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success pull-right hidden" id="submit-cancela">Enviar
                            Pedido</button>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pedidos de cancelamento</h3>
                    </div>
                    <div class="box-body">
                        <table class="table" style="width:100%; font-size:12px" id="table-pedido-cancelar">
                            <thead>
                                <tr>
                                    <th>Emp</th>
                                    <th>Nota</th>
                                    <th>Pessoa</th>
                                    <th>Motivo</th>
                                    <th>Criado em:</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listNotasCancelar as $l)
                                    <tr>
                                        <td>{{ $l->cd_empresa }}</td>
                                        <td>{{ $l->nr_nota }}</td>
                                        <td>{{ $l->nm_pessoa }}</td>
                                        <td>{{ $l->motivo }}</td>
                                        <td>{{ $l->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer">

                    </div>
                </div>
            </div>
            {{-- Icon loading --}}
            <div class="hidden" id="loading">
                <img id="loading-image" class="mb-4" src="{{ Asset('img/loader.gif') }}" alt="Loading...">
            </div>
        </div>
    </section>
    <!-- /.row -->
    <!-- /.content -->
@endsection
@section('scripts')
    @includeIf('admin.master.datatables')
    <script type="text/javascript">
        $(document).ready(function() {
            let token = $("meta[name='csrf-token']").attr("content");
            $('.select2').select2();
            $('#btn-search-nota').click(function() {
                let cd_empresa = $('#cd_empresa').val();
                let nr_nota = $('#id_nota').val();
                let nr_serie = $('#nr_serie').val();
                if (nr_nota == "") {
                    $('#id_nota').attr('title', 'Nota e obrigatório!').tooltip('show');
                    return false;
                } else if (nr_serie == "") {
                    $('#nr_serie').attr('title', 'Série e obrigatório!').tooltip('show');
                    return false;
                };

                $.ajax({
                    url: '{{ route('comercial.search-nota') }}',
                    method: "GET",
                    data: {
                        cd_empresa: cd_empresa,
                        nr_nota: nr_nota,
                        nr_serie: nr_serie,
                    },
                    success: function(result) {
                        if (result.error) {
                            $(".alert").removeClass('hidden');
                            $(".alert p").text(result.error);
                            window.setTimeout(function() {
                                $(".alert").alert('close');
                            }, 3000);
                            $('#submit-cancela').addClass('hidden');
                        } else {
                            $('#nr_lancamento').val(result[0].NR_LANCAMENTO);
                            $('#nm_pessoa').val(result[0].NM_PESSOA);
                            $('#nr_cnpjcpf').val(result[0].NR_CNPJCPF);
                            $('#submit-cancela').removeClass('hidden');
                        }
                    }
                });
            });

            function verifica(nr_nota) {
                if (nr_nota == "") {
                    $('#id_nota').attr('title', 'Nota e obrigatório!').tooltip('show');
                    return false;
                }
            }

            $('#submit-cancela').click(function() {
                let motivo = $('#motivo').val();
                if (motivo == 0) {
                    $('#motivo').attr('title', 'Selecione o Motivo!').tooltip('show').tooltip('right');
                    return false;
                }
                $.ajax({
                    url: "{{ route('comercial.cancela-nota-do') }}",
                    method: "POST",
                    data: {
                        cd_empresa: $('#cd_empresa').val(),
                        nr_lancamento: $('#nr_lancamento').val(),
                        nr_nota: $('#id_nota').val(),
                        nr_cnpjcpf: $('#nr_cnpjcpf').val(),
                        nm_pessoa: $('#nm_pessoa').val(),
                        motivo: $('#motivo').val(),
                        observacao: $('#observacao').val(),
                        _token: token,
                    },
                    beforeSend: function(result) {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(result) {
                        $("#loading").addClass('hidden');
                        if (result.error) {
                            alert(result.error);
                        } else {
                            alert(result.success);
                            location.reload();
                        }
                    }
                });
            });

            $("#table-pedido-cancelar").DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                responsive: true,
                "order": [
                    [4, "desc"]
                ],
            });

        });
    </script>
@endsection
