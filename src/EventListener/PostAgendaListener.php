<?php


namespace App\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\End;
use App\Entity\Event;
use App\Entity\Start;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Google_Client;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PostAgendaListener implements EventSubscriberInterface
{
    private $repository;
    private $userRepository;
    private $om;


    public function __construct(EventRepository $repository, UserRepository $userRepository, ObjectManager $om )
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
        $this->om = $om;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setGoogle', EventPriorities::PRE_WRITE],
        ];
    }

    public function setGoogle(ViewEvent $event): void
    {
        $repository = $this->repository;
        $userRepository = $this->userRepository;

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

        $calendarId = 'primary';


        $dataEvent = $event->getControllerResult();
            //on créé un nouvel Event google calendar en récupérant les donnée stockés en bdd
            if ($dataEvent instanceof Event)
            {
                if ($event->getRequest()->getMethod() == "POST")
                {
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
                } elseif ($event->getRequest()->getMethod() == "DELETE")
                {
                    $service->events->delete($calendarId, $dataEvent->getGoogleId());
                }

            }


        }



}
