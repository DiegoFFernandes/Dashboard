<div class="modal-body">
    <div class="alert alert-dismissible hidden" id="alert">
        <button type="button" class="close btn-cancel" data-dismiss="alert" aria-hidden="true">×</button>
    </div>
    <form id="formPessoa" method="POST" action="{{route('pessoa.store')}}">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Cd. Pessoa</label>
                <input type="text" class="form-control" name="id" id="id" disabled>
            </div>
        </div>
        <div class="col-md-12">
            <input id="token" name="_token" type="hidden" value="{{ csrf_token() }}">
            <div class="form-group">
                <label>Empresa</label>
                <select class="form-control" name="cd_empresa" id="cd_empresa">
                    <option selected>Selecione</option>
                    <option value="1">AM MORENO</option>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Pessoa</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Nome" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" class="form-control" name="cpf" id="cpf" placeholder="000.000.000-00">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="phone">Telefone:</label>
                <input type="text" class="form-control" name="phone" id="phone" placeholder="41 9.99999-9999">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="email">Email</label>
                <select class="form-control select2" name="cd_email" id="cd_email" style="width: 100%" required>
                    <option value="0" selected>Selecione</option>
                    <option value="1">ti.campina@ivorecap.com.br</option>
                    
                </select>
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group">
                <label for="endereco">Endereço</label>
                <input type="text" class="form-control" name="endereco" id="endereco" placeholder="Rua / Avenida">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="numero">Nº</label>
                <input type="text" class="form-control" name="numero" id="numero" placeholder="Número">
            </div>
        </div>
        <button type="submit">Enviar</button>
    </form>
</div>
