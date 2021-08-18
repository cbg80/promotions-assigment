<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\VO\Price;

class PriceNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function normalize($object, $format = null, array $context = []): array
    {
        /** @var $object Price */
        $data = [
            'original' => $object->getOriginal(),
            'final' => $object->getFinal(),
            'discount_percentage' => $object->getDiscountPercentage(),
            'currency' => $object->getCurrency()
        ];

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Price;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
