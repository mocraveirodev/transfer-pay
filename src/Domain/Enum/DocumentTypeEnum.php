<?php

namespace Src\Domain\Enum;

enum DocumentTypeEnum: string
{
    case CPF = 'cpf';
    case CNPJ = 'cnpj';
}
