<?php

namespace App\Helpers;

class MessageHelper
{
    public const ORDER_THROWABLE = 'Erro ao realizar transferência.';

    public const ORDER_UNAUTHORIZED = 'Transferência não autorizada.';

    public const ORDER_STORE_SUCCESS = 'Transferência realizada com sucesso.';

    public const TRANSFER_RECEIVED = 'Transferência recebida.';

    public const PAYER_NOT_FOUND = 'Não foi possível encontrar o Payer.';

    public const PAYEE_NOT_FOUND = 'Não foi possível encontrar o Payee.';

    public const USER_TYPE_NOT_FOUND = 'Tipo de usuário não encontrado.';

    public const STATUS_NOT_FOUND = 'Status não encontrado.';

    public const UNAVAILABLE_BALANCE = 'Saldo indisponível.';

    public const NOT_PAYER_TYPE = 'Este tipo de cliente não pode realizar transferências.';

    public const PAYER_EQUALS_PAYEE = 'O Payer não pode ser igual ao Payee.';
}
