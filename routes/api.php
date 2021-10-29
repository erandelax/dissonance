<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Http\Actions\Api;
use App\Factories\ScopedRouteFactory;

$namePrefixRoot = ScopedRouteFactory::SCOPE_ROOT;
$namePrefixSubdirectory = ScopedRouteFactory::SCOPE_SUBDIRECTORY;
$namePrefixSubdomain = ScopedRouteFactory::SCOPE_SUBDOMAIN;

// Project-scope routes ------------------------------------------------------
$scopedRoutesFactory = static function (string $namePrefix): callable {
    return static function (Router $router) use ($namePrefix): void {
        // svg placeholder
        $router->post('/tools/markdown-preview', Api\PreviewMarkdown::class)->name("$namePrefix:tools.markdown-preview");
        // edit here end
    };
};
// ---------------------------------------------------------------------------

// Project-scope routes registration for project directory notation
Route::group(['prefix' => '/p/{project}'], $scopedRoutesFactory($namePrefixSubdirectory));

// Root routes ===============================================================
Route::domain(
    parse_url(config('app.url'), PHP_URL_HOST)
)->group(static function (Router $router) use ($namePrefixRoot): void {
    // edit here start
    $router->post('/tools/markdown-preview', Api\PreviewMarkdown::class)->name("$namePrefixRoot:tools.markdown-preview");
    // edit here end
});
// ===========================================================================

// Project-scope routes registration for subdomains
Route::group(['domain' => '{project}', 'name' => 'domain', 'where' => [
    'project' => '.*',
]], $scopedRoutesFactory($namePrefixSubdomain));
