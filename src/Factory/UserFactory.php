<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public static function class(): string
    {
        return User::class;
    }

    public static function createAll(): void
    {
        self::createOne([
            'username' => 'admin',
            'password' => 'admin',
            'roles' => ['ROLE_ADMIN'],
        ]);

        self::createOne([
            'username' => 'manager',
            'password' => 'manager',
            'roles' => ['ROLE_MANAGER'],
        ]);

        self::createOne([
            'username' => 'librarian',
            'password' => 'librarian',
            'roles' => ['ROLE_LIBRARIAN'],
        ]);

        self::createOne([
            'username' => 'webmaster',
            'password' => 'webmaster',
            'roles' => ['ROLE_WEBMASTER'],
        ]);

        self::createOne([
            'username' => 'reader',
            'password' => 'reader',
        ]);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'password' => self::faker()->password(10),
            'roles' => [],
            'username' => self::faker()->userName(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this->afterInstantiate(fn (User $user) =>
            $user->setPassword($this->hasher->hashPassword($user, $user->getPassword()))
        );
    }
}
