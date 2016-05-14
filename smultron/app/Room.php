<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model {
	protected $fillable = [
		'destination',
		'code',
	];

	public function places() {
		return $this->hasMany('App\Place');
	}

	public function users() {
		return $this->hasMany('App\User');
	}
}
