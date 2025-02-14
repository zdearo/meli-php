<?php

namespace Zdearo\Meli\Enums;

enum MarketplaceEnum: string
{
    case VENEZUELA = 'MLV';
    case DOMINICANA = 'MRD';
    case URUGUAY = 'MLU';
    case MEXICO = 'MLM';
    case COSTA_RICA = 'MCR';
    case HONDURAS = 'MHN';
    case BRASIL = 'MLB';
    case COLOMBIA = 'MCO';
    case CHILE = 'MLC';
    case PARAGUAY = 'MPY';
    case ECUADOR = 'MEC';
    case BOLIVIA = 'MBO';
    case NICARAGUA = 'MNI';
    case PANAMA = 'MPA';
    case EL_SALVADOR = 'MSV';
    case ARGENTINA = 'MLA';
    case PERU = 'MPE';
    case CUBA = 'MCU';
    case GUATEMALA = 'MGT';

    public function domain(): string
    {
        return match ($this) {
            self::VENEZUELA => 'mercadolibre.com.ve',
            self::DOMINICANA => 'mercadolibre.com.do',
            self::URUGUAY => 'mercadolibre.com.uy',
            self::MEXICO => 'mercadolibre.com.mx',
            self::COSTA_RICA => 'mercadolibre.co.cr',
            self::HONDURAS => 'mercadolibre.com.hn',
            self::BRASIL => 'mercadolivre.com.br',
            self::COLOMBIA => 'mercadolibre.com.co',
            self::CHILE => 'mercadolibre.cl',
            self::PARAGUAY => 'mercadolibre.com.py',
            self::ECUADOR => 'mercadolibre.com.ec',
            self::BOLIVIA => 'mercadolibre.com.bo',
            self::NICARAGUA => 'mercadolibre.com.ni',
            self::PANAMA => 'mercadolibre.com.pa',
            self::EL_SALVADOR => 'mercadolibre.com.sv',
            self::ARGENTINA => 'mercadolibre.com.ar',
            self::PERU => 'mercadolibre.com.pe',
            self::CUBA => 'mercadolibre.com.cu',
            self::GUATEMALA => 'mercadolibre.com.gt',
        };
    }
}
