<option selected="selected" value="">Selecione</option>
@foreach($marcas as $marca)
<option value="{{$marca->cd_marca}}">{{$marca->descricao}}</option>
@endforeach