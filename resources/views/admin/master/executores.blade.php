<div class="form-group">
    <label for="executor">Executor:</label>
    <select class=" form-control select" name="" id="executor">
        <option value="0">Selecione um operador</option>
        @foreach ($executor as $e)
            <option value="{{ $e->ID }}">{{ $e->NMEXECUTOR }}</option>
        @endforeach
    </select>
</div>