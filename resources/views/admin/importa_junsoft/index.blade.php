@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h4 class="box-title">Atualizar Executores</h4>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <select class="form-control" name="local" id="local">
                                <option value="NORTE">Funcionarios Norte</option>
                                <option value="SUL">Funcionarios Sul</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-success mb-2" id="btn-search-executores">Atualizar</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">{{ $title_page }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
                            <label>Importar por Marca</label>
                            <select id="importa-produto" class="form-control" style="width: 100%;">
                                <option selected="selected">Selecione a marca</option>
                                @foreach ($marcas as $m)
                                    <option value="{{ $m->CD_MARCA }}">{{ $m->DS_MARCA }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- <div class="box-body">
                        <div class="form-group">
                            <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
                            <label>Importar Motivo Pneu</label>
                        </div>
                        <button id="submit-importa" class="btn btn-primary">Importar Item</button>
                    </div> --}}
                    <div class="box-footer">
                        <button id="submit-importa" class="btn btn-primary">Importar Item</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Importa/Atualiza Cadastro de Motivo da Junsoft</h3>
                    </div>
                    <div class="box-body">
                        <div class="box-body">
                            <div class="form-group">
                                <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
                                <label>Importar Motivo Pneu</label>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button id="submit-importa-motivo" class="btn btn-primary">Importar Motivo</button>
                    </div>
                </div>
            </div>

            {{-- Icon loading --}}
            <div class="hidden" id="loading">
                <img id="loading-image" class="mb-4" src="{{ Asset('img/loader.gif') }}" alt="Loading...">
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#importa-produto').select2();
            $('#submit-importa').click(function() {
                $.ajax({
                    url: "{{ route('importa-item.index') }}",
                    method: 'POST',
                    data: {
                        cd_marca: $("#importa-produto option:selected").val(),
                        _token: $('#_token').val(),
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');

                        if (response.success) {
                            msgToastr(response.success, 'success');
                        } else {
                            msgToastr(response.error, 'warning');
                        }

                    }
                });
            });
            $('#submit-importa-motivo').click(function() {
                $.ajax({
                    url: "{{ route('importa-motivo-pneu') }}",
                    method: 'POST',
                    data: {
                        _token: $('#_token').val(),
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');
                        if (response.success) {
                            msgToastr(response.success, 'success');
                        } else {
                            msgToastr(response.error, 'danger');
                        }
                    }
                });
            });
            $('#btn-search-executores').click(function() {
                let local = $('#local').val();
                var url = "{{ route('get-buscar-executor', ':local') }}";
                url = url.replace(':local', local);
                $.ajax({
                    method: "GET",
                    url: url,
                    beforeSend: function() {
                        $('#loading').removeClass('hidden');
                    },
                    success: function(result) {
                        $('#loading').addClass('hidden');
                        msgToastr(result.success, 'success');
                    }
                });
            });
        });
    </script>
@endsection
