<?php

declare(strict_types=1);

use AdventOfCode\Day4\Puzzle1;

describe('it can solve the day 4 puzzles', function () {
    it('can solve the first part with the public input', function () {
        $input = require __DIR__.'/../Fixtures/Day4PublicInput.php';
        $puzzle = new Puzzle1();
        $solution = $puzzle->solve($input);

        expect($solution)->toBe(13);
    });

    it('can solve the first part', function () {
        $input = require __DIR__.'/../Fixtures/Day4.php';
        $puzzle = new Puzzle1();
        $solution = $puzzle->solve($input);

        expect($solution)->toBe(24848);
    });
});
