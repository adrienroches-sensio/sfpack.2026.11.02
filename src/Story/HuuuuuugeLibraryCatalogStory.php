<?php

namespace App\Story;

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

        BookFactory::new()
            ->many(250)
            ->create(static function () {
                $numberOfGenres = random_int(0, 3);

                if ($numberOfGenres <= 0) {
                    return [];
                }

                return [
                    'genres' => GenreFactory::randomSet($numberOfGenres)
                ];
            })
        ;
    }
}
