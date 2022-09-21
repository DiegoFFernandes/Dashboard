@component('mail::message')

<h3>Email Validado/Deletado</h3>

<p>Olá, o email abaixo foi validado/excluido da plataforma (Dashboard), 
    verique no iagente se ele está na lista de endereço inexistente, 
    e envie e-mail para atendimento@iagente.movidesk.com solicitando a liberação do mesmo!</p>

### Dados:
* Email: {{$request->email}}

@component('mail::button', ['url' => 'https://www.iagente.com.br/login-smtp', 'color' => 'success'])
Iagente
@endcomponent

@endcomponent