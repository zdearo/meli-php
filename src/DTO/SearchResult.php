<?php

namespace Zdearo\Meli\DTO;

readonly class SearchResult
{
    public function __construct(
        public array $results,
        public int $total,
        public int $offset,
        public int $limit,
    ) {}
}
