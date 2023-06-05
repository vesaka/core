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
    
    const TYPES = ['string', 'numeric', 'array', 'file'];

    public function authorize() {
        return true;
    }

    public function messages() {
        $messages = [];
        $rules = $this->rules();
        foreach ($rules as $key => $rule) {
            $suffix = '';
            $list = is_string($rule) ? explode('|', $rule) : $rule;
            
            $matchedTypes = array_values(array_filter(self::TYPES, function($type) use ($list) {
                return in_array($type, $list);
            }));

            if (isset($matchedTypes[0])) {
                $suffix = '.' . $matchedTypes[0];
            }
            foreach ($list as $item) {
                if ($item instanceof Rule) {
                    $classname = get_class($item);
                    $class = substr($classname, strrpos($classname, '\\') + 1);
                    $messages[$key.'.'.$class . $suffix]['rule'] = $item->message();
                } else {
                    $name = explode(':', $item, 2);
                    $messageKey = $key . '.' . $name[0] . $suffix;
                    $messages[$messageKey]['rule'] = $name[0];
                    if (!isset($name[1])) {
                        continue;
                    }

                    if (method_exists($this, $name[0])) {
                        $messages[$messageKey]['value'] = $this->{$name[0]}($name[1]);
                    } else {
                        $messages[$messageKey]['value'] = $name[1];
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
