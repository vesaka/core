<?php

namespace Vesaka\Core\Providers;

use Illuminate\Support\Arr;

/**
 * Description of ConfigServiceProvider
 *
 * @author vesak
 */
class ConfigServiceProvider extends BaseServiceProvider {
    
    public function register(): void {
        $files = glob($this->__dir__ . "config" . DIRECTORY_SEPARATOR . "*.php");
        foreach ($files as $file) {
            $key = str_replace('.php', '', basename($file));
            $merging = require_once $file;
            $original = config($key, []);

            if (is_array($merging)) {
                config([$key => $this->mergeConfigs($original, $merging)]);
            }
        }
    }
    
    public function boot() {
        
    }
    
    protected function mergeConfigs(array $original, array $merging) {
        $array = array_merge($original, $merging);

        foreach ($original as $key => $value) {
            if (!is_array($value)) {
                continue;
            }

            if (!Arr::exists($merging, $key)) {
                continue;
            }

            if (is_numeric($key)) {
                continue;
            }

            if (is_array($merging[$key])) {
                $array[$key] = $this->mergeConfigs($value, $merging[$key]);
            }
        }

        return $array;
    }
}
