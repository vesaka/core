<?php

namespace Vesaka\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;
use File;
use RecursiveDirectoryIterator;
use Closure;
use Illuminate\Support\Str;

/**
 * Description of ComponentsServiceProvider
 *
 * @author vesak
 */
class ComponentsServiceProvider extends BaseServiceProvider {

    protected array $packageComponents = [];

    //protected string $alias = 'admin';
    public function register(): void {
        
    }

    public function boot() {
        $this->includePackageClasses('src/View/Components', function ($component) {
            //$alias = class_basename($component);
            $this->packageComponents[] = $component;
        });

        $this->loadViewComponentsAs($this->alias, $this->packageComponents);

        $dir = $this->__dir__ . 'resources/views/components';

        if (!File::exists($dir)) {
            return;
        }
        $directoryIterator = new RecursiveDirectoryIterator(
                $dir,
                RecursiveDirectoryIterator::SKIP_DOTS |
                RecursiveDirectoryIterator::KEY_AS_PATHNAME
        );

        $files = File::allFiles($dir);
        $bladeExtension = '.blade.php';

        foreach ($files as $realpath => $file) {

            $realpath = $file->getRealPath();
            if (!Str::endsWith($realpath, $bladeExtension)) {
                continue;
            }

            $alias = str_replace(DIRECTORY_SEPARATOR, '.', substr(str_replace([$dir, $bladeExtension], '', $realpath), 1));
            $dotAlias = str_replace(DIRECTORY_SEPARATOR, '.', $alias);
            $path = $this->alias . '::components.' . $dotAlias;
            Blade::component($path, $this->alias . '-' . $dotAlias);
        }
    }

}
