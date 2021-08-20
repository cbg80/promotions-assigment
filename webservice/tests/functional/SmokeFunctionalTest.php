<?php

declare(strict_types=1);

namespace App\Tests\functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\dataProviders\functional\SmokeFunctionalDataProvider;

class SmokeFunctionalTest extends WebTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        
        print PHP_EOL . 'Starting smoke functional tests ...' . PHP_EOL;
        
        $outputArray = [];
        
        system('php bin/console cache:clear --env=test --no-interaction --no-warmup', $outputArray);
        system('php bin/console doctrine:database:create --env=test --no-interaction --if-not-exists', $outputArray);
        system('php bin/console doctrine:schema:drop --env=test --no-interaction --force --full-database', $outputArray);
        system('php bin/console doctrine:schema:create --env=test --no-interaction', $outputArray);
        system('php bin/console doctrine:fixtures:load --env=test --no-interaction', $outputArray);
    }
    
    /**
     * 
     * @dataProvider App\Tests\dataProviders\functional\SmokeFunctionalDataProvider::urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
    
    /**
     * 
     * @dataProvider App\Tests\dataProviders\functional\SmokeFunctionalDataProvider::urlAPIProvider
     */
    public function testAPIWorks($url)
    {
        $client = self::createClient();
        $client->request('GET', $url, [], [], ['HTTP_ACCEPT' => 'application/json']);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
