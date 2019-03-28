#  Retrieve data from Google Analytics

[![Latest Version](https://img.shields.io/github/release/fr30n/ganalytics.svg?style=flat-square)](https://github.com/fr30n/ganalytics/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/fr30n/ganalytics/master.svg?style=flat-square)](https://travis-ci.org/fr30n/ganalytics)
[![Quality Score](https://img.shields.io/scrutinizer/g/fr30n/ganalytics.svg?style=flat-square)](https://scrutinizer-ci.com/g/fr30n/ganalytics-analytics)
[![StyleCI](https://styleci.io/repos/178206408/shield)](https://styleci.io/repos/178206408)
[![Total Downloads](https://img.shields.io/packagist/dt/fr30n/ganalytics.svg?style=flat-square)](https://packagist.org/packages/fr30n/ganalytics)

Using this package you can easily retrieve data from Google Analytics.

Here are a few examples of the provided methods:

```php
use GAnalytics;
use Fr3on\GAnalytics\Period;

//fetch the most visited pages for today and the past week
GAnalytics::fetchMostVisitedPages(Period::days(7));

//fetch visitors and page views for the past week
GAnalytics::fetchVisitorsAndPageViews(Period::days(7));
```

Most methods will return an `\Illuminate\Support\Collection` object containing the results.

Fr3on is a software agency in Cairo, Egypt. You'll find an overview of all our open source projects [on our website](https://fr30n.com).

## Installation

This package can be installed through Composer.

``` bash
composer require fr30n/ganalytics
```

Optionally, you can publish the config file of this package with this command:

``` bash
php artisan vendor:publish --provider="Fr30n\GAnalytics\GAnalyticsServiceProvider"
```

The following config file will be published in `config/ganalytics.php`

```php
return [

    /*
     * The view id of which you want to display data.
     */
    'view_id' => env('ANALYTICS_VIEW_ID'),

    /*
     * Path to the client secret json file. Take a look at the README of this package
     * to learn how to get this file. You can also pass the credentials as an array 
     * instead of a file path.
     */
    'service_account_credentials_json' => storage_path('app/ganalytics/service-account-credentials.json'),

    /*
     * The amount of minutes the Google API responses will be cached.
     * If you set this to zero, the responses won't be cached at all.
     */
    'cache_lifetime_in_minutes' => 60 * 24,

    /*
     * Here you may configure the "store" that the underlying Google_Client will
     * use to store it's data.  You may also add extra parameters that will
     * be passed on setCacheConfig (see docs for google-api-php-client).
     *
     * Optional parameters: "lifetime", "prefix"
     */
    'cache' => [
        'store' => 'file',
    ],
];
```

## Usage

When the installation is done you can easily retrieve Analytics data. Nearly all methods will return an `Illuminate\Support\Collection`-instance.


Here are a few examples using periods 
```php
//retrieve visitors and pageview data for the current day and the last seven days
$analyticsData = GAnalytics::fetchVisitorsAndPageViews(Period::days(7));

//retrieve visitors and pageviews since the 6 months ago
$analyticsData = GAnalytics::fetchVisitorsAndPageViews(Period::months(6));

//retrieve sessions and pageviews with yearMonth dimension since 1 year ago 
$analyticsData = GAnalytics::performQuery(
    Period::years(1),
    'ga:sessions',
    [
        'metrics' => 'ga:sessions, ga:pageviews',
        'dimensions' => 'ga:yearMonth'
    ]
);
```

`$analyticsData` is a `Collection` in which each item is an array that holds keys `date`, `visitors` and `pageViews`

If you want to have more control over the period you want to fetch data for, you can pass a `startDate` and an `endDate` to the period object.

```php
$startDate = Carbon::now()->subYear();
$endDate = Carbon::now();

Period::create($startDate, $endDate);
```

## Provided methods

### Visitors and pageviews

```php
public function fetchVisitorsAndPageViews(Period $period): Collection
```

The function returns a `Collection` in which each item is an array that holds keys `date`, `visitors`, `pageTitle` and `pageViews`.

### Total visitors and pageviews

```php
public function fetchTotalVisitorsAndPageViews(Period $period): Collection
```

The function returns a `Collection` in which each item is an array that holds keys `date`, `visitors`, and `pageViews`.

### Most visited pages

```php
public function fetchMostVisitedPages(Period $period, int $maxResults = 20): Collection
```

The function returns a `Collection` in which each item is an array that holds keys `url`, `pageTitle` and `pageViews`.

### Top referrers

```php
public function fetchTopReferrers(Period $period, int $maxResults = 20): Collection
```

The function returns a `Collection` in which each item is an array that holds keys `url` and `pageViews`.

### User Types

```php
public function fetchUserTypes(Period $period): Collection
```

The function returns a `Collection` in which each item is an array that holds keys `type` and `sessions`.

### Top browsers

```php
public function fetchTopBrowsers(Period $period, int $maxResults = 10): Collection
```

The function returns a `Collection` in which each item is an array that holds keys `browser` and `sessions`.

### All other Google Analytics queries

To perform all other queries on the Google Analytics resource use `performQuery`.  [Google's Core Reporting API](https://developers.google.com/analytics/devguides/reporting/core/v3/common-queries) provides more information on which metrics and dimensions might be used.

```php
public function performQuery(Period $period, string $metrics, array $others = [])
```

You can get access to the underlying `Google_Service_Analytics` object:

```php
GAnalytics::getAnalyticsService();
```

## Testing

Run the tests with:

``` bash
vendor/bin/phpunit
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email hello@fr30n.com instead of using the issue tracker.

## Credits

- [Ahmed Mordy](https://github.com/fr30n)
- [All Contributors](../../contributors)

## Support us

Fr3on is a software agency based in Cairo, Egypt. You'll find an overview of all our open source projects [on our website](https://fr30n.com).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/fr30n).
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
