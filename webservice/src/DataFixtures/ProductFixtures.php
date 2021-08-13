<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    private SerializerInterface $serializer;
    
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    
    public function load(ObjectManager $manager)
    {
        $finder = new Finder();
        $finder->in(__DIR__ . '/json');
        $finder->name('products.json');
        $finder->files();
        
        $files = $finder->getIterator();
        foreach ($files as $file) {
            try {
                print 'Importing ' . $file->getBasename() . PHP_EOL;
                
                $jsonProductPage = $file->getContents();
                $denormalizedProductArray = $this->serializer->deserialize($jsonProductPage, Product::class, 'json');
                var_dump($denormalizedProductArray);
            } catch (\Exception $e) {
                print 'Exception ' . $file->getBasename() . '-' . $e->getMessage() . PHP_EOL;
            }
        }
    }
    
    public function getDependencies()
    {
        return [
            PromotionFixtures::class
        ];
    }
}
