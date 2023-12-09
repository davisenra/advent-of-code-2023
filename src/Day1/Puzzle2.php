<?php

declare(strict_types=1);

namespace AdventOfCode\Day1;

use AdventOfCode\PuzzleInterface;

class Puzzle2 implements PuzzleInterface
{
    public function solve(string $input): int
    {
        $stringDigits = [
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
        ];

        $sum = 0;
        $lines = explode("\n", $input);

        foreach ($lines as $line) {
            $digitsFound = [];

            $chars = mb_str_split($line);
            $digits = array_filter($chars, fn ($char) => is_numeric($char));

            if (!empty($digits)) {
                foreach ($digits as $position => $digit) {
                    $digitsFound[] = [
                        'position' => (int) $position,
                        'digit' => (int) $digit,
                    ];
                }
            }

            foreach ($stringDigits as $digit => $stringDigit) {
                $lastPosition = 0;

                while (($ocurrence = mb_strpos($line, $stringDigit, $lastPosition)) !== false) {
                    $digitsFound[] = [
                        'position' => $ocurrence,
                        'digit' => $digit,
                    ];

                    $lastPosition = $ocurrence + mb_strlen($stringDigit);
                }
            }

            // sort digits found by position
            usort($digitsFound, function (array $a, array $b) {
                return $a['position'] <=> $b['position'];
            });

            if (1 === count($digitsFound)) {
                $digitsToSum = $digitsFound[0]['digit'].$digitsFound[0]['digit'];
                $sum += (int) $digitsToSum;
                continue;
            }

            $firstDigit = $digitsFound[0]['digit'];
            $lastDigit = end($digitsFound)['digit'];
            $digitsToSum = $firstDigit.$lastDigit;
            $sum += (int) $digitsToSum;
        }

        return $sum;
    }
}
