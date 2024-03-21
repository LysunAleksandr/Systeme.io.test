<?php

namespace App\Controller;


use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api/v1/products')]
class ProductController extends AbstractController
{
    protected function getDefaultNormalizerContext(): array
    {
        return [
            AbstractNormalizer::GROUPS => ['View'],
        ];
    }

    #[Route('/', methods: ['GET'])]
    public function showAction(): Response
    {
        return $this->json(['total' => 0], Response::HTTP_OK);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function showAction1(Product $product): Response
    {
        return $this->json(
            $product,
            Response::HTTP_OK,
            [],
            $context ?? $this->getDefaultNormalizerContext()
        );
    }
}
