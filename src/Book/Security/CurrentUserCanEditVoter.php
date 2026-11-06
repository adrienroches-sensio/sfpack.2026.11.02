<?php

declare(strict_types=1);

namespace App\Book\Security;

use App\Entity\Book;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\CacheableVoterInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use function is_a;

final class CurrentUserCanEditVoter implements VoterInterface, CacheableVoterInterface
{
    public function vote(TokenInterface $token, mixed $subject, array $attributes, ?Vote $vote = null): int
    {
        [$attribute] = $attributes;
        if ($attribute !== BookPermission::EDIT_DETAILS) {
            return self::ACCESS_ABSTAIN;
        }

        if (! $subject instanceof Book) {
            return self::ACCESS_ABSTAIN;
        }

        $isSameUser = $subject->getAddedBy() === $token->getUser();
        if (false === $isSameUser) {
            $vote?->addReason('Not the user who added this book.');

            return self::ACCESS_ABSTAIN;
        }

        $vote?->addReason('It is the same user who added this book.');
        return self::ACCESS_GRANTED;
    }

    public function supportsAttribute(string $attribute): bool
    {
        return $attribute === BookPermission::EDIT_DETAILS;
    }

    public function supportsType(string $subjectType): bool
    {
        return is_a($subjectType, Book::class, true);
    }
}
