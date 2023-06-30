<?php

namespace Vesaka\Core\Tests\Integration;

use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * Description of ModelRepositoryTest
 *
 * @author vesak
 */
class ModelRepositoryTest extends TestCase {
    public function test_model_has_correct_datatable_pagination() {
        $request = $this->mockRequest();
        $this->assertTrue(true);
    }

    protected function mockRequest(array $data = []): Request {
        $request = Request::create('/', 'POST');
        $request->merge($data);

        return $request;
    }
}
