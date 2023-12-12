<?php

declare(strict_types=1);

use AdventOfCode\Day3\Puzzle1;
use AdventOfCode\Day3\Puzzle2;

describe('it can solve the day 3 puzzles', function () {
    it('can solve the first part', function () {
        $input = require __DIR__.'/../Fixtures/Day3.php';
        $puzzle = new Puzzle1();
        $solution = $puzzle->solve($input);

        expect($solution)->toBe(539713);
    });

    it('can solve the second part', function () {
        $input = require __DIR__.'/../Fixtures/Day3.php';
        $puzzle = new Puzzle2();
        $solution = $puzzle->solve($input);

        expect($solution)->toBe(84159075);
    });

    it('can solve the second part with the public input', function () {
        $input = require __DIR__.'/../Fixtures/Day3PublicInput.php';
        $puzzle = new Puzzle2();
        $solution = $puzzle->solve($input);

        expect($solution)->toBe(467835);
    });
});
