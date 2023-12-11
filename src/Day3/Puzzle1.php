<?php

declare(strict_types=1);

namespace AdventOfCode\Day3;

use AdventOfCode\PuzzleInterface;

class Puzzle1 implements PuzzleInterface
{
    private const SYMBOLS = ['*', '#', '$', '/', '&', '=', '%', '-', '@'];

    public function solve(string $input): int
    {
        $lines = collect(explode("\n", $input));
        $symbolsMap = [];

        foreach ($lines as $lineNumber => $line) {
            $characters = mb_str_split($line);
            $symbolsMap[$i] = [];

            foreach ($characters as $charPosition => $character) {
                if (in_array($character, self::SYMBOLS)) {
                    $symbolsMap[$lineNumber][] = $charPosition;
                }
            }
        }

        $digitsMap = [];
        $numericSequenceStart = null;

        foreach ($lines as $lineNumber => $line) {
            $characters = mb_str_split($line);

            foreach ($characters as $charPosition => $character) {
                if (is_numeric($character)) {
                    if (is_null($numericSequenceStart)) {
                        $numericSequenceStart = $charPosition;
                    }
                } elseif (!is_null($numericSequenceStart)) {
                    $digitsMap[$lineNumber][] = [
                        'start' => $numericSequenceStart,
                        'end' => $charPosition - 1,
                        'value' => $this->characterSequenceSliceToInteger(
                            array_slice($characters, $numericSequenceStart, $charPosition - $numericSequenceStart)
                        ),
                    ];
                    $numericSequenceStart = null;
                }
            }
        }

        dd($digitsMap);

        $sum = 0;

        return $sum;
    }

    private function characterSequenceSliceToInteger(array $sequence): int
    {
        return (int) implode('', $sequence);
    }
}
