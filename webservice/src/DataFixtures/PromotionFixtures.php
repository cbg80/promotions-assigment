<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;

class PromotionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $finder = new Finder();
        $finder->in(__DIR__ . '/sql');
        $finder->name('promotions.sql');
        $finder->files();
        
        $files = $finder->getIterator();
        foreach ($files as $file) {
            try {
                print 'Importing ' . $file->getBasename() . PHP_EOL;
                
                $sql = $file->getContents();
                $result = $manager->getConnection()->exec($sql);
                
                if ($result == 1) {
                    throw new \Exception('Error al ejecutar los inserts. Compruebe la consulta');
                }
                
            } catch (\Exception $e) {
                print 'Exception ' . $file->getBasename() . ' - ' . $e->getMessage() . PHP_EOL;
            }
        }
    }
}
