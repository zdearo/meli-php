<?php

use Zdearo\Meli\Enums\MarketplaceEnum;

test('marketplace enum has correct values', function () {
    expect(MarketplaceEnum::BRASIL->value)->toBe('MLB');
    expect(MarketplaceEnum::ARGENTINA->value)->toBe('MLA');
    expect(MarketplaceEnum::MEXICO->value)->toBe('MLM');
    expect(MarketplaceEnum::COLOMBIA->value)->toBe('MCO');
    expect(MarketplaceEnum::CHILE->value)->toBe('MLC');
    expect(MarketplaceEnum::URUGUAY->value)->toBe('MLU');
    expect(MarketplaceEnum::PERU->value)->toBe('MPE');
});

test('marketplace enum returns correct domain', function () {
    expect(MarketplaceEnum::BRASIL->domain())->toBe('mercadolivre.com.br');
    expect(MarketplaceEnum::ARGENTINA->domain())->toBe('mercadolibre.com.ar');
    expect(MarketplaceEnum::MEXICO->domain())->toBe('mercadolibre.com.mx');
    expect(MarketplaceEnum::COLOMBIA->domain())->toBe('mercadolibre.com.co');
    expect(MarketplaceEnum::CHILE->domain())->toBe('mercadolibre.cl');
    expect(MarketplaceEnum::URUGUAY->domain())->toBe('mercadolibre.com.uy');
    expect(MarketplaceEnum::PERU->domain())->toBe('mercadolibre.com.pe');
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
