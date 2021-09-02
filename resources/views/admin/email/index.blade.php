@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            {{-- index --}}
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- box-header -->
                    <div class="box-header with-border">
                        <h3 class="box-title">Emails Cadastrados</h3>
                        <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm add" type="button"
                            data-toggle="modal" data-target="#CreateEmailModal">
                            Adicionar Email
                        </button>
                    </div>
                    <!-- /.box-header -->
                    <!-- .box-body -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table display" style="width:100%">
                                <thead>
                                    <tr>                                        
                                        <th style="width: 10px">Cod.</th>
                                        <th>Email</th> 
                                        <th>Ações</th>                                        
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-header -->
                </div>
                <!-- /.box -->
            </div>

            {{-- Create Email --}}
            <div class="modal" id="CreateEmailModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Adicionar Email</h4>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="alert alert-dismissible hidden" id="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            </div>
                            <form id="formEmail">
                                @csrf
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Cd. Email</label>
                                        <input type="text" class="form-control" name="id" id="id" disabled>
                                    </div>
                                </div>                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" name="email" id="email"
                                            placeholder="email@ivorecap.com.br" required>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success btn-save">Criar</button>
                            <button type="button" class="btn btn-warning btn-update">Editar</button>

                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- /.row -->

        {{-- Icon loading --}}
        <div class="hidden" id="loading">
            <img id="loading-image" class="mb-4" src="{{ Asset('img/loader.gif') }}" alt="Loading...">
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    @includeIf('admin.master.datatables')
    <!-- My Scripts -->
    <script src="{{ asset('js/email/scripts.js') }}"></script>

@endsection
