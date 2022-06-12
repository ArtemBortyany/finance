<?php

namespace App\Models\Auth;

use App\Abstracts\Model;

class UserInvitation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_invitations';

    protected $tenantable = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['user_id', 'company_id', 'token'];

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User');
    }
 
    /**
     * Scope a query to only include given token value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeToken($query, $token)
    {
        $query->where('token', $token);
    }
}
