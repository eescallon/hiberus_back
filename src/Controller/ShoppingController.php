<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Helper\SerializerEntities;
use App\Controller\BaseController;

use App\Entity\Shopping;
use App\Entity\ShoppingCart;
use App\Entity\Product;
use App\Entity\CountableProduct;
use App\Entity\WeightedProduct;

class ShoppingController extends BaseController
{
    public function __construct(
        EntityManagerInterface $em, 
        RequestStack $request, 
        )
    {
        parent::__construct($em, $request);
    }

    #[Route('/shopping/{id}', name: 'get_shopping', methods: ['GET', 'HEAD'])]
    public function getShoppingCart($id): JsonResponse
    {
        $serializerHelper = new SerializerEntities();
        $shoppingCartRepo = $this->em->getRepository(ShoppingCart::class);
        $shopping = $shoppingCartRepo->findBy(array('shopping' => $id));
        return $this->json([
            'sucess' => true,
            'data' => json_decode($serializerHelper->convertCollectionToJson($shopping), true)
        ]);
    }

    #[Route('/shopping', name: 'create_shopping', methods: ['POST'])]
    public function createShopping(): JsonResponse
    {
        $serializerHelper = new SerializerEntities();

        $shopping = new Shopping();
        $this->em->persist($shopping);
        $this->em->flush(); 

        return $this->json([
            'sucess' => true,
            'shopping' => json_decode($serializerHelper->convertCollectionToJson($shopping), true),
            'message' => "Se creo un nuevo shopping con id " . $shopping->getId()
        ]);
    }

    #[Route('/shopping/add/product/{id}', name: 'add_product_shopping', methods: ['POST'])]
    public function addProductShoppipng(int $id): JsonResponse
    {
        try {
            $serializerHelper = new SerializerEntities();
            $requestBody = $this->request->getCurrentRequest()->getContent();
            $requestBody = json_decode($requestBody, true);
            $productRepo = $this->em->getRepository(Product::class);
            $shoppingRepo = $this->em->getRepository(Shopping::class);
            $countableProductRepo = $this->em->getRepository(CountableProduct::class);
            $weightedProductRepo = $this->em->getRepository(WeightedProduct::class);
            $product = $productRepo->find($requestBody['product']['id']);
            
            $shopping = $shoppingRepo->find($id);
            $shoppingCart = new ShoppingCart();
            $shoppingCart->setShopping($shopping);
            $shoppingCart->setProduct($product);
            $price = 0;
            
            if ($requestBody['isCountable']) {
                $countableProduct = $countableProductRepo->findOneBy(array("product" => $product));
                if ($countableProduct->getTotalAmount() < $requestBody['amount']) {
                    throw new \Exception('No hay cantidad disponible');
                } else {
                    $countableProduct->setTotalAmount($countableProduct->getTotalAmount() - $requestBody['amount']);
                }
                $price = $countableProduct->getProductPrice($requestBody['amount'], $product->getPrice());
            } else {
                $weightedProduct = $weightedProductRepo->findOneBy(array("product" => $product));
                if ($weightedProduct->getWeight() < $requestBody['amount']) {
                    throw new \Exception('No hay cantidad disponible');
                } else {
                    $weightedProduct->setWeight($countableProduct->getWeight() - $requestBody['amount']);

                }
                $price = $weightedProduct->getProductPrice($requestBody['amount'], $product->getPrice());
            }
            $shoppingCart->setAmount($requestBody['amount']);
            $shoppingCart->setSellPrice($price);
            $this->em->persist($shoppingCart);
            $this->em->flush(); 

            return $this->json([
                'sucess' => true,
                'message' => "Se Agrego un nuevo producto al shopping con id " . $shoppingCart->getId(),
                'product' => json_decode($serializerHelper->convertCollectionToJson($shoppingCart), true),
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'sucess' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/shopping/remove/product/{id}', name: 'delete_product_shopping', methods: ['DELETE'])]
    public function removeProductShopping(int $id): JsonResponse
    {
        $requestBody = $this->request->getCurrentRequest()->getContent();
        $requestBody = json_decode($requestBody, true);
        $shoppingCartRepo = $this->em->getRepository(ShoppingCart::class);

        $shoppingCart = $shoppingCartRepo->find($id);
        
        $this->em->remove($shoppingCart);
        $this->em->flush();

        return $this->json([
            'sucess' => true,
            'message' => "Producto con id " . $id . ' Eliminado del Shopping'
        ]);
    }

    #[Route('/shopping/{id}', name: 'get_products_shopping', methods: ['GET'])]
    public function getProductsShopping(int $id): JsonResponse
    {
        $serializerHelper = new SerializerEntities();
        $shoppingRepo = $this->em->getRepository(Shopping::class);
        $shoppingCartRepo = $this->em->getRepository(ShoppingCart::class);

        $shopping = $shoppingRepo->find($id);
        $shoppingCart = $shoppingCartRepo->findBy(array("shopping" => $shopping));
        $products = array();
        foreach($shoppingCart as $key => $value) {
            $products[$key]['amount'] = $value->getAmount();
            $products[$key]['product'] = json_decode($serializerHelper->convertCollectionToJson($value->getProduct()), true);
        }
        
        $this->em->flush();

        return $this->json([
            'sucess' => true,
            'products' => $products
        ]);
    }
}
