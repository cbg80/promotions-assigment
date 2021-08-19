<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\adapters\ProductsApplicationService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductController extends AbstractController
{
    /**
     * @Route(
     *     name="products_by_category",
     *     path="api/products/{!page<\d+>?1}/{category<[A-Za-z ]+>?}/{priceLessThan<\d+>?0}",
     *     methods={"GET"},
     *     defaults={
     *       "_controller"="\App\Controller\ProductController::getProductsByCategory"
     *     }
     *   )
     */
    public function getProductsByCategory(Request $request, ProductsApplicationService $applicationService): JsonResponse
    {
        $data = $applicationService->execute($request);
        
        return JsonResponse::create($data);
    }

    /**
     * @Route(
     *     name="products_by_price",
     *     path="api/products/{!page}/{priceLessThan}",
     *     requirements={
     *       "priceLessThan"="\d+",
     *       "page"="\d+"
     *     },
     *     methods={"GET"},
     *     defaults={
     *       "page": 1,
     *       "_controller"="\App\Controller\ProductController::getProductsByPrice"
     *     }
     *   )
     */
    public function getProductsByPrice(Request $request, ProductsApplicationService $applicationService): JsonResponse
    {
        $data = $applicationService->execute($request);
        
        return JsonResponse::create($data);
    }
}
