<?php

declare(strict_types=1);

namespace AdventOfCode\Day2;

readonly class Game
{
    public function __construct(
        public int $id,
        /** @var Set[] $sets */
        public array $sets,
    ) {
    }
}
