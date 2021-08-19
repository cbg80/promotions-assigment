<?php

declare(strict_types=1);

namespace App\Service\ports;

use Symfony\Component\HttpFoundation\Request;

interface ApplicationService
{
    public function execute(Request $request): ?array;
}
