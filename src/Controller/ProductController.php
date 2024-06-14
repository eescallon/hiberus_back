<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Helper\SerializerEntities;
use App\Controller\BaseController;

use App\Entity\Product;
use App\Entity\CountableProduct;
use App\Entity\WeightedProduct;

class ProductController extends BaseController
{
    public function __construct(
        EntityManagerInterface $em, 
        RequestStack $request, 
        )
    {
        parent::__construct($em, $request);
    }

    #[Route('/product', name: 'get_all_products', methods: ['GET', 'HEAD', 'OPTIONS'])]
    public function getAllProducts(): JsonResponse
    {
        $serializerHelper = new SerializerEntities();
        $productsRepository = $this->em->getRepository(Product::class);
        $products = $productsRepository->findAll();
        return $this->json([
            'sucess' => true,
            'data' => json_decode($serializerHelper->convertCollectionToJson($products), true)
        ]);
    }

    #[Route('/product/{id}', name: 'get_product_by_id', methods: ['GET', 'HEAD'])]
    public function getProductById($id): JsonResponse
    {
        $serializerHelper = new SerializerEntities();
        $productsRepository = $this->em->getRepository(CountableProduct::class);
        $products = $productsRepository->find($id);
        return $this->json([
            'sucess' => true,
            'data' => json_decode($serializerHelper->convertCollectionToJson($products),true)
        ]);
    }

    #[Route('/product', name: 'save_product_test', methods: ['POST'])]
    public function insertProductTest(): JsonResponse
    {
        $requestBody = $this->request->getCurrentRequest()->getContent();
        $requestBody = json_decode($requestBody, true);
        $product = new Product();
        $product->setName($requestBody['product']['name']);
        $product->setPrice($requestBody['product']['price']);
        $this->em->persist($product);
        
        $this->em->flush(); 

        return $this->json([
            'sucess' => true,
            'message' => "Se Agrego un nuevo producto con nombre " . $product->getName() . " y con id " . $product->getId(),
            'product' => json_decode($serializerHelper->convertCollectionToJson($product), true)
        ]);
    }

    #[Route('/product/{id}', name: 'update_product_test', methods: ['PUT'])]
    public function updateProduct(int $id): JsonResponse
    {
        $requestBody = $this->request->getCurrentRequest()->getContent();
        $requestBody = json_decode($requestBody, true);
        $productRepository = $this->em->getRepository(Product::class);

        $product = $productRepository->find($id);
        $product->setName($requestBody['name']);
        $product->setPrice($requestBody['price']);
        $this->em->persist($product);
        $this->em->flush();

        return $this->json([
            'sucess' => true,
            'message' => "Producto con id " . $product->getId() . ' Actualizado Satisfactoriamente'
        ]);
    }
}
