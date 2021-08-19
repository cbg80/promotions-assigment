<?php

declare(strict_types=1);

namespace App\Repository\ports;

interface ProductRepository
{
    public function findWithoutDiscountApplied(int $findOffset, ?string $categoryNameFilter = NULL, int $priceOriginalLessThanFilter = 0): ?array;
}

