<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
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
        Validator::extend('ean13', function ($attribute, $value, $parameters, $validator) {
            if (!preg_match('/^\d{13}$/', $value)) {
                return false;
            }
    
            $sum = 0;
            for ($i = 0; $i < 12; $i++) {
                $digit = (int) $value[$i];
                $sum += $i % 2 === 0 ? $digit : $digit * 3;
            }
    
            $checkDigit = (10 - ($sum % 10)) % 10;
            return $checkDigit === (int) $value[12];
        }, 'The :attribute must be a valid EAN-13 barcode.');
    }
}
