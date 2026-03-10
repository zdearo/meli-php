<?php

namespace Zdearo\Meli\DTO;

readonly class Category
{
    public function __construct(
        public string $id,
        public ?string $name,
        public array $pathFromRoot = [],
        public array $childrenCategories = [],
        public array $settings = [],
        public array $extra = [],
    ) {}

    public static function fromArray(array $data): static
    {
        $known = ['id', 'name', 'path_from_root', 'children_categories', 'settings'];

        return new static(
            id: $data['id'],
            name: $data['name'] ?? null,
            pathFromRoot: $data['path_from_root'] ?? [],
            childrenCategories: $data['children_categories'] ?? [],
            settings: $data['settings'] ?? [],
            extra: array_diff_key($data, array_flip($known)),
        );
    }
}
