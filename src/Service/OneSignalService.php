<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\Object_;

class OneSignalService
{
    private $eventRepository;
    private $userRepository;

    private const JOINED = ' joined the ';

    private const FULLNESS = ' is almost full';

    private const ONE_DAY = '  is starting in 24 hours';

    private const ONE_HOUR = '  is starting in 1 hour';

    private const APP_ID = 'b6013fa6-1fc9-4afa-8236-4dd009fd798d';

    private const API_KEY = 'N2NlYTMzOTItNDc2Ny00ODY3LWE5OGYtOWQ1Y2NmMWE4ZDYx';

    public function __construct(EventRepository $repository, UserRepository $userRepo)
    {
        $this->eventRepository = $repository;
        $this->userRepository = $userRepo;
    }

    /**
     * @param Event $event
     * @param User $user
     */
    public function joinedEvent(Event $event, User $user)
    {
        $members = $this->eventRepository->getMembers($event, $user);
        $idEvent = $event->getId();
        $joined = $event->getJoinLimit();
        $fullness = count($members)/$joined;
        $this->oneSignal($user->getFirstName() . self::JOINED . $event->getName(), $members, $idEvent);
        $fullness < 0.7 ?: $this->oneSignal($event->getName() . self::FULLNESS, $members, $idEvent);
    }

    public function eventStarted()
    {
        $oneDay = date_add(date_create(date('Y-m-d H:i:00')), date_interval_create_from_date_string('1 day'));
        $events['oneDay'] = $this->eventRepository->findBy(['date' => $oneDay]);
        $oneHour = date_add(date_create(date('Y-m-d H:i:00')), date_interval_create_from_date_string('1 hour'));
        $events['oneHour'] = $this->eventRepository->findBy(['date' => $oneHour]);
        array_map(function ($event) {
            $this->oneSignal($event->getName() . self::ONE_DAY, $this->eventRepository->getMembers($event), $event->getId());
        }, $events['oneDay']);
        array_map(function ($event) {
            $this->oneSignal($event->getName() . self::ONE_HOUR, $this->eventRepository->getMembers($event), $event->getId());
        }, $events['oneHour']);
    }

    private function oneSignal(string $message, $to, $id)
    {
        $getEvent = $this->eventRepository->findOneBy(['id' => $id]);
        $context = [];

        $content = [
            'en' => $message
        ];
        $fields = [
            'app_id' => self::APP_ID,
            'include_player_ids' => $to,
            'data' => [
                'event' => [
                    'id' => $getEvent->getId(),
                    'name' => $getEvent->getName(),
                    'datetime' => $getEvent->getDate(),
                    'address' => $getEvent->getAddress(),
                    'coordinates' => $getEvent->getCoordinates(),
                    'description' => $getEvent->getDescription(),
                    'join_limit' => $getEvent->getJoinLimit(),
                    'joined' => count($getEvent->getMembers()),
                    'organizer' => [
                        'id' => $getEvent->getOrganizer()->getId(),
                        'avatar' => 'http://159.65.199.71' . $getEvent->getOrganizer()->getAvatar(),
                        'name' => $getEvent->getOrganizer()->getFirstName()
                        ],
                    'members' => array_map(function (User $user) use ($context) {
                                return [
                                    'id' => $user->getId(),
                                    'name' => $user->getFirstName(),
                                    'phone' => $user->getPhone(),
                                    'avatar' => 'http://159.65.199.71' . $user->getAvatar()
                                ];
                            }, $getEvent->getMembers()->toArray()),
                    'community_id' => $getEvent->getCommunity()->getId()
                ],
            ],
            'contents' => $content,
        ];

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic' . self::API_KEY
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_exec($ch);
        curl_close($ch);
    }
}
