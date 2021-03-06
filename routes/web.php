<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Http\Actions\Web;
use App\Factories\ScopedRouteFactory;

$namePrefixRoot = ScopedRouteFactory::SCOPE_ROOT;
$namePrefixSubdirectory = ScopedRouteFactory::SCOPE_SUBDIRECTORY;
$namePrefixSubdomain = ScopedRouteFactory::SCOPE_SUBDOMAIN;

// Project-scope routes ------------------------------------------------------
$scopedRoutesFactory = static function (string $namePrefix): callable {
    return static function (Router $router) use($namePrefix): void {
        // svg placeholder
        $router->get('/placeholder.svg', Web\Uploads\ReadSVGPlaceholder::class)->name("$namePrefix:svg");
        // edit here start
        $router->get('/', Web\Projects\ReadProject::class)->name("$namePrefix:projects.read");
        // authorization
        $router->group(['prefix' => '/a'], static function (Router $router) use($namePrefix): void {
            $router->get('/logout', Web\Auths\Logout::class)->name("$namePrefix:auths.logout");
        });
        // localized routes
        $router->group(['prefix' => '/{locale}'], static function (Router $router) use($namePrefix): void {
            // search
            $router->get('/search', Web\Search\ReadSearch::class)->name("$namePrefix:search.read");
            // users (accounts)
            $router->group(['prefix' => '/u'], static function (Router $router) use($namePrefix): void {
                $router->get('/', Web\Users\BrowseUsers::class)->name("$namePrefix:users.browse");
                $router->get('/{user}', Web\Users\ReadOrEditUser::class)->name("$namePrefix:users.read");
                $router->post('/{user}', Web\Users\ReadOrEditUser::class)->name("$namePrefix:users.edit");
            });
            // characters, groups and organizations (entities)
            $router->group(['prefix' => '/e'], static function (Router $router) use($namePrefix): void {
                $router->get('/', Web\Entities\BrowseEntities::class)->name("$namePrefix:entities.browse");
                $router->get('/{id}', Web\Entities\ReadEntity::class)->name("$namePrefix:entities.read");
            });
            // admin
            $router->group(['prefix' => '/x'], static function (Router $router) use($namePrefix): void {
                $router->get('/', Web\Admins\BrowseAdmins::class)->name("$namePrefix:admins.browse");
                $router->get('/{page}/{id?}', Web\Admins\ReadAdmin::class)->name("$namePrefix:admins.read");
                $router->post('/{page}/{id?}', Web\Admins\ReadAdmin::class)->name("$namePrefix:admins.edit");
            });
            // uploads/storage
            $router->group(['prefix' => '/s'], static function (Router $router) use($namePrefix): void {
                $router->get('/', Web\Uploads\BrowseUploads::class)->name("$namePrefix:uploads.browse");
                $router->post('/', Web\Uploads\PushUpload::class)->name("$namePrefix:uploads.push");
            });
            // pages
            $router->get('/{page?}', Web\Pages\ReadAndRestorePages::class)->name("$namePrefix:pages.read");
            $router->post('/{page?}', Web\Pages\EditPage::class)->name("$namePrefix:pages.edit");
        });
        // edit here end
    };
};
// ---------------------------------------------------------------------------

// Project-scope routes registration for project directory notation
Route::group(['prefix' => '/p/{project}'], $scopedRoutesFactory($namePrefixSubdirectory));

// Root routes ===============================================================
Route::domain(
    parse_url(config('app.url'), PHP_URL_HOST)
)->group(static function (Router $router) use($namePrefixRoot): void {
    // edit here start
    $router->get('/', Web\Projects\BrowseProjects::class)->name("$namePrefixRoot:projects.browse");
    // svg placeholder
    $router->get('/placeholder.svg', Web\Uploads\ReadSVGPlaceholder::class)->name("$namePrefixRoot:svg");
    // authorization
    $router->group(['prefix' => '/a'], static function (Router $router) use($namePrefixRoot): void {
        $router->get('/', Web\Auths\BrowseAuths::class)->name("$namePrefixRoot:auths.browse");
        $router->get('/discord', Web\Auths\DiscordAuth::class)->name("$namePrefixRoot:auths.discord");
        $router->get('/logout', Web\Auths\Logout::class)->name("$namePrefixRoot:auths.logout");
    });
    // localized routes
    $router->group(['prefix' => '/{locale}'], static function (Router $router) use($namePrefixRoot): void {
        // search
        $router->get('/search', Web\Search\ReadSearch::class)->name("$namePrefixRoot:search.read");
        // users (accounts)
        $router->group(['prefix' => '/u'], static function (Router $router) use($namePrefixRoot): void {
            $router->get('/', Web\Users\BrowseUsers::class)->name("$namePrefixRoot:users.browse");
            $router->get('/{user}', Web\Users\ReadOrEditUser::class)->name("$namePrefixRoot:users.read");
            $router->post('/{user}', Web\Users\ReadOrEditUser::class)->name("$namePrefixRoot:users.edit");
        });
        // characters, groups and organizations (entities)
        $router->group(['prefix' => '/e'], static function (Router $router) use($namePrefixRoot): void {
            $router->get('/', Web\Entities\BrowseEntities::class)->name("$namePrefixRoot:entities.browse");
            $router->get('/{id}', Web\Entities\ReadEntity::class)->name("$namePrefixRoot:entities.read");
        });
        // admin
        $router->group(['prefix' => '/x'], static function (Router $router) use($namePrefixRoot): void {
            $router->get('/', Web\Admins\BrowseAdmins::class)->name("$namePrefixRoot:admins.browse");
            $router->get('/{page}/{id?}', Web\Admins\ReadAdmin::class)->name("$namePrefixRoot:admins.read");
            $router->post('/{page}/{id?}', Web\Admins\ReadAdmin::class)->name("$namePrefixRoot:admins.edit");
        });
        // uploads/storage
        $router->group(['prefix' => '/s'], static function (Router $router) use($namePrefixRoot): void {
            $router->get('/', Web\Uploads\BrowseUploads::class)->name("$namePrefixRoot:uploads.browse");
            $router->post('/', Web\Uploads\PushUpload::class)->name("$namePrefixRoot:uploads.push");
        });
        // pages
        $router->get('/{page?}', Web\Pages\ReadAndRestorePages::class)->name("$namePrefixRoot:pages.read");
        $router->post('/{page?}', Web\Pages\EditPage::class)->name("$namePrefixRoot:pages.edit");
    });
    // edit here end
});
// ===========================================================================

// Project-scope routes registration for subdomains
Route::group(['domain' => '{project}', 'name' => 'domain', 'where' => [
    'project' => '.*',
]], $scopedRoutesFactory($namePrefixSubdomain));

// Fallback 404 / redirection fixer
Route::fallback(fn() => '404')->name("$namePrefixRoot:fallback");
