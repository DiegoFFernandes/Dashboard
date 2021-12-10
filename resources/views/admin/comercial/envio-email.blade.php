@component('mail::message')

<h3>Pedido de nota á cancelar</h3>

<p>Fala Coutinho, o requerente {{$user->name}}, acabou de fazer um pedido de cancelamento de nota.</p>

@component('mail::table')

### Dados para cancelamento:

* Empresa: {{$request->cd_empresa}}
    * Lançamento: {{$request->nr_lancamento }} 
    * Nota: {{$request->nr_nota }}  
    * Cliente: {{$request->nm_pessoa }}
    * Cnpj/Cpf: {{$request->nr_cnpjcpf }}
    * Motivo: {{$request->motivo }}
    * Obervação: {{$request->observacao }}

@endcomponent

@component('mail::button', ['url' => 'http://producao.ivorecap.com.br', 'color' => 'success'])
Ver Pedido Cancelamento
@endcomponent

@endcomponent