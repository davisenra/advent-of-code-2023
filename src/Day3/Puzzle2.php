<?php

declare(strict_types=1);

namespace AdventOfCode\Day3;

use AdventOfCode\PuzzleInterface;

class Puzzle2 implements PuzzleInterface
{
    public function solve(string $input): int
    {
        $lines = collect(explode("\n", $input));
        $asterisks = [];

        foreach ($lines as $lineNumber => $line) {
            $characters = mb_str_split($line);

            foreach ($characters as $charPosition => $character) {
                if ('*' === $character) {
                    $asterisks[] = [
                        'x' => $charPosition,
                        'line' => $lineNumber,
                    ];
                }
            }
        }

        $numbers = collect();
        $numericSequenceStart = null;

        foreach ($lines as $lineNumber => $line) {
            $characters = mb_str_split($line);

            foreach ($characters as $charPosition => $character) {
                if (is_numeric($character)) {
                    if (is_null($numericSequenceStart)) {
                        $numericSequenceStart = $charPosition;
                    }
                } elseif (!is_null($numericSequenceStart)) {
                    $numberValue = $this->characterSequenceSliceToInteger(
                        array_slice($characters, $numericSequenceStart, $charPosition - $numericSequenceStart)
                    );
                    $numberLength = mb_strlen((string) $numberValue);
                    $numbers->push([
                        'x' => $charPosition - $numberLength,
                        'line' => $lineNumber,
                        'value' => $numberValue,
                        'len' => $numberLength,
                    ]);
                    $numericSequenceStart = null;
                }
            }

            if (!is_null($numericSequenceStart)) {
                $numberValue = $this->characterSequenceSliceToInteger(
                    array_slice($characters, $numericSequenceStart, $charPosition)
                );
                $numberLength = mb_strlen((string) $numberValue);
                $numbers->push([
                    'x' => $charPosition - $numberLength + 1,
                    'line' => $lineNumber,
                    'value' => $numberValue,
                    'len' => $numberLength,
                ]);
                $numericSequenceStart = null;
            }
        }

        $gearRatioSum = 0;

        foreach ($asterisks as $asterisk) {
            $aboveLine = $numbers->filter(function (array $num) use ($asterisk) {
                return $num['line'] + 1 === $asterisk['line'] && ($num['x'] <= $asterisk['x'] + 1 && $num['x'] + $num['len'] >= $asterisk['x']);
            });

            $currentLine = $numbers->filter(function (array $num) use ($asterisk) {
                return $num['line'] === $asterisk['line'] && (
                    ($num['x'] + $num['len'] === $asterisk['x']) || ($asterisk['x'] + 1 === $num['x'])
                );
            });

            $underLine = $numbers->filter(function (array $num) use ($asterisk) {
                return $num['line'] - 1 === $asterisk['line'] && ($num['x'] <= $asterisk['x'] + 1 && $num['x'] + $num['len'] >= $asterisk['x']);
            });

            if (2 === $currentLine->count()) {
                $gearRatioSum += $currentLine
                    ->take(2)
                    ->reduce(fn (int $carry, array $adjacent) => $carry *= $adjacent['value'], 1);

                continue;
            }

            if ($aboveLine->isEmpty() && $underLine->isEmpty()) {
                continue;
            }

            $aboveAdjacentNumbers = $aboveLine->map(fn ($adjacent) => $adjacent['value'] ?? 1);
            $underAdjacentNumbers = $underLine->map(fn ($adjacent) => $adjacent['value'] ?? 1);
            $currentLineAdjacentNumbers = $currentLine->map(fn ($adjacent) => $adjacent['value'] ?? 1);

            $adjacents = collect([...$aboveAdjacentNumbers, ...$underAdjacentNumbers, ...$currentLineAdjacentNumbers])
                ->filter(fn ($number) => 1 !== $number);

            if (1 === $adjacents->count()) {
                continue;
            }

            $gearRatioSum += $adjacents
                ->take(2)
                ->reduce(fn (int $carry, int $adjacent) => $carry *= $adjacent, 1);
        }

        return $gearRatioSum;
    }

    private function characterSequenceSliceToInteger(array $sequence): int
    {
        return (int) implode('', $sequence);
    }
}
