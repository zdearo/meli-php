<?php

namespace Zdearo\Meli\Support;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Zdearo\Meli\DTO\SearchResult;

class LazyPaginator
{
    /**
     * @param  Closure(int, int): SearchResult  $fetcher
     */
    public function __construct(
        protected Closure $fetcher,
        protected int $limit = 50,
    ) {}

    /**
     * Returns a LazyCollection that fetches pages on demand.
     */
    public function cursor(): LazyCollection
    {
        return LazyCollection::make(function () {
            $offset = 0;

            do {
                $page = ($this->fetcher)($offset, $this->limit);

                foreach ($page->results as $item) {
                    yield $item;
                }

                $offset += $this->limit;
            } while ($offset < $page->total);
        });
    }

    /**
     * Collect all results into a Collection.
     */
    public function all(): Collection
    {
        return $this->cursor()->collect();
    }
}
