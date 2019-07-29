<?php

namespace App\Controller;

use App\Entity\End;
use App\Entity\Event;
use App\Entity\Start;
use Google_Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventRepository;

class GetGoogleAgendaController extends AbstractController
{
    /**
     * @Route("/get-agenda", name="get_google_agenda")
     */
    public function index(EventRepository $repository)
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Calendar API PHP Quickstart');
        $client->setScopes(\Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig('credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = 'token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        // Get the API client and construct the service object.
//        $client = getClient();
        $service = new \Google_Service_Calendar($client);

// Print the next 10 events on the user's calendar.
        $calendarId = 'primary';
        $optParams = array(
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        //si la liste d'event est vide on ne fais rien
        if (empty($events)) {
            print "No upcoming events found.\n";
        } else {
            print "Upcoming events:\n";
            // pour chaque event de la liste on vérifie si un doublon existe déjà en BDD
            foreach ($events as $event) {
                $doublon = $repository->findOneBy(['googleId' => $event->id]);
//                dump($doublon);
                dump($event);
                //si aucun doublon n'est trouvé on ajoute l'event en BDD
                if ($doublon === null)
                {
                    $newEvent = new Event();
                    $newEvent->setSummary($event->summary);
                    $start = new Start();
                    $startDate = new \DateTime($event->start->dateTime);
                    $start->setDateTime($startDate);
                    $end = new End();
                    $endDate = new \DateTime($event->end->dateTime);
                    $end->setDateTime($endDate);
                    $newEvent->setStart( $start);
                    $newEvent->setEnd( $end);
                    $newEvent->setGoogleId($event->id);
//                    dump($newEvent);
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($newEvent);
                    $manager->flush();
                }
            }


        }

        $databaseEvents = $repository->findAll();
        foreach ($databaseEvents as $dataEvent)
        {
            //on créé un nouvel Event google calendar en récupérant les donnée stockés en bdd
            $Gevent = new \Google_Service_Calendar_Event(array(
                'summary' => $dataEvent->getSummary(),
                'location' => null,
                'description' => null,
                'start' => array(
                    'dateTime' => $dataEvent->getStart()->getDateTime()->format('c'),
                    'timeZone' => null,
                ),
                'end' => array(
                    'dateTime' => $dataEvent->getEnd()->getDateTime()->format('c'),
                    'timeZone' => null,
                ),
                'recurrence' => null,
                'attendees' => array(
                    // On peut ajouter le mail du client et du pro ici
//                    array('email' => 'lpage@example.com'),
//                    array('email' => 'sbrin@example.com'),
                ),
                'reminders' => null,

            ));
            //on appel le service google et on envoie le nouvel event
            $service->events->insert($calendarId, $Gevent);
        }

            return $this->render('get_google_agenda/index.html.twig', [
                'controller_name' => 'GetGoogleAgendaController',
                'events' => $events,
                'dataEvent' => $databaseEvents

            ]);
        }


}
