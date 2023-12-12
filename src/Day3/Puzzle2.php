<?php

declare(strict_types=1);

namespace AdventOfCode\Day3;

use AdventOfCode\PuzzleInterface;

class Puzzle2 implements PuzzleInterface
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

        $adjacencyMap = [];

        foreach ($numbersMap as $lineNumber => $numbers) {
            $adjacencyMap[$lineNumber] = [];

            foreach ($numbers as $number) {
                $adjacencyPosition = $this->getAdjacencyPosition($lineNumber, $number, $symbolsMap);

                if (!is_null($adjacencyPosition)) {
                    $adjacencyMap[$lineNumber][$number['value']] = $adjacencyPosition;
                }
            }
        }

        $gearRatioSum = 0;
        $lineNumber = 0;

        do {
            $previousLine = $adjacencyMap[$lineNumber - 1] ?? null;
            $currentLine = $adjacencyMap[$lineNumber];
            $nextLine = $adjacencyMap[$lineNumber + 1];

            if (is_null($previousLine)) {
                ++$lineNumber;
                continue;
            }

            $lines = $previousLine + $currentLine + $nextLine;

            foreach ($lines as $number => $position) {
                if (in_array($position, $lines)) {
                    // WIP
                }
            }

            ++$lineNumber;
        } while ($lineNumber < count($adjacencyMap) - 1);

        return $gearRatioSum;
    }

    private function getAdjacencyPosition(int $lineNumber, array $number, array $symbolsMap): ?int
    {
        $startPosition = $number['start'];
        $endPosition = $number['end'];

        $currentLine = $symbolsMap[$lineNumber];

        $leftSideOrRightSideAdjancencies = array_intersect([$startPosition - 1, $endPosition + 1], $currentLine);

        if (!empty($leftSideOrRightSideAdjancencies)) {
            return reset($leftSideOrRightSideAdjancencies);
        }

        $aboveLine = $symbolsMap[$lineNumber - 1] ?? null;
        $nextLine = $symbolsMap[$lineNumber + 1] ?? null;
        $intervalToCheckOnAboveAndNextLine = range($startPosition - 1, $endPosition + 1);

        if (!is_null($aboveLine)) {
            $aboveLineAdjancencies = array_intersect($intervalToCheckOnAboveAndNextLine, $aboveLine);

            if (!empty($aboveLineAdjancencies)) {
                return reset($aboveLineAdjancencies);
            }
        }

        if (!is_null($nextLine)) {
            $nextLineAdjancencies = array_intersect($intervalToCheckOnAboveAndNextLine, $nextLine);

            if (!empty($nextLineAdjancencies)) {
                return reset($nextLineAdjancencies);
            }
        }

        return null;
    }

    private function characterSequenceSliceToInteger(array $sequence): int
    {
        return (int) implode('', $sequence);
    }
}
