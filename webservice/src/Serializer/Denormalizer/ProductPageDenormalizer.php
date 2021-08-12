<?php

declare(strict_types=1);

namespace App\Serializer\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use App\Entity\Product;

class ProductPageDenormalizer implements DenormalizerAwareInterface, DenormalizerInterface
{
    const ASSOC_KEY_FOR_PRODUCT_PAGE = 'products';
    
    use DenormalizerAwareTrait;

    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $decodedProductArray = $data[self::ASSOC_KEY_FOR_PRODUCT_PAGE];
        
        $denormalizedProductArray = [];
        
        if (count($decodedProductArray) > 0) {
            foreach ($decodedProductArray as $decodedProduct) {
                $denormalizedProductArray[] = $this->denormalizer->denormalize($decodedProduct, Product::class);
            }
        }
        
        return $denormalizedProductArray;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type == Product::class && array_key_exists(self::ASSOC_KEY_FOR_PRODUCT_PAGE, $data);
    }
}
