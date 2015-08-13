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

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user';
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
     * {@inheritdoc}
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setKeyName('pycid');
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
     * Get post in the community.
     *
     * @param string $community
     *
     * @return string post or null
     */
    public function getCommunityPost($community)
    {
        $community = Community::select('id')
            ->where(['name' => $community])
            ->first();

        $community = $community->id;

        $committee = Committee::select('post')
            ->where([
                'pycid' => $this->pycid,
                'community' => $community
            ])
            ->first();

        if (null === $committee) {
            return null;
        }

        return $committee->post;
    }

    public function hasCommunityAuth($community, $permission)
    {
        $post = $this->getCommunityPost($community);

        $community = Community::select('id')
            ->where(['name' => $community])
            ->first();

        $community = $community->id;

        $permission = Permission::select('id')
            ->where(['name' => $permission])
            ->first();
        $id = $permission->id;

        $auth = DB::table('community_permission')
            ->select('active')
            ->where([
                'community' => $community,
                'post' => $post,
                'permission' => $id
            ])
            ->first();

        return null !== $auth && $auth->active;
    }
}