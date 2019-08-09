<?php


namespace App\Controller;



use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FreeTimeHandler extends AbstractController
{
public function handle(){
    $entityManager = $this->getDoctrine()->getManager();




    $startDay = new \DateTime('2019-08-08');
    $endDay = new \DateTime('2019-08-09');
    $repository = $entityManager->getRepository(Event::class);
    $eventByDate = $repository->findByDay($startDay, $endDay);
    //        dump($startDay);
    //        dump($endDay);




    $free = [];
    foreach ($eventByDate as $index=>$item) {
    if ($index > 0 && $item->getStart()->getDateTime() > $eventByDate[$index - 1]->getEnd()->getDateTime()) {


    $free[] = ["start" => $item->getStart()->getDateTime(), "end" => $eventByDate[$index - 1]->getEnd()->getDateTime() ];
    }
    }


return $free;
}
}
