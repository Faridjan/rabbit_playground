<?php

declare(strict_types=1);

namespace App\Helper;

class FormatHelper
{
    public const FRONTEND_DATE_FORMAT = 'Y-m-d\TH:i:s';

    public const REGEX_HOSTNAME = '/(^\w+:|^)\/\/?(?:www\.)?/';
    public const REGEX_DOMAIN = '/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/';
}
