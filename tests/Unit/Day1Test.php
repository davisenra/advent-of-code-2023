<?php

declare(strict_types=1);

use AdventOfCode\Day1\Puzzle1;
use AdventOfCode\Day1\Puzzle2;

describe('it can solve the day 1 puzzles', function () {
    it('can solve the first part', function () {
        $input = require __DIR__.'/../Fixtures/Day1.php';
        $puzzle = new Puzzle1();
        $solution = $puzzle->solve($input);

        expect($solution)->toBe(55130);
    });

    it('can solve the second part', function () {
        $input = require __DIR__.'/../Fixtures/Day1.php';
        $puzzle = new Puzzle2();
        $solution = $puzzle->solve($input);

        expect($solution)->toBe(54985);
    });
});
