@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header withborder">
                        <h3 class="box-title">Deseja cancelar uma nota?</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="id>Numero Nota</label>
                                    <input type="email" class="form-control" id="id_nota" placeholder="Número Nota">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Cliente</label>
                                <select class="form-control" style="width: 100%;" id="search-pessoa" name="nm_pessoa"></select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Motivo</label>
                                <select class="form-control select2" style="width: 100%;" id="id_motivo">
                                    <option selected="selected">Selecione o motivo</option>
                                    @foreach ($motivo as $m)
                                        <option value="{{ $m->DS_MOTIVO }}">{{ $m->DS_MOTIVO }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observação</label>
                                <textarea class="form-control" rows="3" placeholder="Deseja comentar mais algo?"
                                    id="ds_obs_motivo"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success pull-right" id="submit-cancela">Enviar Pedido</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.row -->
    <!-- /.content -->
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            let token = $("meta[name='csrf-token']").attr("content");
            $('.select2').select2();

            $('#search-pessoa').select2({
                placeholder: "Selecione um cliente",
                minimumInputLength: 1,
                ajax: {
                    url: "{{route('comercial.search-cliente')}}",
                    dataType: 'json',
                    results: function(data) {
                        console.log(data);
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(result) {
                                //console.log(item)
                                return {
                                    text: result.NM_PESSOA,
                                    id: result.NM_PESSOA,
                                }
                            }),

                        };
                    },
                    cache: true
                }
            });

            $('#submit-cancela').click(function() {
                $.ajax({
                    url: "{{ route('comercial.cancela-nota-do') }}",
                    method: "POST",
                    data: {
                        nm_pessoa: $('#nm_pessoa').val(),
                        id_nota: $('#id_nota').val(),
                        id_motivo: $('#id_motivo').val(),
                        ds_obs: $('#ds_obs_motivo').val(),
                        _token: token,
                    },
                    beforeSend: function(result) {

                    },
                    success: function(result) {

                    }
                });
            });

        });
    </script>
@endsection
