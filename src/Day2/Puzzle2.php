<?php

declare(strict_types=1);

namespace AdventOfCode\Day2;

use AdventOfCode\PuzzleInterface;

class Puzzle2 implements PuzzleInterface
{
    public function solve(string $input)
    {
        $records = explode("\n", $input);

        /** @var Game[] $games */
        $games = array_map(function (string $record): Game {
            [$gameInfo, $setsData] = explode(':', $record);
            $setsData = explode(';', $setsData);
            $gameId = (int) explode(' ', $gameInfo)[1];

            $sets = array_map(function (string $set): Set {
                $cubes = explode(',', $set);
                $redCubes = array_filter($cubes, fn ($cube) => str_contains($cube, 'red'));
                $blueCubes = array_filter($cubes, fn ($cube) => str_contains($cube, 'blue'));
                $greenCubes = array_filter($cubes, fn ($cube) => str_contains($cube, 'green'));

                return new Set(
                    $this->getCubesCount($redCubes),
                    $this->getCubesCount($blueCubes),
                    $this->getCubesCount($greenCubes),
                );
            }, $setsData);

            return new Game(
                $gameId,
                $sets,
            );
        }, $records);

        $minimumCubesPerGame = array_map(function (Game $game) {
            $minimumRedCubes = null;
            $minimumBlueCubes = null;
            $minimumGreenCubes = null;

            foreach ($game->sets as $set) {
                if (is_null($minimumRedCubes) && is_null($minimumBlueCubes) && is_null($minimumGreenCubes)) {
                    $minimumRedCubes = $set->redCubes;
                    $minimumBlueCubes = $set->blueCubes;
                    $minimumGreenCubes = $set->greenCubes;
                    continue;
                }

                if ($set->redCubes > $minimumRedCubes) {
                    $minimumRedCubes = $set->redCubes;
                }

                if ($set->blueCubes > $minimumBlueCubes) {
                    $minimumBlueCubes = $set->blueCubes;
                }

                if ($set->greenCubes > $minimumGreenCubes) {
                    $minimumGreenCubes = $set->greenCubes;
                }
            }

            return [
                'red' => $minimumRedCubes,
                'blue' => $minimumBlueCubes,
                'green' => $minimumGreenCubes,
            ];
        }, $games);

        $powerOfMinimumCubes = array_reduce(
            $minimumCubesPerGame,
            fn (int $carry, array $cubes) => $carry += $cubes['red'] * $cubes['blue'] * $cubes['green'],
            0
        );

        return $powerOfMinimumCubes;
    }

    private function getCubesCount(array $cubesData): int
    {
        if (empty($cubesData)) {
            return 0;
        }

        $cubesPlayed = trim(reset($cubesData));
        [$count, $color] = explode(' ', $cubesPlayed);

        return (int) $count;
    }
}
