<?php
namespace Vesaka\Core\Tests\Integration;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Vesaka\Core\Database\Repositories\ModelRepository;
use Illuminate\Http\Request;
/**
 * Description of ModelRepositoryTest
 *
 * @author vesak
 */
class ModelRepositoryTest extends TestCase {
    
    public function test_model_has_correct_datatable_pagination() {
        $request = $this->mockRequest();
        
    }
    
    protected function mockRequest(array $data = []): Request
    {
        $request = Request::create('/', 'POST');
        $request->merge($data);
        return $request;
    }
}
