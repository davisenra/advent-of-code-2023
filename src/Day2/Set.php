<?php

declare(strict_types=1);

namespace AdventOfCode\Day2;

readonly class Set
{
    public function __construct(
        public int $redCubes,
        public int $blueCubes,
        public int $greenCubes,
    ) {
    }
}
