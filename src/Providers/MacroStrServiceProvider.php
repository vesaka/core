<?php

namespace Vesaka\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

/**
 * Description of MacroServiceProvider
 *
 * @author vesak
 */
class MacroStrServiceProvider extends ServiceProvider {

    public function register(): void {
        Str::macro('dotKebab', function ($value) {
            if (is_object($value)) {
                $value = (new ReflectionObject($value))->getName();
            } else if (class_exists($value)) {
                $value = (new ReflectionClass($value))->getName();
            }
            return collect(explode('\\', $value))->map(function ($segment) {
                return Str::kebab($segment);
            })->implode('.');
        });

        Str::macro('toNamespace', function ($value) {
            return collect(explode('.', $value))->map(function ($segment) {
                return Str::studly($segment);
            })->implode('\\');
        });

        Str::macro('aprintf', function ($format, array $data, $pattern = "/\{(\w+)\}/") {
            return preg_replace_callback($pattern, function ($matches) use ($data) {
                return @$data[$matches[1]] ?: $matches[0];
            }, $format);
                });
            }

    public function boot() {
        
    }

}
