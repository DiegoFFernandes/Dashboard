<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Buscar:</h3>
    </div>
    <div class="box-body">
        <div class="col-md-5">
            <div class="form-group">
                <select class="form-control empresas" id="empresas" style="width: 100%;">

                </select>
            </div>
        </div>
        <div class="col-md-5">
            @includeIf('admin.master.filtro-data')
        </div>
        <div class="col-md-2" align="center">
            <div class="form-group" id="btn-action">
                <button type="button" class="btn btn-success btn-block pull-right btn-search-bol" id="btn-search-bol">Buscar
                    Boleto</button>                
            </div>
        </div>
    </div>
</div>
