<?php

declare(strict_types=1);

namespace App\Loan\Event;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class LoanSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    #[AsEventListener]
    public function log(LoanCreatedEvent $event): void
    {
        $this->logger->info('A loan was made for "{user}" on "{book}"', [
            'user' => $event->loan->getUser()->getUserIdentifier(),
            'book' => $event->loan->getBook()->getTitle(),
        ]);
    }
}
