<?php

namespace Vesaka\Core\Console\Commands;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CreateRepository extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repo:create {--model=} --o {--namespace=app} {--skip=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates repository classes';

    protected $map = [
        'eloquent' => '{namespace}Database\\Repositories\\{model}Repository',
        'cache' => '{namespace}Database\Cache\\{model}Cache',
        'interface' => '{namespace}Database\\Interfaces\\{model}Interface',
        'observer' => '{namespace}Observers\\{model}Observer',
        'policy' => '{namespace}Policies\\{model}Policy',
        'collection' => '{namespace}Collections\\{model}Collection',
    ];

    protected $paths = [
        'eloquent' => 'Database/Repositories/',
        'cache' => 'Database/Cache/',
        'interface' => 'Database/Interfaces/',
        'observer' => 'Observers/',
        'policy' => 'Policies/',
        'collection' => 'Collections/',
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $model = Str::studly($this->option('model'));
        $options = (object) $this->options();

        $composer = Arr::dot(json_decode(File::get(base_path('composer.json')), true));
        $namespace = Str::toNamespace($options->namespace).'\\';

        //dd($namespace);

        foreach ($options->skip as $skip) {
            if (isset($this->map[$skip])) {
                unset($this->map[$skip]);
            }

            if (isset($this->paths[$skip])) {
                unset($this->paths[$skip]);
            }
        }

        if (! isset($composer['autoload.psr-4.'.$namespace])) {
            $this->error('Invalid namespace. Aborting!');
            exit;
        }

        $path = $composer['autoload.psr-4.'.$namespace];

        if (! $model) {
            $this->error('No model was specified. Aborting!');
            exit;
        }

        $stubs = File::glob(str_replace('\/', DIRECTORY_SEPARATOR, __DIR__.'/../../../stubs/*.stub'));

        foreach ($stubs as $stub) {
            $name = str_replace('.stub', '', basename($stub));

            if (in_array($name, $options->skip)) {
                continue;
            }

            $class = Str::aprintf($this->map[$name], ['namespace' => $namespace, 'model' => $model]);

            if (class_exists($class) || interface_exists($class)) {
                continue;
            }

            $content = Str::aprintf(file_get_contents($stub), ['entity' => $model, 'namespace' => $namespace]);

            $file = base_path(
                Str::aprintf(
                    str_replace(
                        '\\',
                        DIRECTORY_SEPARATOR,
                        $path.str_replace('{namespace}', '', $this->map[$name])
                    ),
                    ['model' => $model]
                )
            ).'.php';

            File::put($file, $content);
        }
    }
}
