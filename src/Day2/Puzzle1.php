<?php

declare(strict_types=1);

namespace AdventOfCode\Day2;

use AdventOfCode\PuzzleInterface;

class Puzzle1 implements PuzzleInterface
{
    private const MAX_RED_CUBES = 12;
    private const MAX_GREEN_CUBES = 13;
    private const MAX_BLUE_CUBES = 14;

    public function solve(string $input): int
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

        $validGames = array_filter($games, $this->isGameValid());
        $sumOfValidGamesIds = array_reduce(
            $validGames,
            fn (int $collector, Game $game) => $collector += $game->id,
            0
        );

        return $sumOfValidGamesIds;
    }

    private function isGameValid(): \Closure
    {
        return function (Game $game): bool {
            $validSets = array_filter($game->sets, function (Set $set) {
                return $set->redCubes <= self::MAX_RED_CUBES
                    && $set->blueCubes <= self::MAX_BLUE_CUBES
                    && $set->greenCubes <= self::MAX_GREEN_CUBES;
            });

            return count($validSets) === count($game->sets);
        };
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
