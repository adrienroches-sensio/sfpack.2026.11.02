<?php

namespace App\Story;

use App\Factory\BookFactory;
use App\Factory\GenreFactory;
use DateTimeImmutable;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;
use function array_values;
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

        GenreFactory::createAll();

        BookFactory::new()
            ->many(count($booksData))
            ->create(static function (int $i) use ($booksData) {
                $bookIndex = $i - 1;
                $bookData = $booksData[$bookIndex];

                unset(
                    $bookData['author'],
                );

                $genres = [];
                foreach ($bookData['genres'] as $genreName) {
                    $genres[] = GenreFactory::findOrCreate(['name' => $genreName]);
                }
                $bookData['genres'] = $genres;

                return $bookData;
            })
        ;
    }
}
