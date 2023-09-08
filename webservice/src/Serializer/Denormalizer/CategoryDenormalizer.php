<?php

declare(strict_types=1);

namespace App\Serializer\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use App\Entity\Category;
use Ramsey\Uuid\Uuid;

class CategoryDenormalizer implements DenormalizerInterface
{

    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $category = NULL;
        
        if (isset($data)) {
            $category = new Category(Uuid::uuid4()->toString());
            
            $category->setName($data);
        }
        
        return $category;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type == Category::class;
    }
}
