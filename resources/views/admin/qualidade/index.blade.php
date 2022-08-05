@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-5">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Incluir/Listar</h3>
                    </div>
                    <div class="box-body">
                        @error('file')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                        <form action="{{ route('procedimento.upload') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="arquivo">Setor designado</label>
                                    <select name="setor" id="setor" class="form-control select2">
                                        <option value="1">Faturamento</option>
                                        <option value="2">Cobranca</option>
                                        <option value="3">Financeiro</option>
                                        <option value="4">Tecnologia da Informação</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="users">Usuarios Responsaveis</label>
                                    <select class="form-control" id="users" name="users[]" multiple="multiple"
                                        data-placeholder="Selecione os usuarios" style="width: 100%;">
                                        @foreach ($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="arquivo">Titulo</label>
                                    <input type="text" class="form-control" name='title' placeholder="Titulo Procedimento" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="arquivo">Descrição</label>
                                    <textarea name="description" class="form-control" rows="4" cols="50" placeholder="Descreva um pequeno resumo do Procedimento"></textarea>
                                </div>
                            </div>                            
                            <div class="col-md-12">
                                <label for="arquivo">Buscar arquivos</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-file-pdf-o"></i></span>
                                    <input type="file" name="file" class="form-control" placeholder="Clique/Arraste e Solte aqui" accept="application/pdf" required>
                                    
                                </div>
                                <p class="help-block">Somente arquivos em PDF.</p>
                            </div>
                            <div class="col-md-12" align="center" style="padding-top: 24px">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fa fa-download"></i> Enviar Procedimento</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Procedimentos criados</h3>
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
            $("#users").select2();

        });
    </script>
@endsection
