@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Maquinas Manutenção</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-success btn-sm" id='create'>Adicionar</button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12">
                            <table class="table table-sm" id="table-machines" style="width: 100%">
                                <thead>
                                    <th>Cód.</th>                                    
                                    <th>Descrição</th>                                    
                                    <th>Nr. Cód. QR</th>
                                    <th>QR Code</th>
                                    <th>Acões</th>
                                </thead>
                                <tbody>
                                    @foreach ($etapa_maquina as $m)
                                        <tr>
                                            <td>{{ $m->id }}</td>
                                            <td>{{ $m->ds_maquina }}</td>                                                                                       
                                            <td>{{ $m->cd_barras }}</td>
                                            <td>{!! QrCode::generate($m->cd_barras) !!}</td>
                                            <td><button class="btn btn-primary btn-edit btn-sm"
                                                    data-id="{{ $m->id }}">Editar</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Associar Maquina / Setor --}}
        <div class="modal" id="CreateEditMachineSetor">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Associar Maquina / Setor</h4>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="alert alert-dismissible hidden" id="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        </div>
                        <div class="col-md-12">
                            <input id="token" name="_token" type="hidden" value="{{ csrf_token() }}">
                            <input id="id_machine" type="hidden" type="number">                            
                        </div>
                        <div class="col-md-12">
                            @includeIf('admin.master.etapas-produtivas')
                        </div>
                        <div class="col-md-8">
                            <label>Maquinas:</label>
                            <select class="form-control" name="cd_maquina" id="cd_maquina" style="width: 100%;">
                                <option value="">Selecione a Maquina</option>
                                @foreach ($maquinas as $m)
                                    <option value="{{ $m->id }}">{{ $m->ds_maquina }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Seq.:</label>
                                <select class="form-control" name="seq_maquina" id="seq_maquina" style="width: 100%;">
                                    @for ($i = 1; $i < 10; $i++)
                                        0 <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                    <select>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="SubmitAssociarMachine">Associar</button>
                        <button type="button" class="btn btn-warning" id="SubmitEditMachine">Editar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
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

            function InitTable() {
                $('#table-machines').DataTable({
                    dom: 'Blfrtip',
                    responsive: true,
                    pagingType: "simple",
                    buttons: [{
                            extend: 'pdfHtml5',
                            download: 'open',
                        },
                        'print'
                    ],
                });
            }
            InitTable();
            $("#create").click(function() {
                $('#SubmitAssociarMachine').show();
                $('#SubmitEditMachine').hide();

                $('#CreateEditMachineSetor').modal('show');
            });
            $('#cd_maquina').select2();
            $('#SubmitAssociarMachine').click(function() {
                let empresa = $('#cd_empresa').val();
                let maquina = $('#cd_maquina').val();
                let seq_maquina = $('#seq_maquina').val();
                let etapa = $('.etapas').val();

                if (etapa == 0) {
                    msgToastr('Selecione uma etapa', "warning");
                    return false;
                }
                $.ajax({
                    url: '{{ route('manutencao.associate-phases') }}',
                    type: 'get',
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),                        
                        maquina: maquina,
                        seq_maquina: seq_maquina,
                        etapa: etapa
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');
                        if (response.error) {
                            // toastr["warning"](response.error);
                            msgToastr(response.error, "warning");
                        } else {
                            msgToastr(response.success, "success");
                            location.reload();
                        };

                    }
                });

            });

            $('body').on('click', '.btn-edit', function(e) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('manutencao.edit-phases') }}",
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        id: $(this).data('id'),
                    },
                    success: function(response) {
                        $('#SubmitEditMachine').show();
                        $('#SubmitAssociarMachine').hide();
                        $('#id_machine').val(response['id']);
                        $('#cd_empresa').val(response['cd_empresa']);
                        $('.etapas').val(response['cd_etapa_producao']).trigger('change');
                        $('#cd_maquina').val(response['cd_maquina']).trigger('change');
                        $('#seq_maquina').val(response['cd_seq_maq']);
                        $('#CreateEditMachineSetor').modal('show');                        
                    }
                });
            });
            $('#SubmitEditMachine').click(function() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('manutencao.update-phases') }}",
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        id: $('#id_machine').val(),                        
                        etapa: $('.etapas').val(),
                        maquina: $('#cd_maquina').val(),
                        seq_maquina: $('#seq_maquina').val(),
                    },
                    success: function(response) {
                        $('#CreateEditMachineSetor').modal('hide');
                        if (response.error) {
                            msgToastr(response.error, 'warning')
                        } else {
                            msgToastr(response.success, 'success');
                            location.reload();
                        }
                    }
                });
            });


        });
    </script>
@endsection
