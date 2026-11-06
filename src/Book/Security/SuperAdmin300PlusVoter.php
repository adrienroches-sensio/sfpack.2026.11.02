<?php

declare(strict_types=1);

namespace App\Book\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class SuperAdmin300PlusVoter implements VoterInterface
{
    public function __construct(
        private AccessDecisionManagerInterface $decisionManager,
    ) {
    }

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $isSuperAdmin300Plus = $this->decisionManager->decide($token, ['ROLE_ADMIN']);
        if (false === $isSuperAdmin300Plus) {
            return self::ACCESS_ABSTAIN;
        }

        return self::ACCESS_GRANTED;
    }
}
