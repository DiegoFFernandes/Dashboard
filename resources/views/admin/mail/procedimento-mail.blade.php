@component('mail::message')

<h3>Procedimento</h3>

<p>Olá {{ $user->name }}, 
@if ($request['status'] == 'R')
    o procedimento que você criou para o setor de <b>{{ $setor->nm_setor }}</b> foi <b>Recusado</b>,
    veja as informação abaixo:
@elseif ($request['status'] == 'L')
    o procedimento que você criou para o setor de <b>{{ $setor->nm_setor }}</b> foi <b>Liberado</b>,
    veja as informação abaixo:
@elseif ($request['op_table'] == 'table-procedimento-recusados')
    o procedimento com os dados abaixo sofreu uma reanalise e foi alterado, devida a uma reprovação! 
    As alterações estão em uma <b>cor</b> de destaque, Você deve liberar ou reprovar novamente para seguir processo!  
@elseif ($request['status'] == 'revision')
    o procedimento com os dados abaixo teve um pedido de revisão, de acordo com a descrição abaixo!
    @else
    foi criado um procedimento novo para o setor de <b>{{ $setor->nm_setor }}</b>,
    verifique os dados e clique no botão abaixo para visualizar e dar sequência!
@endif
</p>
@component('mail::table')

@if ($request['status'] == 'R')
### Dados:
* Descrição: {{$request['description_recusa']}}  
@elseif ($request['status'] == 'L')


@elseif ($request['op_table'] == 'table-procedimento-recusados')
### Dados:
* Titulo: {{$request['title']}}
* Descrição: {{$request['description']}} (EDITADO)
@elseif($request['status'] == 'revision')
### Dados:
* Titulo: {{$request['title']}}
* Descrição: {{$request['description_recusa']}} 
@else
### Dados:
* Titulo: {{$request['title']}}
* Descrição: {{$request['description']}} 
@endif

@endcomponent

@if ($request['status'] == 'R' || $request['status'] == 'L' || $request['status'] = 'revision')
@component('mail::button', ['url' => 'https://portal.ivorecap.com.br/procedimento/index', 'color' => 'success'])
Ver procedimento
@endcomponent
@else
@component('mail::button', ['url' => 'https://portal.ivorecap.com.br/procedimento-aprovador/autorizador', 'color' => 'primary'])
Ver procedimento
@endcomponent
@endif

<p>Caso não for liberado ou recusado em 10 dias, o procedimento 
    será considerado <b>liberado</b> automaticamente!</p>
@endcomponent