<?php

namespace Vesaka\Core\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Vesaka\Core\Rules\Category\ExistsRule;

/**
 * Description of StoreCategoryRequest
 *
 * @author vesak
 */
class StoreCategoryRequest extends FormRequest {
    public function rules() {
        return [
            'name' => ['required', new ExistsRule()],
            'meta.order' => 'nullable|integer',
        ];
    }

    public function messages() {
        return [
            'name.reqiuired' => 'Then :attribute is required',
            'meta.order.integer' => 'The :attribute must be integer',
        ];
    }
}
