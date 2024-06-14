<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Helper\SerializerEntities;
use App\Controller\BaseController;

use App\Entity\Inventory;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\CountableProduct;
use App\Entity\WeightedProduct;

class InventoryController extends BaseController
{
    public function __construct(
        EntityManagerInterface $em, 
        RequestStack $request, 
        )
    {
        parent::__construct($em, $request);
    }

    #[Route('/inventory', name: 'get_inventory', methods: ['GET', 'HEAD'])]
    public function getInventory(): JsonResponse
    {
        $serializerHelper = new SerializerEntities();
        $cartItemRepo = $this->em->getRepository(CartItem::class);
        $inventoryRepo = $this->em->getRepository(Inventory::class);
        $inventory = $inventoryRepo->find(1);
        if ($inventory == null) {
            $inventory = new Inventory();
            $inventory->setDate(new \DateTime());
            $this->em->persist($inventory);
            $this->em->flush();
        }
        $cartItem = $cartItemRepo->findBy(array("inventory" => $inventory));
        $response = array(
            "inventory" => json_decode($serializerHelper->convertCollectionToJson($inventory), true),
            "products" => json_decode($serializerHelper->convertCollectionToJson($cartItem), true)
        );
        return $this->json([
            'sucess' => true,
            'data' => $response
        ]);
    }

    #[Route('/inventory', name: 'create_inventory', methods: ['POST'])]
    public function createInventory(): JsonResponse
    {
        $inventory = new Inventory();
        $inventory->setDate(new \DateTime());
        $this->em->persist($inventory);
        $this->em->flush(); 

        return $this->json([
            'sucess' => true,
            'message' => "Se creo un nuevo inventario con id " . $inventory->getId()
        ]);
    }

    #[Route('/inventory/add/product', name: 'add_product_inventory', methods: ['POST'])]
    public function addProductInventory(): JsonResponse
    {
        $serializerHelper = new SerializerEntities();
        $requestBody = $this->request->getCurrentRequest()->getContent();
        $requestBody = json_decode($requestBody, true);
        $productRepo = $this->em->getRepository(Product::class);
        $inventoryRepo = $this->em->getRepository(Inventory::class);
        $cartItem = new CartItem();
        $product = $productRepo->find($requestBody['product']['id']);
        $inventory = $inventoryRepo->find($requestBody['inventory']);
        $cartItem->setInventory($inventory);
        $cartItem->setCountable($requestBody['isCountable']);
        if ($requestBody['isCountable']) {
            $countableProduct = new CountableProduct();
            $countableProduct->setProduct($product);
            $countableProduct->setTotalAmount($requestBody['amount']);
            $cartItem->setCountableProduct($countableProduct);
            $this->em->persist($countableProduct);
        } else {
            $weightedProduct = new WeightedProduct();
            $weightedProduct->setProduct($product);
            $weightedProduct->setWeight($requestBody['amount']);
            $cartItem->setWeightedProduct($weightedProduct);
            $this->em->persist($weightedProduct);
        }
        $this->em->persist($cartItem);
        $this->em->flush(); 

        return $this->json([
            'sucess' => true,
            'message' => "Se Agrego un nuevo producto al inventario con id " . $cartItem->getId(),
            'product' => json_decode($serializerHelper->convertCollectionToJson($cartItem), true)
        ]);
    }

    #[Route('/inventory/remove/product/{id}', name: 'delete_product_inventory', methods: ['DELETE'])]
    public function deleteProductInventory(int $id): JsonResponse
    {
        $requestBody = $this->request->getCurrentRequest()->getContent();
        $requestBody = json_decode($requestBody, true);
        $cartItemRepo = $this->em->getRepository(CartItem::class);

        $cartItem = $cartItemRepo->find($id);
        
        $this->em->remove($cartItem);
        $this->em->flush();

        return $this->json([
            'sucess' => true,
            'message' => "Producto con id " . $id . ' Eliminado del Inventario'
        ]);
    }
}
