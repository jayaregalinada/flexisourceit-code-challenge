<?php

namespace Services\Customer\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static GenderEnum MALE()
 * @method static GenderEnum FEMALE()
 */
class GenderEnum extends Enum
{
    private const MALE = 0;

    private const FEMALE = 1;
}
