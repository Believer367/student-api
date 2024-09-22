<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use MongoDB\Database;
use MongoDB\Model\BSONArray;
use MongoDB\Model\BSONDocument;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::macro('getMongoTimestamp', function () {
            /** @var \Illuminate\Support\Carbon $this */
            return new UTCDateTime($this->getPreciseTimestamp(3));
        });
    }
}
