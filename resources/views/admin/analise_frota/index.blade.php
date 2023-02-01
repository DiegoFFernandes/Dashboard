@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Analises Cadastradas:</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm badge bg-green" data-toggle="modal"
                                data-target="#modal-create"><i class="fa fa-plus"></i>
                            </button>

                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table compact stripe" id="table-analise" style="width: 100%">
                            <thead>
                                <th>Análise</th>
                                <th>Cliente</th>
                                <th>Placa</th>
                                <th>Modelo</th>
                                <th>Sulco</th>
                                <th>Ps. Min</th>
                                <th>Ps. Max</th>
                                <th>#</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-create">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Incluir Analise:</h4>
                    </div>
                    <div class="modal-body">
                        <form id="form-analysis">
                            <div class="col-md-8 col-xs-12">
                                @includeIf('admin.master.pessoa')
                            </div>
                            <div class="col-md-4 col-xs-8">
                                <div class="form-group">
                                    <label for="password">Placa:</label>
                                    <input type="text" name='placa' class="form-control" id="placa"
                                        placeholder="AA1A-0000" value="" required>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-4">
                                <div class="form-group">
                                    <label for="sulco">Sulco Ideal:</label>
                                    <input type="number" name='sulco' class="form-control" id="sulco" placeholder="4"
                                        value="" required>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="modelo_veiculo">Modelo Veiculo:</label>
                                    <select class="form-control" name="modelo_veiculo" id="modelo_veiculo" required
                                        style="width: 100%">
                                        <option value=" {!! Crypt::encrypt(0) !!}" selected>Selecione um Modelo</option>
                                        @foreach ($modelo as $m)
                                            <option value=" {!! Crypt::encrypt($m->id) !!}">
                                                {{ $m->dsmarca . ' - ' . $m->dsmodelo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <div class="form-group">
                                    <label for="ps_min">Pressão Min.:</label>
                                    <input type="number" name='ps_min' class="form-control" id="ps_min"
                                        placeholder="110" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <div class="form-group">
                                    <label for="ps_max">Pressão Max.:</label>
                                    <input type="number" name='ps_max' class="form-control" id="ps_max"
                                        placeholder="120" value="" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                    <label for="obs">Observação:</label>
                                    <textarea class="form-control" rows="2" name="obs" id="obs"
                                        placeholder="Precisa informar alguma informação? Digite aqui!"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary"id="create-analysis">Criar</button>
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
            var cd_pessoa, placa, modelo_veiculo, ps_min, ps_max, sulco, obs, analise;
            $('#modelo_veiculo').select2();
            $('#pessoa').select2({
                placeholder: "{{ isset($user_id->name) ? $user_id->name : 'Pessoa' }}",
                allowClear: true,
                ajax: {
                    url: '{{ route('admin.usuarios.search-pessoa') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.NM_PESSOA,
                                    id: item.ID
                                }
                            })

                        };
                    },
                    cache: true
                }
            });
            $('#ps_min').change(function() {
                ps_min = $('#ps_min').val();
                $('#ps_max').val(parseInt(ps_min) + 10);
            })
            $('#create-analysis').click(function() {

                pessoa = $('#pessoa').val();
                placa = $('#placa').val();
                sulco = $('#sulco').val();
                modelo_veiculo = $('#modelo_veiculo').val();
                ps_min = $('#ps_min').val();
                ps_max = $('#ps_max').val();
                nm_pessoa = $('#pessoa :selected').text();
                obs = $('#obs').val();

                $.ajax({
                    type: "GET",
                    url: "{{ route('analise-frota.create') }}",
                    data: {
                        pessoa: pessoa,
                        nm_pessoa: nm_pessoa,
                        sulco: sulco,
                        placa: placa,
                        modelo_veiculo: modelo_veiculo,
                        ps_min: ps_min,
                        ps_max: ps_max,
                        obs: obs,
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');
                        if (response.success) {
                            $('#modal-create').modal('hide');
                            msgToastr(response.success, 'success');
                            $('#pessoa').val(null).trigger('change');
                            $('#modelo_veiculo').val(0).trigger('change');
                            $('#form-analysis').each(function() {
                                this.reset();
                            });
                            $('#table-analise').DataTable().ajax.reload();
                        } else {
                            msgToastr(response.error, 'warning');
                        }
                    }
                });
            });
            $('#table-analise').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                responsive: true,
                pagingType: "simple",
                processing: false,
                ajax: {
                    type: 'GET',
                    url: "{{ route('list-analise-create') }}",
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    }, {
                        data: 'cliente',
                        name: 'cliente'
                    }, {
                        data: 'placa',
                        name: 'placa'
                    }, {
                        data: 'modelo',
                        name: 'modelo'
                    },
                    {
                        data: 'sulco',
                        name: 'sulco'
                    }, {
                        data: 'ps_min',
                        name: 'ps_min'
                    }, {
                        data: 'ps_max',
                        name: 'ps_max'
                    }, {
                        data: 'action',
                        name: 'action'
                    }
                ],
                order: [0, 'desc']
            });
            $('body').on('click', '#getDeleteId', function() {
                analise = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "{{ route('delete-analysis') }}",
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        id: analise,
                    },
                    success: function(response) {
                        if (response.success) {
                            msgToastr(response.success, 'success');
                            $('#table-analise').DataTable().ajax.reload();
                        } else {
                            msgToastr(response.error, 'error');
                        }
                    }
                });
            })
        });
    </script>
@endsection
