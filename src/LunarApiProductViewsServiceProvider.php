<?php

namespace Dystcz\LunarApiProductViews;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\SchemaManifest;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaExtension;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema;
use Dystcz\LunarApiProductViews\Domain\Products\JsonApi\Sorts\RecentlyViewedSort;
use Illuminate\Support\ServiceProvider;

class LunarApiProductViewsServiceProvider extends ServiceProvider
{
    /** Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('lunar-api-product-views.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/lunar-api-product-views.php', 'lunar-api-product-views');

        // Register the main class to use with the facade
        $this->app->singleton('lunar-api-product-views', function () {
            return new LunarApiProductViews;
        });

        $this->extendSchemas();
    }

    protected function extendSchemas(): void
    {
        $schemaManifest = $this->app->make(SchemaManifest::class);

        /** @var SchemaExtension $productSchemaExtenstion */
        $productSchemaExtenstion = $schemaManifest::for(ProductSchema::class);

        $productSchemaExtenstion->setSortables([
            RecentlyViewedSort::make('recently_viewed'),
        ]);
    }
}
