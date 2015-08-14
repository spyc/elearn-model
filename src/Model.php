<?php
/**
 * model
 *
 * PHP version 5
 *
 * Copyright (C) Tony Yip 2015.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category Guardian
 * @author   Tony Yip <tony@opensource.hk>
 * @license  http://opensource.org/licenses/GPL-3.0 GNU General Public License
 */

namespace Elearn\Model;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{

    /**
     * Column alias for property access.
     * @var array<string, string>
     */
    protected $aliases = [];

    /**
     * Add Alias for property.
     *
     * @param string $name
     * @param string $alias
     */
    protected function alias($name, $alias)
    {
        $this->aliases[$alias] = $name;
    }

    /**
     * Remove alias
     *
     * @param string $alias
     */
    protected function dropAlias($alias)
    {
        unset($this->aliases[$alias]);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->aliases)) {
            $key = $this->aliases[$key];
        }
        return parent::__get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function __set($key, $value)
    {
        if (array_key_exists($key, $this->aliases)) {
            $key = $this->aliases[$key];
        }
        parent::__set($key, $value);
    }

}