<?php

declare(strict_types=1);

namespace App\Book\Security;

use App\Entity\Book;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class LibrarianCanEditVoter implements VoterInterface
{
    public function __construct(
        private AccessDecisionManagerInterface $decisionManager,
    ) {
    }

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        [$attribute] = $attributes;
        if ($attribute !== BookPermission::EDIT_DETAILS) {
            return self::ACCESS_ABSTAIN;
        }

        if (! $subject instanceof Book) {
            return self::ACCESS_ABSTAIN;
        }

        $isLibrarian = $this->decisionManager->decide($token, ['ROLE_LIBRARIAN']);
        if (false === $isLibrarian) {
            return self::ACCESS_ABSTAIN;
        }

        return self::ACCESS_GRANTED;
    }
}
