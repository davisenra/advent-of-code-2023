<?php

declare(strict_types=1);

namespace AdventOfCode\Day4;

use AdventOfCode\PuzzleInterface;
use Illuminate\Support\Collection;

class Puzzle1 implements PuzzleInterface
{
    public function solve(string $input): int
    {
        $lines = collect(explode("\n", $input));
        $cardsData = $lines->map(function (string $line) {
            return explode(':', $line);
        });

        /** @var Collection<Card> $cards */
        $cards = collect([]);
        $matchNumbersRegex = '/[^0-9]/';

        foreach ($cardsData as $cardData) {
            $cardNumber = (int) preg_replace($matchNumbersRegex, '', $cardData[0]);
            [$winningNumbersString, $numbersYouHaveString] = explode('|', $cardData[1]);

            preg_match_all('/\d+/', $winningNumbersString, $winningNumbers);
            preg_match_all('/\d+/', $numbersYouHaveString, $numbersYouHave);

            $winningNumbers = array_map(fn ($number) => (int) $number, $winningNumbers[0]);
            $numbersYouHave = array_map(fn ($number) => (int) $number, $numbersYouHave[0]);

            $card = new Card($cardNumber, $winningNumbers, $numbersYouHave);
            $cards->push($card);
        }

        return $cards->reduce(function (int $acumulator, Card $card) {
            return $acumulator += $card->evaluatePoints();
        }, 0);
    }
}
