<?php

declare(strict_types=1);

namespace App\Book\Security;

use App\Entity\Book;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class LibrarianCanChangeAvailabilityVoter implements VoterInterface
{
    public function __construct(
        private AccessDecisionManagerInterface $decisionManager,
    ) {
    }

    public function vote(TokenInterface $token, mixed $subject, array $attributes, ?Vote $vote = null): int
    {
        [$attribute] = $attributes;
        if ($attribute !== BookPermission::CHANGE_AVAILABILITY) {
            return self::ACCESS_ABSTAIN;
        }

        if (! $subject instanceof Book) {
            return self::ACCESS_ABSTAIN;
        }

        $isLibrarian = $this->decisionManager->decide($token, ['ROLE_LIBRARIAN']);
        if (false === $isLibrarian) {
            $vote?->addReason('User is not a librarian.');

            return self::ACCESS_ABSTAIN;
        }

        $vote?->addReason('User is a librarian.');
        return self::ACCESS_GRANTED;
    }
}
