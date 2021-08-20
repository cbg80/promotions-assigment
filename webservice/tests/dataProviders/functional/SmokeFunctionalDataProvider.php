<?php

declare(strict_types=1);

namespace App\Tests\dataProviders\functional;

class SmokeFunctionalDataProvider
{
    public function urlProvider()
    {
        yield ['/api'];
    }
    
    public function urlAPIProvider()
    {
        yield ['/api/products/1'];
        yield ['/api/products/1/70000'];
        yield ['/api/products/1/boots'];
        yield ['/api/products/1/boots/90000'];
    }
}
