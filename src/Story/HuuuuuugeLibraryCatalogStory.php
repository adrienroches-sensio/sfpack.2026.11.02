<?php

namespace App\Story;

use App\Factory\BookFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture('huge_catalog')]
final class HuuuuuugeLibraryCatalogStory extends Story
{
    public function build(): void
    {
        BookFactory::createMany(250);
    }
}
