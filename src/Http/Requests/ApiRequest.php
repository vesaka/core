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

    protected string $messagesParser = 'default';
    protected $rules;

    protected $messages = [];
    
    const TYPES = ['string', 'numeric', 'array', 'file'];

    public function authorize() {
        return true;
    }

    public function messages() {
        $parser = $this->messagesParser;

        if (method_exists($this, $parser)) {
            return $this->{$parser}();
        }

        return parent::messages();
    }

    protected function default(): array {
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
                    $messages[$key.'.'.$class . $suffix] = $item->message();
                } else {
                    $name = explode(':', $item, 2);
                    $messageKey = $key . '.' . $name[0] . $suffix;
                    $messages[$messageKey] = $name[0];
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

    protected function simple(): array {
        $messages = [];
        $rules = $this->rules();
        foreach ($rules as $key => $rule) {
            $list = is_string($rule) ? explode('|', $rule) : $rule;

            foreach ($list as $item) {
                if (is_object($item)) {
                    $classname = get_class($item);
                    $class = substr($classname, strrpos($classname, '\\') + 1);
                    $messages["$key.$class"] = $class;
                } else {
                    $name = explode(':', $item, 2);
                    $messages["$key.$name[0]"] = $item;
                }
            }
        }
//dd($messages);
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
