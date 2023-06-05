<?php

namespace Vesaka\Core\Rules\User;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Contracts\Validation\Rule;

/**
 * Description of HasNoRole
 *
 * @author vesak
 */
class HasNoRole implements Rule {
    //put your code here
    protected $user;

    protected $role;

    public function __construct(User $user, string $role) {
        $this->user = $user;
        $this->role = $role;
    }

    public function message() {
    }

    public function passes($attribute, $value): bool {
        return ! app('user-role')->userHasRole($this->user->id, $this->role);
    }
}
