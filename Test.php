<?php

require 'vendor/autoload.php';

use Zdearo\Meli\Meli;
use Zdearo\Meli\Enums\MarketplaceEnum;
use Zdearo\Meli\Http\MeliClient;

$app_id = '4698841684096527';
$secret_code = 'TveiQSy7xZbDgorUBDGhuSU1U9Vc5aUn';
$uri = 'https://integra-ecommerce.test/auth/mercadolivre';
$code = 'TG-67af8d0d5d54ea0001cacc24-2070744830';
$access_token = 'APP_USR-4698841684096527-021414-ebb8f64b4d8e1838137f31bfd8449506-2070744830';
$refresh_token = 'TG-67af8d209a38c0000110da15-2070744830';

$meli = new Meli('BRASIL', $access_token);
$searchService = $meli->searchItems();
$productService = $meli->products();
$visitsService = $meli->visits();

define('DATA_DIR', __DIR__ . '/data');
if (!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0777, true);
}

function saveToFile($filename, $data) {
    file_put_contents(DATA_DIR . '/' . $filename, json_encode($data, JSON_PRETTY_PRINT));
}

if (!isset($code)) {
    $authUrl = $meli->auth()->getAuthUrl($uri, $app_id);
    saveToFile('auth_url.json', ['auth_url' => $authUrl]);
} elseif (!isset($access_token)) {
    $tokenData = $meli->auth()->getToken($app_id, $secret_code, $code, $uri);
    saveToFile('token.json', $tokenData);
} else {
    // $queries = ['grings', 'alimentos naturais', 'orgânicos'];
    // foreach ($queries as $query) {
    //     $result = $searchService->byQuery($query);
    //     saveToFile("search_{$query}.json", $result);
    // }

    // $categories = ['MLB1648', 'MLB271599'];
    // foreach ($categories as $category) {
    //     $result = $searchService->byCategory($category);
    //     saveToFile("category_{$category}.json", $result);
    // }

    // $nicknames = ['GRINGSSAUDAVEIS'];
    // foreach ($nicknames as $nickname) {
    //     $result = $searchService->byNickname($nickname);
    //     saveToFile("nickname_{$nickname}.json", $result);
    // }

    // $sellerIds = [2070744830];
    // foreach ($sellerIds as $sellerId) {
    //     $result = $searchService->bySeller($sellerId);
    //     saveToFile("seller_{$sellerId}.json", $result);
    // }

    $productId = 'MLB3901420381';
    $productDetails = $productService->get($productId);
    saveToFile("product_{$productId}.json", $productDetails);

    $productVisits = $visitsService->totalByItem($productId);
    saveToFile("product_visits_{$productId}.json", $productVisits);

    $productsVisits = $visitsService->visitsByItemTimeWindow($productId, 7, 'day');
    saveToFile("products_visits.json", $productsVisits);
}
