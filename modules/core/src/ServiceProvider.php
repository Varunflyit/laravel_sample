<?php

namespace Ecommify\Core;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Arr::macro('merge', function (array ...$arrays) {
            $merged = [];
            foreach ($arrays as $current) {
                foreach ($current as $key => $value) {
                    if (is_string($key)) {
                        if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                            $merged[$key] = array_merge($merged[$key], $value);
                        } else {
                            $merged[$key] = $value;
                        }
                    } else {
                        $merged[] = $value;
                    }
                }
            }

            return $merged;
        });

        Arr::macro('mergeRecursive', function (array ...$arrays) {
            $merged = [];
            foreach ($arrays as $current) {
                foreach ($current as $key => $value) {
                    if (is_string($key)) {
                        if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                            $merged[$key] = Arr::mergeRecursive($merged[$key], $value);
                        } else {
                            $merged[$key] = $value;
                        }
                    } else {
                        $merged[] = $value;
                    }
                }
            }

            return $merged;
        });
    }
}
