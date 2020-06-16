<?php

namespace App\Controller;

use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestaurantController extends AbstractController
{
    public function getRestaurants () {
        $restaurants = $this->getDoctrine()
            ->getRepository(Restaurant::class)
            ->findAll();
        if (!$restaurants){
            return new Response('Restaurants not found');
        }
        $restaurantsJson = array();

        foreach($restaurants as $restaurant) {
            $restaurantsJson[] = array(
                'id' => $restaurant->getId(),
                'name' => $restaurant->getName(),
                'description' => $restaurant->getDescription()
            );
        }

        return new JsonResponse($restaurantsJson);
    }

    public function getRestaurant ($id) {
        $restaurant = $this->getDoctrine()
            ->getRepository(Restaurant::class)
            ->find($id);
        if (!$restaurant){
            return new Response('Restaurant not found');
        }
        $clientJSON = [
            'id' => $restaurant->getId(),
            'name' => $restaurant->getName(),
            'description' => $restaurant->getDescription()
        ];
        return new JsonResponse($clientJSON);
    }

    public function createRestaurant (Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $restaurant = new Restaurant();
        $restaurant->setName($request->request->get('name'));
        $restaurant->setDescription($request->request->get('description'));
        $entityManager->persist($restaurant);
        $entityManager->flush();
        return new Response('Restaurant has been created id: '.$restaurant->getId());
    }

    public function patchRestaurant ($id, Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $restaurant = $this->getDoctrine()
            ->getRepository(Restaurant::class)
            ->find($id);
        if (!$restaurant) {
            return new Response('Restaurant not found');
        } else {
            $restaurant->setName($request->request->get('name'));
            $restaurant->setDescription($request->request->get('description'));
            $entityManager->flush();
            return new Response('Restaurant has been updated id: ' . $restaurant->getId());
        }
    }

    public function deleteRestaurant ($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $restaurant = $entityManager->getRepository(Restaurant::class)->find($id);
        if (!$restaurant) return new Response('Restaurant not found');
        $entityManager->remove($restaurant);
        $entityManager->flush();
        return new Response('Restaurant with id '.$id.' has been deleted');
    }
}
