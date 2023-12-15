<?php

declare(strict_types=1);

namespace AdventOfCode\Day4;

use Illuminate\Support\Collection;

readonly class Card
{
    /** @property Collection<int> $winningNumbers */
    private Collection $winningNumbers;
    /** @property Collection<int> $numbersYouHave */
    private Collection $numbersYouHave;

    /**
     * @param int[] $winningNumbers
     * @param int[] $numbersYouHave
     */
    public function __construct(
        public int $cardNumber,
        array $winningNumbers,
        array $numbersYouHave,
    ) {
        $this->winningNumbers = collect($winningNumbers);
        $this->numbersYouHave = collect($numbersYouHave);
    }

    public function evaluatePoints(): int
    {
        $points = 0;

        foreach ($this->numbersYouHave as $number) {
            if ($this->winningNumbers->contains($number)) {
                0 === $points ? ++$points : $points *= 2;
            }
        }

        return $points;
    }
}
