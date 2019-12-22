<?php

namespace Belca\Support\Tests\Constants;

use Belca\Support\AbstractConstants;

class FirstConstants extends AbstractConstants
{
    const USER = 'user';
    const SUPERUSER = 'superuser';
    const CLIENT = 'client';
    const MODERATOR = 'moderator';
    const SUPERMODERATOR = 'super'.self::USER;
}
