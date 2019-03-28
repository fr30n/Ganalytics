<?php

namespace Fr3on\GAnalytics;

use Illuminate\Support\ServiceProvider;
use Spatie\Analytics\Exceptions\InvalidConfiguration;

class GAnalyticsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/ganalytics.php' => config_path('ganalytics.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ganalytics.php', 'ganalytics');

        $this->app->bind(GAnalyticsClient::class, function () {
            $analyticsConfig = config('ganalytics');

            return GAnalyticsClientFactory::createForConfig($analyticsConfig);
        });

        $this->app->bind(Analytics::class, function () {
            $analyticsConfig = config('ganalytics');

            $this->guardAgainstInvalidConfiguration($analyticsConfig);

            $client = app(GAnalyticsClient::class);

            return new GAnalytics($client, $analyticsConfig['view_id']);
        });

        $this->app->alias(GAnalytics::class, 'ganalytics');
    }

    protected function guardAgainstInvalidConfiguration(array $analyticsConfig = null)
    {
        if (empty($analyticsConfig['view_id'])) {
            throw InvalidConfiguration::viewIdNotSpecified();
        }

        if (is_array($analyticsConfig['service_account_credentials_json'])) {
            return;
        }

        if (! file_exists($analyticsConfig['service_account_credentials_json'])) {
            throw InvalidConfiguration::credentialsJsonDoesNotExist($analyticsConfig['service_account_credentials_json']);
        }
    }
}
