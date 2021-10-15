<div>
    Olá {{ $username }}!

    <br><br>

    Segue abaixo as informações de sua última consulta de conversão de valores:
    <br>

    <b>Moeda de origem:</b> {{ $user_historic->source_currency_name }} ({{ $user_historic->source_currency_code }}) <br>
    <b>Moeda de destino:</b> {{ $user_historic->destination_currency_name }} ({{ $user_historic->destination_currency_code }}) <br>
    <b>Valor para conversão:</b> $ {{ $user_historic->source_currency_value }} <br>
    <b>Forma de pagamento:</b> {{ $payment_method_name }} <br>
    <b>Valor da "Moeda de destino" usado para conversão:</b> $ {{ $user_historic->destination_currency_bid_value }} <br>
    <b>Valor comprado em "Moeda de destino":</b> $ {{ $user_historic->destination_currency_total_bough_value }} <br>
    <b>Taxa de pagamento:</b> $ {{ $user_historic->payment_method_tax_value }} <br>
    <b>Taxa de conversão:</b> $ {{ $user_historic->exchange_tax_value }} <br>
    <b>Valor utilizado para conversão descontando as taxas:</b> $ {{ $user_historic->exchange_used_value }} <br>
</div>