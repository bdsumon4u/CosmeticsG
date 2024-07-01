<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
      Schema::defaultStringLength(191);
      Paginator::useBootstrap();

      $this->app->bind("pathao", function () {
        return new \App\Pathao\Manage\Manage(
          new \App\Pathao\Apis\AreaApi(),
          new \App\Pathao\Apis\StoreApi(),
          new \App\Pathao\Apis\OrderApi()
        );
      });
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
