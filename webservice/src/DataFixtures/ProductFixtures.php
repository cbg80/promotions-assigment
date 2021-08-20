<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Category;
use App\Repository\adapters\DoctrinePromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;

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
                if (count($denormalizedProductArray) > 0) {
                    $uniqueDenormalizedCategoryCollection = new ArrayCollection();
                    /** @var $denormalizedProduct Product **/
                    /** @var $denormalizedCategory Category **/
                    foreach ($denormalizedProductArray as $denormalizedProduct) {
                        $denormalizedCategoryCollection = $denormalizedProduct->getCategories();
                        $denormalizedCategoryCollection->map(function($denormalizedCategory) use ($manager, $uniqueDenormalizedCategoryCollection, $denormalizedProduct) {
                            if ($uniqueDenormalizedCategoryCollection->containsKey($denormalizedCategory->getName())) {
                                $denormalizedProduct->removeCategory($denormalizedCategory);
                                $denormalizedCategory = $uniqueDenormalizedCategoryCollection->get($denormalizedCategory->getName());
                                $denormalizedProduct->addCategory($denormalizedCategory);
                            } else {
                                $promotionsToCategoryArray = $manager->getRepository('App:Promotion')->findBy(['categoryName' => $denormalizedCategory->getName()]);
                                if (count($promotionsToCategoryArray) > 0) {
                                    foreach ($promotionsToCategoryArray as $promotionToCategory) {
                                        $denormalizedCategory->addPromotion($promotionToCategory);
                                    }
                                }
                                $uniqueDenormalizedCategoryCollection->set($denormalizedCategory->getName(), $denormalizedCategory);
                            }
                            return $denormalizedCategory;
                        });
                        $promotionsToProductArray = $manager->getRepository('App:Promotion')->findBy(['productSku' => $denormalizedProduct->getSku()]);
                        if (count($promotionsToProductArray) > 0) {
                            foreach ($promotionsToProductArray as $promotionToProduct) {
                                $denormalizedProduct->addPromotion($promotionToProduct);
                            }
                        }
                        $manager->persist($denormalizedProduct);
                    }
                    $manager->flush();
                }
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
