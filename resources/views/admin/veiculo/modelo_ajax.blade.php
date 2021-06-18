<option selected="selected">Selecione</option>
@foreach($modelos as $modelo)
<option value="{{$modelo->id}}" selected="selected">{{$modelo->descricao}}</option>
@endforeach