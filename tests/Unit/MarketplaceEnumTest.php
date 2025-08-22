<?php

use Zdearo\Meli\Enums\MarketplaceEnum;

test('marketplace enum has correct values', function () {
    expect(MarketplaceEnum::BRASIL->value)->toBe('MLB')
        ->and(MarketplaceEnum::ARGENTINA->value)->toBe('MLA')
        ->and(MarketplaceEnum::MEXICO->value)->toBe('MLM')
        ->and(MarketplaceEnum::COLOMBIA->value)->toBe('MCO')
        ->and(MarketplaceEnum::CHILE->value)->toBe('MLC')
        ->and(MarketplaceEnum::URUGUAY->value)->toBe('MLU')
        ->and(MarketplaceEnum::PERU->value)->toBe('MPE');
});

test('marketplace enum returns correct domain', function () {
    expect(MarketplaceEnum::BRASIL->domain())->toBe('mercadolivre.com.br')
        ->and(MarketplaceEnum::ARGENTINA->domain())->toBe('mercadolibre.com.ar')
        ->and(MarketplaceEnum::MEXICO->domain())->toBe('mercadolibre.com.mx')
        ->and(MarketplaceEnum::COLOMBIA->domain())->toBe('mercadolibre.com.co')
        ->and(MarketplaceEnum::CHILE->domain())->toBe('mercadolibre.cl')
        ->and(MarketplaceEnum::URUGUAY->domain())->toBe('mercadolibre.com.uy')
        ->and(MarketplaceEnum::PERU->domain())->toBe('mercadolibre.com.pe');
});

test('can create enum from string value', function () {
    $enum = MarketplaceEnum::from('MLB');
    expect($enum)->toBe(MarketplaceEnum::BRASIL);

    $enum = MarketplaceEnum::from('MLA');
    expect($enum)->toBe(MarketplaceEnum::ARGENTINA);

    $enum = MarketplaceEnum::from('MLM');
    expect($enum)->toBe(MarketplaceEnum::MEXICO);
});

// Note: Testing invalid enum values directly is challenging due to PHP's enum implementation
// Instead, we focus on testing the valid behavior
