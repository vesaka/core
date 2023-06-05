<?php

namespace Vesaka\Core\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Description of ApiRequest
 *
 * @author Vesaka
 */
class ApiRequest extends FormRequest {
    protected $rules;

    protected $messages = [];

    public function authorize() {
        return true;
    }

    public function messages() {
        $messages = [];
        $rules = $this->rules();
        foreach ($rules as $key => $rule) {
            if (is_string($rule)) {
                $list = explode('|', $rule);
            } else {
                $list = $rule;
            }
            foreach ($list as $item) {
                if ($item instanceof Rule) {
                    $classname = get_class($item);
                    $class = substr($classname, strrpos($classname, '\\') + 1);
                    $messages[$key.'.'.$class]['rule'] = $item->message();
                } else {
                    $name = explode(':', $item, 2);
                    $messages[$key.'.'.$name[0]]['rule'] = $name[0];
                    if (! isset($name[1])) {
                        continue;
                    }

                    if (method_exists($this, $name[0])) {
                        $messages[$key.'.'.$name[0]]['value'] = $this->{$name[0]}($name[1]);
                    } else {
                        $messages[$key.'.'.$name[0]]['value'] = $name[1];
                    }
                }
            }
        }

        return $messages;
    }

    protected function dimensions($value) {
        $values = explode(',', $value);

        $pairs = [];
        foreach ($values as $key => $item) {
            $pair = explode('=', $item);
            $pairs[$pair[0]] = $pair[1];
        }

        return $pairs;
    }

    public function rules() {
        return [];
    }
}
