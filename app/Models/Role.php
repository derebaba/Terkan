<?php namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
	protected $fillable = ['name', 'display_name', 'description'];

	public function permissions() {
		return $this->belongsToMany(Permission::class);
	}

	public function users() {
		return $this->belongsToMany('App\User');
	}
}