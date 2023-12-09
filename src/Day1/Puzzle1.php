<?php

declare(strict_types=1);

namespace AdventOfCode\Day1;

use AdventOfCode\PuzzleInterface;

class Puzzle1 implements PuzzleInterface
{
    public function solve(string $input): int
    {
        $lines = explode("\n", $input);
        $sum = 0;

        foreach ($lines as $line) {
            $chars = mb_str_split($line);
            $digits = array_filter($chars, fn ($char) => is_numeric($char));
            $digits = array_values($digits);

            if (1 === count($digits)) {
                $digitsToSum = $digits[0].$digits[0];
                $sum += (int) $digitsToSum;
                continue;
            }

            $firstDigit = $digits[0];
            $lastDigit = end($digits);
            $digitsToSum = $firstDigit.$lastDigit;
            $sum += (int) $digitsToSum;
        }

        return $sum;
    }
}
