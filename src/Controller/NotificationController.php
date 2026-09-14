<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NotificationController extends AbstractController
{
    #[Route('/notifications/{id}/read', name: 'app_notification_read', methods: ['POST'])]
    public function markRead(Notification $notification, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->validateCsrfToken($request);
        $this->denyUnlessRecipient($notification);

        $notification->markAsRead();
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer') ?: $this->generateUrl('app_discover'));
    }

    #[Route('/notifications/read-all', name: 'app_notifications_read_all', methods: ['POST'])]
    public function markAllRead(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->validateCsrfToken($request);

        foreach ($this->getUser()->getNotifications() as $notification) {
            $notification->markAsRead();
        }
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer') ?: $this->generateUrl('app_discover'));
    }

    private function validateCsrfToken(Request $request): void
    {
        if (!$this->isCsrfTokenValid('notifications', (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid notification request.');
        }
    }

    private function denyUnlessRecipient(Notification $notification): void
    {
        if ($notification->getRecipient() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
    }
}
