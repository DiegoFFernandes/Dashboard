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
                                    <th>Empresa</th>
                                    <th>Descrição</th>
                                    <th>Nr. Cód. QR</th>
                                    <th>QR Code</th>
                                    <th>Acões</th>
                                </thead>
                                <tbody>
                                    @foreach ($etapa_maquina as $m)
                                        <tr>
                                            <td>{{ $m->id }}</td>
                                            <td>{{ $m->cd_empresa }}</td>
                                            <td>{{ $m->ds_maquina }}</td>
                                            <td>{{ $m->cd_barras }}</td>
                                            <td>{!! QrCode::generate($m->cd_barras) !!}</td>
                                            <td><button class="btn btn-primary">Editar</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Create Motorista Veiculo --}}
        <div class="modal" id="CreateMachineSetor">
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
                            <div class="form-group">
                                <label>Empresa:</label>
                                <select class="form-control" name="cd_empresa" id="cd_empresa">
                                    <option selected>Selecione</option>
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->cd_empresa }}">{{ $empresa->ds_local }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
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
                        <button type="button" class="btn btn-success" id="SubmitCreateMarcaModeloForm">Criar</button>
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
            $('#table-machines').DataTable({
                dom: 'Blfrtip',
                buttons: [{
                    extend: 'pdfHtml5',
                    download: 'open'
                }],
            });
            $("#create").click(function() {
                $('#CreateMachineSetor').modal('show');
            });
            $('#cd_maquina').select2();
            // $('#cd_empresa').on('change', function(e) {
            //     var empresa = ($(this).val());
            //     $.ajax({
            //         url: '{{ route('manutencao.seach-maquinas') }}?empresa='+empresa,
            //         type: 'get',
            //         success: function (res) {
            //             $('#cd_maquina').html('<option value="">Selectione a maquina</option>');
            //             // $.each(res, function (key, value) {
            //             //     $('#state').append('<option value="' + value
            //             //         .id + '">' + value.name + '</option>');
            //             // });

            //         }
            //     });


            // });

            // $("#cd_maq").on("keypress focusout", function(event) {
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     var cd_barras = $("#cd_maq").val();                
            //     if (keycode == '9' || keycode == '13' || event.type == "focusout") {
            //         $('.maquina').val(cd_barras).trigger('change');
            //         return false;

            //     }
            // });
        });
    </script>
@endsection
