@component('mail::message')

<h3>Chat - Procedimento</h3>

<p>Olá {{$user_approver->name}}, houve uma resposta para sua mensagem de reprovação.</p>
@component('mail::table')

### Dados da mensagem:
* Criador: {{$user_create->name}} 
* Titulo: {{$procedimento->title}}
* Resposta: {{$request['description']}}


@endcomponent

@component('mail::button', ['url' => 'https://producao.ivorecap.com.br/procedimento/index', 'color' => 'success'])
Ver procedimento
@endcomponent

@endcomponent