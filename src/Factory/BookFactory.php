<?php

namespace App\Factory;

use App\Entity\Book;
use DateTimeImmutable;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use function rtrim;

/**
 * @extends PersistentProxyObjectFactory<Book>
 */
final class BookFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Book::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'title' => rtrim(self::faker()->sentence(random_int(2, 5)), '.'),
            'isbn' => self::faker()->unique()->isbn13(),
            'summary' => self::faker()->sentences(6, true),
            'publicationDate' => DateTimeImmutable::createFromInterface(self::faker()->dateTimeThisDecade()),
            'available' => self::faker()->boolean(),
            'language' => self::faker()->languageCode(),
            'coverUrl' => self::faker()->imageUrl(),
            'addedBy' => null,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Book $book): void {})
        ;
    }
}
