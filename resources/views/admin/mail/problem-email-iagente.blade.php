@component('mail::message')

<h3>Email com problema</h3>

<p>Olá, o email abaixo esta com algum tipo de problema e não chegou no seu destinatario, 
    favor verificar se está digitado corretamente ou se ele ainda e valido, 
    estando tudo certo valide ele no Dashboard!</p>

### Dados:
* Email: {{$request->Email}}
* Assunto: {{$request->Assunto}}
* Motivo: {{$request->Descricao}}

@component('mail::button', ['url' => 'https://producao.ivorecap.com.br', 'color' => 'success'])
Dasboard - Ivorecap
@endcomponent

@endcomponent