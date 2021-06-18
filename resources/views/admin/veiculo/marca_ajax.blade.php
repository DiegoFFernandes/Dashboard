<option selected="selected" value="">Selecione</option>
@foreach($marcas as $marca)
<option value="{{$marca->cd_marca}}" selected="selected">{{$marca->descricao}}</option>
@endforeach