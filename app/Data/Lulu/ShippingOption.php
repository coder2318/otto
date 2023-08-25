<?php

namespace App\Data\Lulu;

enum ShippingOption: string
{
    case MAIL = 'MAIL';
    case PRIORITY_MAIL = 'PRIORITY_MAIL';
    case GROUND_HD = 'GROUND_HD';
    case GROUND_BUS = 'GROUND_BUS';
    case GROUND = 'GROUND';
    case EXPEDITED = 'EXPEDITED';
    case EXPRESS = 'EXPRESS';
}
