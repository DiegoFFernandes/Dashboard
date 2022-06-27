<div class="form-group">
    <label for="etapas">Etapa Produtiva:</label>
    <select class="form-control select etapas" style="width: 100%;">
        <option value="0">Selecione uma etapa</option>
        @foreach ($etapas as $e)
            <option value="{{ $e->id }}">{{ $e->dsetapaempresa }}</option>
        @endforeach
    </select>
</div>