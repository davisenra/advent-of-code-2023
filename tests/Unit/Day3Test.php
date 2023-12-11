<?php

declare(strict_types=1);

use AdventOfCode\Day3\Puzzle1;

describe('it can solve the day 2 puzzles', function () {
    it('can solve the first part', function () {
        $input = require __DIR__.'/../Fixtures/Day3/Puzzle1.php';
        $puzzle = new Puzzle1();
        $solution = $puzzle->solve($input);

        expect($solution)->toBe(1);
    });
});
