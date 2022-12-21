<?php
namespace Vesaka\Core\Http\Requests\Model;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Description of StorePostRequest
 *
 * @author vesak
 */
class StoreModelRequest extends FormRequest {
    
    public function rules() {
        //dd($this->all());
        return [
            'title' => 'required',
            'content' => 'required',
            
        ];
    }
}
