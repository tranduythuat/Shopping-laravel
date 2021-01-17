<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Colors\ColorsRepositoryInterface;
use App\Repositories\Colors\ColorsRepository;
use App\Repositories\Sizes\SizesRepositoryInterface;
use App\Repositories\Sizes\SizesRepository;
use App\Repositories\Products\ProductsRepositoryInterface;
use App\Repositories\Products\ProductsRepository;
use App\Repositories\Categories\CategoriesRepositoryInterface;
use App\Repositories\Categories\CategoriesRepository;
use App\Repositories\Tags\TagsRepositoryInterface;
use App\Repositories\Tags\TagsRepository;
use App\Repositories\Suppliers\SuppliersRepositoryInterface;
use App\Repositories\Suppliers\SuppliersRepository;
use App\Repositories\ProductColors\ProductColorsRepositoryInterface;
use App\Repositories\ProductColors\ProductColorsRepository;
use App\Repositories\Sliders\SlidersRepositoryInterface;
use App\Repositories\Sliders\SlidersRepository;
use App\Repositories\Transactions\TransactionsRepositoryInterface;
use App\Repositories\Transactions\TransactionsRepository;
use App\Repositories\Orders\OrdersRepositoryInterface;
use App\Repositories\Orders\OrdersRepository;
use App\Repositories\ColorSizes\ColorSizesRepositoryInterface;
use App\Repositories\ColorSizes\ColorSizesRepository;

use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ColorsRepositoryInterface::class,ColorsRepository::class);
        $this->app->bind(SizesRepositoryInterface::class,SizesRepository::class);
        $this->app->bind(ProductsRepositoryInterface::class,ProductsRepository::class);
        $this->app->bind(CategoriesRepositoryInterface::class,CategoriesRepository::class);
        $this->app->bind(TagsRepositoryInterface::class,TagsRepository::class);
        $this->app->bind(SuppliersRepositoryInterface::class,SuppliersRepository::class);
        $this->app->bind(ProductColorsRepositoryInterface::class,ProductColorsRepository::class);
        $this->app->bind(SlidersRepositoryInterface::class,SlidersRepository::class);
        $this->app->bind(TransactionsRepositoryInterface::class,TransactionsRepository::class);
        $this->app->bind(OrdersRepositoryInterface::class,OrdersRepository::class);
        $this->app->bind(ColorSizesRepositoryInterface::class,ColorSizesRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
