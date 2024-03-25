<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Purchase;
use App\Service\PurchaseManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1/products')]
class ProductController extends AbstractController
{
    protected function getDefaultNormalizerContext(): array
    {
        return [
            AbstractNormalizer::GROUPS => ['View'],
        ];
    }

    protected function getDefaultCreateContext(): array
    {
        return [
            AbstractNormalizer::GROUPS => ['Create'],
        ];
    }

    #[Route('/', methods: ['GET'])]
    public function showAction(EntityManagerInterface $em): Response
    {
        $entity = $em->getRepository(Product::class)->findOneBy(['id' => 1]);

        return $this->json([$entity], Response::HTTP_OK);
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

    #[Route('/calculate-price', methods: 'POST')]
    public function calculatePriceAction(Request $request, PurchaseManager $purchaseManager, SerializerInterface $serializer): Response
    {
        try {
            $entity = $serializer->deserialize(
                $request->getContent(),
                Purchase::class,
                'json',
                $context ?? $this->getDefaultCreateContext(),
            );

            $purchase = $purchaseManager->setPurchasePrice($entity);
            return $this->json($purchase, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/purchase', methods: 'POST')]
    public function purchaseAction(Request $request, PurchaseManager $purchaseManager, SerializerInterface $serializer): Response
    {
        try {
            $entity = $serializer->deserialize(
                $request->getContent(),
                Purchase::class,
                'json',
                $context ?? $this->getDefaultCreateContext(),
            );

            $purchase = $purchaseManager->buy($entity);
            return $this->json($purchase, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
