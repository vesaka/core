<?php
namespace Vesaka\Core\Rules\Post;

use Illuminate\Contracts\Validation\Rule;
/**
 * Description of HasHtmlRule
 *
 * @author vesak
 */
class HasHtmlRule implements Rule {
    //put your code here
    public function message() {
        return 'The :attribute is required';
    }

    public function passes($attribute, $value): bool {
        return !empty(strip_tags($value));
    }

}
