<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class NotificationService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function create(User $recipient, string $title, string $message, string $type = 'info', ?string $link = null): Notification
    {
        $notification = (new Notification())
            ->setRecipient($recipient)
            ->setTitle($title)
            ->setMessage($message)
            ->setType($type)
            ->setLink($link);

        $this->entityManager->persist($notification);

        return $notification;
    }
}
