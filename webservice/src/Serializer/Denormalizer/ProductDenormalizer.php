<?php

declare(strict_types=1);

namespace App\Serializer\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use App\Entity\Product;
use App\Entity\Category;
use App\VO\Price;
use Ramsey\Uuid\Uuid;

class ProductDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{

    use DenormalizerAwareTrait;

    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $product = NULL;
        
        if (isset($data['sku'], $data['name'], $data['category'], $data['price'])) {
            $product = new Product(Uuid::uuid4()->toString());
            
            $product->setSku($data['sku']);
            $product->setName($data['name']);
            
            $category = $this->denormalizer->denormalize($data['category'], Category::class);
            $product->addCategory($category);
            
            $price = new Price();
            
            $price->setOriginal($data['price']);
            
            $product->setPrice($price);
        }
        
        return $product;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type == Product::class && !array_key_exists(ProductPageDenormalizer::ASSOC_KEY_FOR_PRODUCT_PAGE, $data);
    }
}
