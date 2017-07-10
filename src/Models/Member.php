<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-7-10
 * Time: 上午11:42
 */

namespace Notadd\Vuser\Models;

use Notadd\Foundation\Member\Member as BaseMember;

class Member extends BaseMember {

    protected $table = 'members';

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
    }
}