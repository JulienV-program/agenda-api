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
        $client->setScopes(\Google_Service_Calendar::CALENDAR_READONLY);
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

        if (empty($events)) {
            print "No upcoming events found.\n";
        } else {
//            $newEvent = new Event();
//            $newSerialized = serialize($events[0]);
//            dump($newSerialized);
//
//            $newSerialized = str_replace('Google_Service_Calendar_Event', 'Event', $newSerialized);
//            dump($newSerialized);
//
//            $goodSerialized = preg_replace_callback('!s:\d+:"(.*?)";!s', function($m) { return "s:" . strlen($m[1]) . ':"'.$m[1].'";'; }, $newSerialized);
//            dump($goodSerialized);
//            $newEvent = unserialize($goodSerialized);
//            dump($newSerialized);
//            dump($newEvent);
//            $manager = $this->getDoctrine()->getManager();
//            $manager->persist($newEvent);
//            $manager->flush();

            print "Upcoming events:\n";
            foreach ($events as $event) {
                $doublon = $repository->findOneBy(['googleId' => $event->id]);
                dump($doublon);
                dump($event);
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
                    dump($newEvent);
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($newEvent);
                    $manager->flush();
                }


            }
            }


            return $this->render('get_google_agenda/index.html.twig', [
                'controller_name' => 'GetGoogleAgendaController',
            ]);
        }


}
