<?php

declare(strict_types=1);

namespace AdventOfCode\Day3;

use AdventOfCode\PuzzleInterface;

class Puzzle1 implements PuzzleInterface
{
    private const SYMBOLS = ['*', '#', '$', '/', '&', '=', '%', '-', '@', '+'];

    public function solve(string $input): int
    {
        $lines = collect(explode("\n", $input));
        $symbolsMap = [];

        foreach ($lines as $lineNumber => $line) {
            $characters = mb_str_split($line);
            $symbolsMap[$lineNumber] = [];

            foreach ($characters as $charPosition => $character) {
                if (in_array($character, self::SYMBOLS)) {
                    $symbolsMap[$lineNumber][] = $charPosition;
                }
            }
        }

        $numbersMap = [];
        $numericSequenceStart = null;

        foreach ($lines as $lineNumber => $line) {
            $characters = mb_str_split($line);

            foreach ($characters as $charPosition => $character) {
                if (is_numeric($character)) {
                    if (is_null($numericSequenceStart)) {
                        $numericSequenceStart = $charPosition;
                    }
                } elseif (!is_null($numericSequenceStart)) {
                    $numbersMap[$lineNumber][] = [
                        'start' => $numericSequenceStart,
                        'end' => $charPosition - 1,
                        'value' => $this->characterSequenceSliceToInteger(
                            array_slice($characters, $numericSequenceStart, $charPosition - $numericSequenceStart)
                        ),
                    ];
                    $numericSequenceStart = null;
                }
            }

            if (!is_null($numericSequenceStart)) {
                $numbersMap[$lineNumber][] = [
                    'start' => $numericSequenceStart,
                    'end' => $charPosition - 1,
                    'value' => $this->characterSequenceSliceToInteger(
                        array_slice($characters, $numericSequenceStart, $charPosition)
                    ),
                ];
                $numericSequenceStart = null;
            }
        }

        $sum = 0;

        foreach ($numbersMap as $lineNumber => $numbers) {
            foreach ($numbers as $number) {
                if ($this->numberIsAdjacentToSymbol($lineNumber, $number, $symbolsMap)) {
                    $sum += $number['value'];
                }
            }
        }

        return $sum;
    }

    private function numberIsAdjacentToSymbol(int $lineNumber, array $number, array $symbolsMap): bool
    {
        $startPosition = $number['start'];
        $endPosition = $number['end'];

        $currentLine = $symbolsMap[$lineNumber];

        $hasSymbolOnLeftSideOrRightSide = !empty(array_intersect([$startPosition - 1, $endPosition + 1], $currentLine));

        if ($hasSymbolOnLeftSideOrRightSide) {
            return true;
        }

        $aboveLine = $symbolsMap[$lineNumber - 1] ?? null;
        $nextLine = $symbolsMap[$lineNumber + 1] ?? null;
        $intervalToCheckOnAboveAndNextLine = range($startPosition - 1, $endPosition + 1);

        if (!is_null($aboveLine)) {
            $isAdjacentToSymbolOnAboveLine = !empty(array_intersect($intervalToCheckOnAboveAndNextLine, $aboveLine));

            if ($isAdjacentToSymbolOnAboveLine) {
                return true;
            }
        }

        if (!is_null($nextLine)) {
            $isAdjacentToSymbolOnNextLine = !empty(array_intersect($intervalToCheckOnAboveAndNextLine, $nextLine));

            if ($isAdjacentToSymbolOnNextLine) {
                return true;
            }
        }

        return false;
    }

    private function characterSequenceSliceToInteger(array $sequence): int
    {
        return (int) implode('', $sequence);
    }
}
