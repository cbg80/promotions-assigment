<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use App\Entity\Product;

class ProductNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;
    
    public function normalize($object, $format = null, array $context = []): array
    {
        /** @var $object Product */
        $data = array_merge([
            'sku' => $object->getSku(),
            'name' => $object->getName(),
            'price' => $this->normalizer->normalize($object->getPrice(), $format, $context)
        ],
            current($this->normalizer->normalize($object->getCategories()))
        );
        
        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Product;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
