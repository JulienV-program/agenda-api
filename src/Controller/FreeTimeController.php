<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class FreeTimeController extends AbstractController
{
    private $freeTimeHandler;
    private $serializer;

    public function __construct(FreeTimeHandler $freeTimeHandler, SerializerInterface $serializer)
    {
        $this->freeTimeHandler = $freeTimeHandler;
        $this->serializer = $serializer;
    }

    public function __invoke()
    {
        $response = $this->freeTimeHandler->handle();


       return $this->serializer->serialize($response, 'json');
    }
}
