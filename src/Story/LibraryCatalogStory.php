<?php

namespace App\Story;

use App\Factory\BookFactory;
use DateTimeImmutable;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;
use function array_values;
use function count;
use function dirname;

#[AsFixture('catalog')]
final class LibraryCatalogStory extends Story
{
    public function build(): void
    {
        /**
         * @var list<array{
         *     'title': non-empty-string,
         *     'isbn': numeric-string,
         *     'summary': non-empty-string,
         *     'publicationDate': DateTimeImmutable,
         *     'available': boolean,
         *     'language': non-empty-string,
         *     'coverUrl': non-empty-string,
         *     'author': non-empty-string,
         *     'genres': list<non-empty-string>,
         * }> $booksData
         */
        $booksData = array_values(require dirname(__DIR__, 2) . '/fixtures/book_fixtures.php');

        BookFactory::createMany(count($booksData), static function (int $i) use ($booksData): array {
            $bookIndex = $i - 1;
            $bookData = $booksData[$bookIndex];

            unset(
                $bookData['author'],
                $bookData['genres'],
            );

            return $bookData;
        });
    }
}
