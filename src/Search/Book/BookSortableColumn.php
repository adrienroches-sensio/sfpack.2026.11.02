<?php

declare(strict_types=1);

namespace App\Search\Book;

enum BookSortableColumn: string
{
    case Title = 'title';
    case Author = 'author';
    case PublicationDate = 'publicationDate';
}
