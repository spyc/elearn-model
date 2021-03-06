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

class Community extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'community';

    /**
     * Get Committee.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function committee()
    {
        return $this->belongsToMany(User::class, 'committee', 'community', 'pycid');
    }

    /**
     * Get Community by name.
     * 
     * @param string $name
     * @return Community|null
     */
    public static function getCommunityByName($name)
    {
        $community = Community::where('name', '=', $name)->first();
        return $community;
    }

    /**
     * Get Committee and Post.
     * 
     * @return array<\Illuminate\Database\Query\Builder>
     */
    public function getCommitteeWithPost()
    {
        $committee = User::select(['user.*', 'committee.post'])
                        ->join('committee', 'user.pycid', '=', 'committee.pycid')
                        ->where('committee.community', '=', $this->id)
                        ->get();

        return $committee;
    }
}