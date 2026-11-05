<?php

declare(strict_types=1);

namespace App\Search\Book;

use SortDirection;
use function mb_strtolower;

final class BookSearchCriteria
{
    public SortDirection|null $direction = null;

    public function __construct(
        public string|null $q = null,
        public string|null $author = null,
        public string|null $genre = null,
        public bool|null $available = null,
        public BookSortableColumn|null $sort = null,
        string|null $direction = null,
    ) {
        if (null !== $direction) {
            $this->direction = mb_strtolower($direction) === 'asc' ? SortDirection::Ascending : SortDirection::Descending;
        }
    }
}
