<?php

namespace App\Story;

use App\Factory\AuthorFactory;
use App\Factory\BookFactory;
use App\Factory\GenreFactory;
use App\Factory\LoanFactory;
use App\Factory\UserFactory;
use DateTimeImmutable;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;
use function array_values;
use function count;
use function dirname;
use function floor;

#[AsFixture('catalog')]
final class LibraryCatalogStory extends Story
{
    public function build(): void
    {
        UserFactory::createAll();

        GenreFactory::createAll();

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

        BookFactory::new()
            ->many(count($booksData))
            ->create(static function (int $i) use ($booksData): array {
                $bookIndex = $i - 1;
                $bookData = $booksData[$bookIndex];

                $genres = [];
                foreach ($bookData['genres'] as $genreName) {
                    $genres[] = GenreFactory::findOrCreate(['name' => $genreName]);
                }
                $bookData['genres'] = $genres;

                $bookData['authors'] = [AuthorFactory::findOrCreate(['name' => $bookData['author']])];
                unset($bookData['author']);

                $bookData['addedBy'] = $i % 3 === 0 ? UserFactory::find(['username' => 'reader']) : UserFactory::find(['username' => 'librarian']);

                return $bookData;
            })
        ;

        LoanFactory::new()
            ->many(floor(count($booksData) / 2))
            ->create(static function (): array {
                return [
                    'book' => BookFactory::find(['available' => true]),
                    'user' => UserFactory::random(),
                ];
            })
        ;
    }
}
