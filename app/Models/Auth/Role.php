<?php

namespace App\Models\Auth;

use Akaunting\Sortable\Traits\Sortable;
use App\Traits\Tenants;
use Laratrust\Models\LaratrustRole;
use Laratrust\Traits\LaratrustRoleTrait;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;

class Role extends LaratrustRole
{
    use LaratrustRoleTrait, SearchString, Sortable, Tenants;

    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'description', 'created_from', 'created_by'];

    /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('roles.roles.edit', $this->id),
            'permission' => 'update-roles-roles',
        ];

        $actions[] = [
            'title' => trans('general.duplicate'),
            'icon' => 'file_copy',
            'url' => route('roles.roles.duplicate', $this->id),
            'permission' => 'create-roles-roles',
        ];

        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'roles.roles.destroy',
            'permission' => 'delete-roles-roles',
            'model' => $this,
        ];

        return $actions;
    }

    /**
     * Scope to get all rows filtered, sorted and paginated.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $sort
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCollect($query, $sort = 'display_name')
    {
        $request = request();

        $search = $request->get('search');
        $limit = (int) $request->get('limit', setting('default.list_limit', '25'));

        return $query->usingSearchString($search)->sortable($sort)->paginate($limit);
    }
}
