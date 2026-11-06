<?php

declare(strict_types=1);

namespace App\Book\Security;

use DateTimeImmutable;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class OnlyAvailableOnBlackFridayVoter implements VoterInterface
{
    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        return (new DateTimeImmutable()) >= new DateTimeImmutable('last friday of next month') ? self::ACCESS_GRANTED : self::ACCESS_ABSTAIN;
    }
}
