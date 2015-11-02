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


class News extends SoftModel
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'news';

    /**
     * Get news author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author', 'pycid');
    }

    /**
     * Get post tags.
     *
     * @param string $value
     *
     * @return array
     */
    public function getTagAttribute($value)
    {
        return explode('|', $value);
    }

    /**
     * Set post tags.
     *
     * @param array $value
     */
    public function setTagAttribute(array $value)
    {
        $this->attributes['tag'] = implode('|', $value);
    }
}