<?php

declare(strict_types=1);

namespace App\Book\Security;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use function in_array;

final class BookVoter extends Voter
{
    public function __construct(
        private AccessDecisionManagerInterface $decisionManager,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [BookPermission::EDIT_DETAILS, BookPermission::CHANGE_AVAILABILITY], true)
            && $subject instanceof Book
        ;
    }

    /**
     * @param Book $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return match($attribute) {
            BookPermission::EDIT_DETAILS => $this->decisionManager->decide($token, ['ROLE_LIBRARIAN']) || $subject->getAddedBy() === $user,
            BookPermission::CHANGE_AVAILABILITY => $this->decisionManager->decide($token, ['ROLE_LIBRARIAN']),
            default => false,
        };
    }
}
