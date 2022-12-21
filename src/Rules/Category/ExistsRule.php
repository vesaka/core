<?php
namespace Vesaka\Core\Rules\Category;

use Illuminate\Contracts\Validation\Rule;

/**
 * Description of ExistsRule
 *
 * @author vesak
 */
class ExistsRule implements Rule {
    //put your code here
    public function message() {
        return 'Category already axists';
    }

    public function passes($attribute, $value): bool {
        if (request('category')) {
            return true;
        }
        return !app('category')->where([
            'parent_id' => request('parent_id') ?? null,
            'name' => $value
        ])->exists();
    }

}
