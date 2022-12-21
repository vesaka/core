<?php

namespace Vesaka\Core\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use ReflectionClass;
use Illuminate\Support\Str;
use Vesaka\Core\Models\Model;
use Vesaka\Core\Http\Requests\Model\StoreModelRequest;
use Illuminate\Http\Request;
use DB;
/**
 * Description of ModelController
 *
 * @author vesak
 */
class ModelController extends Controller {
    
    protected string $type = '';
    
    public function __construct() {
        $class = new ReflectionClass($this);
        $this->type = Str::of($class->getShortName())->replace('Controller','')->kebab()->toString();
    }
    
    public function index() {
        return view("admin::crud.$this->type.list");
    }
    
    public function datatable(Request $request) {
        return app('model')->datatable($request);
    }
    
    public function create() {
        return view("admin::crud.$this->type.form", [
            'model' => Model::firstOrNew(),
            'categories' => app('category')->nested()
        ]);
    }
    
    public function store(StoreModelRequest $request) {

        DB::beginTransaction();
        $post = app('model')->findOrNew(0);
        $post->author_id = $request->author_id || auth()->id() || 1;
        $post->title = $request->title;
        $post->type = $request->type;
        $post->name = Str::slug($request->title);
        $post->content = $request->content;
        $post->save();
        DB::commit();
        return [
            'model' => $post
        ];
    }
    
    public function show() {
        
    }
    
    public function edit() {
        
    }
    
    public function update(StoreModelRequest $request) {
        return $this->store($request);
    }
    
    public function destory() {
        
    }
}
