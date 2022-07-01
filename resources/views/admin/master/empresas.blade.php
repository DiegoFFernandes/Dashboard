<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-building"></i></span>
        <select class="form-control select2" id="empresas" style="width: 100%;">
            <option selected="selected" value="0">Selecione uma empresa</option>
            @foreach ($empresas as $e)
                <option value="{{ $e->cd_empresa }}">{{ $e->ds_local }}</option>
            @endforeach
        </select>
    </div>
</div>