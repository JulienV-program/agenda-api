<?php


namespace App\Controller;



use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FreeTimeHandler extends AbstractController
{
public function handle($data){
    $entityManager = $this->getDoctrine()->getManager();

//    dump($data);


//    $startDay = new \DateTime('2019-08-08');
//    $endDay = new \DateTime('2019-08-09');
    $startDay = new \DateTime($data['start']);
    $endDay = new \DateTime($data['end']);

    $repository = $entityManager->getRepository(Event::class);
    $eventByDate = $repository->findByDay($startDay, $endDay);
    //        dump($startDay);
    //        dump($endDay);




    $free = [];
    $len = count($eventByDate);
    foreach ($eventByDate as $index=>$item) {
        if ($index === 0 && $item->getStart()->getDateTime() > $startDay)
        {
            $free[] = ["start" => $startDay, "end" => $item->getStart()->getDateTime() ];
        }

        elseif ($index > 0 && $item->getStart()->getDateTime() > $eventByDate[$index - 1]->getEnd()->getDateTime())
        {
            $free[] = ["start" => $eventByDate[$index - 1]->getEnd()->getDateTime(), "end" => $item->getStart()->getDateTime() ];
         }

        elseif ($index === $len - 1 && $item->getEnd()->getDateTime() < $endDay)
        {
            $free[] = ["start" => $item->getEnd()->getDateTime(), "end" => $endDay ];
        }
    }


return $free;
}
}
