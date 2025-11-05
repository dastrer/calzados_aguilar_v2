<?php

namespace App\Enums;

enum MetodoPagoEnum: string
{
    case Efectivo = 'EFECTIVO';
    case QR = 'QR';

    public function name(): string
    {
        return match($this) {
            self::Efectivo => 'Efectivo',
            self::QR => 'QR',
        };
    }
}
