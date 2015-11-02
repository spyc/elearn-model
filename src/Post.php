<?php
/**
 * HHVM
 *
 * Copyright (C) Tony Yip 2015.
 *
 * @author   Tony Yip <tony@opensource.hk>
 * @license  http://opensource.org/licenses/GPL-3.0 GNU General Public License
 */

namespace Elearn\Model;


class Post extends SoftModel
{
    /**
     * Post Auth.
     */
    const OTHER_READ   = 1;
    const OTHER_WRITE  = 2;
    const OTHER_DELETE = 3;
    const OTHER_CHMOD  = 4;
    const OTHER_CHOWN  = 5;

    const GROUP_READ   = 6;
    const GROUP_WRITE  = 7;
    const GROUP_DELETE = 8;
    const GROUP_CHMOD  = 9;
    const GROUP_CHOWN  = 10;

    const OWNER_READ   = 11;
    const OWNER_WRITE  = 12;
    const OWNER_DELETE = 13;
    const OWNER_CHMOD  = 14;
    const OWNER_CHOWN  = 15;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * Get Post owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner', 'pycid');
    }

    /**
     * Get the Post groups.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Community::class, 'group');
    }

    /**
     * @param int $type
     *
     * @return bool
     */
    public function isAuth($type)
    {
        $value = 1 << $type;
        return ($value & $this->auth) !== 0;
    }

    /**
     * @param int $type
     * @param bool $value
     */
    public function setAuth($type, $value)
    {
        $real = 1 << $type;
        $this->auth += $value ? $real : -$real;
    }
}