<?php

declare(strict_types=1);

namespace App\Service\adapters;

use App\Service\ports\ApplicationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Repository\ports\ProductRepository;
use App\Entity\Product;
use App\VO\Price;
use App\Repository\adapters\DoctrineProductRepository;

class ProductsApplicationService implements ApplicationService
{
    private NormalizerInterface $normalizer;
    private ProductRepository $productRepository;
    
    public function __construct(NormalizerInterface $normalizer, ProductRepository $productRepository)
    {
        $this->normalizer = $normalizer;
        $this->productRepository = $productRepository;
    }

    public function execute(Request $request): ?array
    {
        $normalizedProductsWithDiscountAppliedArray = [];
        
        $page = $request->attributes->getInt('page', 1);
        $category = $request->attributes->get('category');
        $priceLessThan = $request->attributes->getInt('priceLessThan');
        
        $findOffset = ($page - 1) * DoctrineProductRepository::FIND_LIMIT;
        
        $productsWithDiscountPercentagesArray = $this->productRepository->findWithoutDiscountApplied($findOffset, $category, $priceLessThan);
        
        if (count($productsWithDiscountPercentagesArray) > 0) {
            /** @var $productObject Product */
            /** @var $priceOfProductObject Price */
            foreach ($productsWithDiscountPercentagesArray as $productWithDiscountPercentagesArray) {
                $productObject = $productWithDiscountPercentagesArray[0];
                $discountPercentageArray = array_filter([$productWithDiscountPercentagesArray[1], $productWithDiscountPercentagesArray[2]]);
                $priceOfProductObject = $productObject->getPrice();
                if (count($discountPercentageArray) > 0) {
                    $discountPercentage = max($discountPercentageArray);
                    $priceOfProductObject->setDiscountPercentage($discountPercentage . '%');
                } else {
                    $discountPercentage = NULL;
                }
                $finalPriceOfProduct = $priceOfProductObject->evalFinal($discountPercentage);
                $priceOfProductObject->setFinal($finalPriceOfProduct);
                $productObject->setPrice($priceOfProductObject);
                $productsWithDiscountAppliedArray[] = $productObject;
            }
            $normalizedProductsWithDiscountAppliedArray = $this->normalizer->normalize($productsWithDiscountAppliedArray);
        }
        
        return $normalizedProductsWithDiscountAppliedArray;
    }
}
