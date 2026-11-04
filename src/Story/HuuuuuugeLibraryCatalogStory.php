<?php

namespace App\Story;

use App\Factory\AuthorFactory;
use App\Factory\BookFactory;
use App\Factory\GenreFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;
use function random_int;

#[AsFixture('huge_catalog')]
final class HuuuuuugeLibraryCatalogStory extends Story
{
    public function build(): void
    {
        GenreFactory::createAll();

        AuthorFactory::createMany(150);

        BookFactory::new()
            ->many(250)
            ->create(static function () {
                $numberOfGenres = random_int(0, 3);

                if ($numberOfGenres <= 0) {
                    return [];
                }

                $numberOfAuthors = random_int(1, 3);

                return [
                    'genres' => GenreFactory::randomSet($numberOfGenres),
                    'authors' => AuthorFactory::randomSet($numberOfAuthors),
                ];
            })
        ;
    }
}
