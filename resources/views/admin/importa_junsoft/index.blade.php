@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">{{ $title_page }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
                            <label>Importar por Marca</label>
                            <select id="importa-produto" class="form-control select2" style="width: 100%;">
                                <option selected="selected">Selecione a marca</option>
                                @foreach ($marcas as $m)
                                    <option value="{{ $m->CD_MARCA }}">{{ $m->DS_MARCA }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button id="submit-importa" class="btn btn-primary">Importar</button>
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
    <script>
        //Initialize Select2 Elements
        $(function() {
            $('.select2').select2()
        });
    </script>
    <script type="text/javascript">
        let cd_marca = $("#_token").val();
        $(document).ready(function() {
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
                    success: function(result) {
                        $("#loading").addClass('hidden');
                        alert(result.msg);
                    }
                });
            });
        });
    </script>
@endsection
