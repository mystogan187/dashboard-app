<?php

declare(strict_types=1);

namespace App\Dashboard\AiChatbot\Infrastructure\Controller;

use App\Dashboard\AiChatbot\Application\SendMessage\SendMessageCommand;
use App\Dashboard\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/chat', name: 'chat_')]
final class ChatController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus
    ) {}

    #[Route('/send', name: 'send', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function send(Request $request): mixed
    {
        try {
            $data = json_decode($request->getContent(), true);
            $userMessage = $data['message'] ?? '';

            if (empty($userMessage)) {
                return $this->json(['error' => 'Message is required'], 400);
            }

            $command = new SendMessageCommand($userMessage);
            return $this->commandBus->dispatch($command);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}