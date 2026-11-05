<?php

declare(strict_types=1);

namespace App\Search\Book;

enum BookSortableColumn: string
{
    case Title = 'title';
    case Author = 'author';
    case PublicationDate = 'publicationDate';

    public function queryColumn(): string
    {
        return match($this) {
            self::Title => 'book.title',
            self::PublicationDate => 'book.publicationDate',
            self::Author => 'author.name',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::Title => 'Title',
            self::Author => 'Author',
            self::PublicationDate => 'Published',
        };
    }
}
