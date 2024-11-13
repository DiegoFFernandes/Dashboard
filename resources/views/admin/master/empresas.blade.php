<div class="form-group">
    <div class="input-group">
        <div class="input-group-addon"><i class="fa fa-building"></i></div>
        <select class="form-control empresas" style="width: 100%;" id="cd_empresa" name="cd_empresa">
            @if (isset($allEmpresas))
                <option selected="selected" value="0">Todas</option>
            @else
                <option selected="selected" value="0">Selecione uma empresa</option>
            @endif
            @foreach ($empresas as $e)
                <option value="{{ $e->cd_empresa_new }}">{{ $e->ds_local }}</option>
            @endforeach
        </select>
    </div>
</div>
