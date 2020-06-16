<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends AbstractController
{
    public function getClients () {
        $clients = $this->getDoctrine()
            ->getRepository(Client::class)
            ->findAll();
        if (!$clients){
            return new Response('Clients not found');
        }
        $clientsJson = array();

        foreach($clients as $client) {
            $clientsJson[] = array(
                'id' => $client->getId(),
                'name' => $client->getName(),
                'phone' => $client->getPhone(),
                'address' => $client->getAddress()
            );
        }

        return new JsonResponse($clientsJson);
    }

    public function getClient ($id) {
        $client = $this->getDoctrine()
            ->getRepository(Client::class)
            ->find($id);
        if (!$client){
            return new Response('Client not found');
        }
        $clientJSON = [
            'id' => $client->getId(),
            'name' => $client->getName(),
            'phone' => $client->getPhone(),
            'address' => $client->getAddress()
        ];
        return new JsonResponse($clientJSON);
    }

    public function createClient (Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $client = new Client();
        $client->setName($request->request->get('name'));
        $client->setPhone($request->request->get('phone'));
        $client->setAddress($request->request->get('address'));
        $entityManager->persist($client);
        $entityManager->flush();
        return new Response('Client has been created id: '.$client->getId());
    }

    public function patchClient ($id, Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $client = $this->getDoctrine()
            ->getRepository(Client::class)
            ->find($id);
        if (!$client) {
            return new Response('Client not found');
        } else {
            $client->setName($request->request->get('name'));
            $client->setPhone($request->request->get('phone'));
            $entityManager->flush();
            return new Response('Client has been updated id: ' . $client->getId());
        }
    }

    public function deleteClient ($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $client = $entityManager->getRepository(Client::class)->find($id);
        if (!$client) return new Response('Client not found');
        $entityManager->remove($client);
        $entityManager->flush();
        return new Response('Client with id '.$id.' has been deleted');
    }
}
