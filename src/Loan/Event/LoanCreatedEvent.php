<?php

declare(strict_types=1);

namespace App\Loan\Event;

use App\Entity\Loan;

final class LoanCreatedEvent
{
    public function __construct(
        public Loan $loan,
    ) {
    }
}
