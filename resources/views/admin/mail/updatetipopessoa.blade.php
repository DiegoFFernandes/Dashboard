@component('mail::message')
# Alteração!
O tipo de pessoa dos clientes abaixo foram alterados para 10 - ZDD Perdidos, pois ultrapassaram 366 dias de vencimento!

@component('mail::table')
| Cód. Pessoa | Pessoa
| :---------  | :------------- |
@foreach ($pessoas as $p)
| {{ $p->CD_PESSOA }} | {{ $p->NM_PESSOA }}
@endforeach


@endcomponent

<br>
Disparo Automatico, não é necessario responder
@endcomponent
