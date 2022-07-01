<div class="form-group">
    <label for="executor">Executor:</label>
    <select class="form-control select executor" name="" style="width: 100%;">
        <option value="0">Selecione um operador</option>
        @foreach ($executor as $e)
            <option value="{{ $e->id }}">{{ $e->nmexecutor }}</option>
        @endforeach
    </select>
</div>