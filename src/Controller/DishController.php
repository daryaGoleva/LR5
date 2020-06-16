<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DishController extends AbstractController
{
    public function getDishesByRestaurantId($restaurantId)
    {
        $restaurant = $this->getDoctrine()
            ->getRepository(Restaurant::class)
            ->find($restaurantId);
        if (!$restaurant){
            return new Response('Restaurant not found');
        }
        $dishes = $restaurant->getDishes();
        if (!$dishes){
            return new Response('This restaurant does not have any dishes');
        }
        foreach ($dishes as $dish){
            $dishArray[] = [
              'id' => $dish->getId(),
              'name' => $dish->getName(),
              'description' => $dish->getDescription(),
              'price' => $dish->getPrice(),
              'restaurant' => $dish->getRestaurant()->getName()
            ];
        }
        return new JsonResponse($dishArray);
    }

    public function getDish($id)
    {
        $dish = $this->getDoctrine()
            ->getRepository(Dish::class)
            ->find($id);
        if (!$dish){
            return new Response('Dish not found');
        }
        $dishJson = [
            'id' => $dish->getId(),
            'name' => $dish->getName(),
            'description' => $dish->getDescription(),
            'price' => $dish->getPrice(),
            'restaurant' => $dish->getRestaurant()->getName()
        ];
        return new JsonResponse($dishJson);
    }

    public function createDish(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dish = new Dish();
        $dish->setName($request->request->get('name'));
        $dish->setPrice($request->request->get('price'));
        $dish->setDescription($request->request->get('description'));
        $restaurantId = $request->request->get('restaurantId');
        $restaurant = $this->getDoctrine()
            ->getRepository(Restaurant::class)
            ->find($restaurantId);
        $dish->setRestaurant($restaurant);
        $entityManager->persist($dish);
        $entityManager->flush();
        return new Response('Dish has been created id: '.$dish->getId());
    }

    public function patchDish($id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dish = $this->getDoctrine()
            ->getRepository(Dish::class)
            ->find($id);
        if (!$dish) {
            return new Response('Dish not found');
        } else {
            $dish->setName($request->request->get('name'));
            $dish->setDescription($request->request->get('description'));
            $dish->setPrice($request->request->get('price'));
            $restaurantId = $request->request->get('restaurantId');
            $restaurant = $this->getDoctrine()
                ->getRepository(Restaurant::class)
                ->find($restaurantId);
            $dish->setRestaurant($restaurant);
            $entityManager->flush();
            return new Response('Dish has been updated id: '.$dish->getId());
        }
    }

    public function deleteDish($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dish = $entityManager->getRepository(Dish::class)->find($id);
        if (!$dish) return new Response('Dish not found');
        $entityManager->remove($dish);
        $entityManager->flush();
        return new Response('Dish with id '.$id.' has been deleted');
    }
}
