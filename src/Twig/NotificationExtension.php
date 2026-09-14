<?php

namespace App\Twig;

use App\Entity\User;
use App\Repository\NotificationRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class NotificationExtension extends AbstractExtension
{
    public function __construct(private NotificationRepository $notifications)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('recent_notifications', [$this, 'recentNotifications']),
            new TwigFunction('unread_notification_count', [$this, 'unreadCount']),
        ];
    }

    public function recentNotifications(?User $user): array
    {
        return $user ? $this->notifications->findRecentForUser($user) : [];
    }

    public function unreadCount(?User $user): int
    {
        return $user ? $this->notifications->countUnreadForUser($user) : 0;
    }
}
