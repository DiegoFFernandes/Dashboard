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
                            <button type="button" id="btn-create" class="btn btn-sm badge bg-green" data-toggle="modal"
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
                            <input type="number" class="hidden" id="id_analysis">
                            <div class="col-md-8">
                                @includeIf('admin.master.pessoa')
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">Placa:</label>
                                    <input type="text" name='placa' class="form-control" id="placa"
                                        placeholder="AA1A-0000" value="" required>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                                        <option value="0" selected>Selecione um Modelo</option>
                                        @foreach ($modelo as $m)
                                            <option value="{{ $m->id }}">
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
                        <button type="button" class="btn btn-primary" id="create-analysis">Criar</button>
                        <button type="button" class="btn btn-warning" id="update_analysis">Atualizar</button>
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
            $('#modelo_veiculo').select2({
                placeholder: 'Selecione um modelo',
            });
            $('#pessoa').select2({
                placeholder: "Pessoa",
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
            $('#btn-create').click(function(){
                $('#update_analysis').hide();
                $('#create-analysis').show();
            });
            $('#create-analysis').click(function() {              
               CreateEdit("{{ route('analise-frota.create') }}");
            });
            $('#table-analise').on('click', '#edit-analysis', function() {
                var rowData = $('#table-analise').DataTable().row($(this).parents('tr')).data();
                if (rowData == undefined) {
                    var selected_row = $(this).parents('tr');
                    if (selected_row.hasClass('child')) {
                        selected_row = selected_row.prev();
                    }
                    rowData = $('#table-analise').DataTable().row(selected_row).data();
                }    
                console.log(rowData);  
                $('#id_analysis').val(rowData.id);      
                $('#pessoa').append('<option value="'+rowData.cd_pessoa+'" selected>'+rowData.nm_pessoa+'</option>');
                $('#placa').val(rowData.placa);
                $('#sulco').val(rowData.sulco);
                $('#modelo_veiculo').val(rowData.id_modelo).trigger('change');                
                $('#ps_min').val(rowData.ps_min);
                $('#ps_max').val(rowData.ps_max);                
                $('#obs').val(rowData.observacao);
                $('#create-analysis').hide();
                $('#update_analysis').show();
                $('#modal-create').modal('show');

            });
            $('#update_analysis').click(function(){
                CreateEdit("{{ route('analise-frota.update') }}");
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
            });
            function CreateEdit(url){
                analise = $('#id_analysis').val();
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
                    url: url,
                    data: {
                        id: analise,
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
            }
        });
    </script>
@endsection
