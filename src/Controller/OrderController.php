<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Dish;
use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends AbstractController
{
    public function getOrdersByClientId($clientId)
    {
        $client = $this->getDoctrine()
            ->getRepository(Client::class)
            ->find($clientId);
        if (!$client){
            return new Response('Client not found');
        }
        $orders = $client->getOrders();
        foreach ($orders as $order){
            $dishesJson = [];
            foreach ($order->getDishes() as $dish){
                $dishesJson[] = $dish->getName();
            }
            $orderJson[] = [
                'id' => $order->getId(),
                'clientId' => $order->getClient()->getId(),
                'dishes' => $dishesJson
            ];
        }
        return new JsonResponse($orderJson);
    }

    public function getOrder($id)
    {
        $order = $this->getDoctrine()
            ->getRepository(Order::class)
            ->find($id);
        if (!$order){
            return new Response('Order not found');
        }
        foreach ($order->getDishes() as $dish){
            $dishesJson[] = $dish->getName();
        }
        $orderJson[] = [
            'id' => $order->getId(),
            'clientId' => $order->getClient()->getId(),
            'dishes' => $dishesJson
        ];

        return new JsonResponse($orderJson);
    }

    public function createOrder(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $clientId = $request->request->get('clientId');
        $client = $this->getDoctrine()
            ->getRepository(Client::class)
            ->find($clientId);
        if (!$client){
            return new Response('Client not found');
        }
        $dishesId = $request->request->get('dishesId');
        if (!$dishesId){
            return new Response('Dishes not found');
        }
        $order = new Order();
        foreach ($dishesId as $dishId){
            $dish = $this->getDoctrine()
                ->getRepository(Dish::class)
                ->find($dishId);
            if (!$dish){
                return new Response('Dish not found');
            }
            $order->addDish($dish);
        }
        $order->setClient($client);
        $entityManager->persist($order);
        $entityManager->flush();
        return new Response('Dish has been created id: '.$order->getId());
    }

    public function patchOrder($id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $order = $this->getDoctrine()
            ->getRepository(Order::class)
            ->find($id);
        if(!$order){
            return new Response('Order not found');
        }
        $clientId = $request->request->get('clientId');
        $client = $this->getDoctrine()
            ->getRepository(Client::class)
            ->find($clientId);
        if (!$client){
            return new Response('Client not found');
        }
        $dishesId = $request->request->get('dishesId');
        if (!$dishesId){
            return new Response('Dishes not found');
        }
        foreach ($dishesId as $dishId){
            $dish = $this->getDoctrine()
                ->getRepository(Dish::class)
                ->find($dishId);
            if (!$dish){
                return new Response('Dish not found');
            }
            $order->addDish($dish);
        }
        $order->setClient($client);
        $entityManager->flush();
        return new Response('Dish has been updated id: '.$order->getId());
    }

    public function deleteOrder($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $order = $entityManager->getRepository(Order::class)->find($id);
        if (!$order) return new Response('Dish not found');
        $entityManager->remove($order);
        $entityManager->flush();
        return new Response('Order with id '.$id.' has been deleted');
    }
}
