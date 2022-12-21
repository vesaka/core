<?php

namespace Vesaka\Core\Providers;

use Illuminate\Support\ServiceProvider;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use ReflectionClass;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

/**
 * Description of BaseServiceProvider
 *
 * @author vesak
 */
class BaseServiceProvider extends ServiceProvider {
    
    protected string $title = 'core';
    protected string $alias = '';
    protected array $providers = [];
    protected string $__dir__ = '';
    protected string $namespace = '';


    public function __construct($app) {
        parent::__construct($app);
        
        if (!$this->namespace) {
            $this->namespace = Str::substr(__NAMESPACE__, 0, strpos(__NAMESPACE__, '\\Providers')) . '\\';
        }
        
        if ('core' === $this->title) {
            $this->alias = 'admin';
        } else if (!$this->alias) {
            $this->alias = $this->title;
        }
        $reflection = new ReflectionClass(get_called_class());
        $dir = str_replace('core', $this->title, dirname($reflection->getFileName()));
        config(["namespaces.$this->title" => str_replace('Providers', '', $reflection->getNamespaceName())]);
        $this->__dir__ = substr($dir, 0, strpos($dir, DIRECTORY_SEPARATOR . 'src') + 1);
    }
    
    public function register(): void {
        
    }
    
    public function boot() {
        
    }
    
    protected function registerProviders() {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }
    
    protected function includePackageClasses(string $path, Closure $callback) {
        $dir = $this->__dir__ . ltrim($path, DIRECTORY_SEPARATOR);

        if (!file_exists($dir)) {
            return;
        }
        $directoryIterator = new RecursiveDirectoryIterator(
                $dir,
                RecursiveDirectoryIterator::SKIP_DOTS |
                RecursiveDirectoryIterator::KEY_AS_PATHNAME
        );

        $files = new RecursiveIteratorIterator($directoryIterator);


        foreach ($files as $realpath => $file) {

            $realpath = $file->getRealPath();
            if (pathinfo($realpath, PATHINFO_EXTENSION) !== 'php') {
                continue;
            }


            $content = file_get_contents($file->getRealPath());
            $tokens = token_get_all($content);
            $namespace = '';

            for ($index = 0; isset($tokens[$index]); $index++) {
                if (!isset($tokens[$index][0])) {
                    continue;
                }
                if (T_NAMESPACE === $tokens[$index][0]) {
                    $index += 2;
                    while (isset($tokens[$index]) && is_array($tokens[$index])) {
                        $namespace .= $tokens[$index++][1];
                    }
                }
                if (T_CLASS === $tokens[$index][0] && T_WHITESPACE === $tokens[$index + 1][0] && T_STRING === $tokens[$index + 2][0]) {
                    $index += 2;
                    $callback($namespace . '\\' . $tokens[$index][1]);
                    break;
                }
            }
        }       
    }
}
