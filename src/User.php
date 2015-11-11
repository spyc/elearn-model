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

use DB;
use Elearn\Model\Auth\Authorizable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, AuthorizableContract
{
    use Authenticatable, CanResetPassword, Authorizable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * Primary Key.
     *
     * @var string
     */
    protected $primaryKey = 'pycid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get user community.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function community()
    {
        return $this->belongsToMany(Community::class, 'committee', 'pycid', 'community');
    }

    /**
     * Check User has Permission
     *
     * @param string $permission Permission Name.
     *
     * @return bool Auth or not.
     */
    public function hasPermission($permission)
    {
        $permission = Permission::select('id')
            ->where(['name' => $permission])
            ->first();
        $id = $permission->id;
        $auth = DB::table('user_auth')
            ->select(['active'])
            ->where([
                'pycid' => $this->pycid,
                'permission' => $id
            ])->first();
        return null !== $auth && $auth->active;
    }

    /**
     * User is in the community
     *
     * @param $communityName
     * @return bool
     */
    public function inCommunity($communityName)
    {
        foreach ($this->community as $community) {
            if ($community->name === $communityName) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check User is super admin.
     *
     * @return bool
     */
    public function isSudoer()
    {
        return $this->inCommunity('sudoer');
    }

    /**
     * Get post in the community.
     *
     * @param string $communityName
     *
     * @return string post or null
     */
    public function getCommunityPost($communityName)
    {
        $committee = DB::table('committee')
            ->join('community', 'community.id', '=', 'committee.community')
            ->select('committee.post')
            ->where('committee.pycid', $this->pycid)
            ->where('community.name', $communityName)
            ->first();
        return $committee->post;
    }
}