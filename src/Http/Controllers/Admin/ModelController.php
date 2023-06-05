<?php

namespace Vesaka\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ReflectionClass;
use Route;
use Vesaka\Core\Http\Requests\Model\StoreModelRequest;
use Vesaka\Core\Models\Model;

/**
 * Description of ModelController
 *
 * @author vesak
 */
class ModelController extends Controller {
    protected string $type = '';

    public function __construct() {
        $class = new ReflectionClass($this);
        $this->type = Str::of($class->getShortName())->replace('Controller', '')->kebab()->toString();
    }

    public function index() {
        $view = "admin::crud.$this->type.list";
        if (view()->exists($view)) {
            return view($view);
        }

        return view('admin::crud.model.list');
    }

    public function datatable(Request $request) {
        return app('model')->datatable($request);
    }

    public function paginate(Request $request) {
        $target = is_array(config('repos.'.$request->type)) ? $request->type : 'model';

        return app($target)->datatable($request);
    }

    public function create() {
        $model = new Model();
        $model->categories = [];
        $model->meta = [];

        $options = [
            'model' => $model,
            'categories' => app('category')->nested(),
            'type' => $this->type,
        ];
        if (view()->exists("admin::crud.$this->type.form")) {
            return view("admin::crud.$this->type.form", $options);
        }

        return view('admin::crud.model.form', $options);
    }

    public function store(StoreModelRequest $request) {
        DB::beginTransaction();
        $alias = $request->alias ?? $request->type;
        $target = is_array(config('repos.'.$alias)) ? $alias : 'model';

        $post = app($target)->findOrNew($request->id);
        $post->author_id = $request->author_id || auth()->id() || 1;
        $post->title = $request->title;
        $post->type = $request->type;
        $post->status = 'active';
        $post->parent = 0;
        $post->name = Str::slug($request->title);
        $post->content = $request->content;
        $post->save();
        DB::commit();

        return [
            'model' => $post,
        ];
    }

    public function show() {
    }

    public function edit($id) {
        $type = Str::of(Route::currentRouteName())->between('admin::', '.edit');

        $alias = 'image' === $type ? $type : 'img';

        $model = app($alias)->with('categories', 'meta')->find($id);
        $model->setRelation('meta', $model->meta->groupBy('name')->map(function ($group) {
            return $group->pluck('value');
        }));

        $media = $model->getFirstMedia(FEATURED_IMAGE);
        if ($media) {
            $model->imgSrc = $media->getUrl();
            $model->crop = array_map('intval', $media->getCustomProperty('crop'));
        } else {
            $model->imgSrc = '';
            $model->crop = [];
        }

        $model->imgSrc = $model->getFirstMediaUrl(FEATURED_IMAGE);

        $crudView = "admin::crud.$model->type.form";
        if (! view()->exists($crudView)) {
            $crudView = 'admin::crud.model.form';
        }

        return view($crudView, [
            'model' => $model,
            'categories' => app('category')->nested(),
        ]);
    }

    public function update(StoreModelRequest $request) {
        return $this->store($request);
    }

    public function destroy($id) {
        app('model')->where('id', $id)->forceDelete();
    }
}
