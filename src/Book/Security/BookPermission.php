<?php

declare(strict_types=1);

namespace App\Book\Security;

enum BookPermission
{
    public const EDIT_DETAILS = 'book/edit';
    public const CHANGE_AVAILABILITY = 'book/change-availability';
}
