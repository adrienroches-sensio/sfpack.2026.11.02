<?php

namespace App\Factory;

use App\Entity\Loan;
use App\Loan\LoanStatus;
use DateTimeImmutable;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Loan>
 */
final class LoanFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Loan::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $loanDate = \DateTimeImmutable::createFromMutable(self::faker()->dateTime());

        return [
            'book' => BookFactory::new(),
            'loanDate' => $loanDate,
            'dueDate' => $loanDate->modify('+14 days'),
            'status' => self::faker()->randomElement(LoanStatus::cases()),
            'user' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
             ->afterInstantiate(function(Loan $loan): void {
                 if ($loan->getStatus() === LoanStatus::Returned) {
                     $loan->setReturnDate(DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween(
                         $loan->getLoanDate()->format('c'),
                         $loan->getDueDate()->format('c'),
                     )));

                     return;
                 }

                 $loan->setReturnDate(null);
             })
        ;
    }
}
