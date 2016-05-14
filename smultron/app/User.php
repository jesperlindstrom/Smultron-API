<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
	protected $fillable = [
		'room_id',
	];

	public function swipes() {
		return $this->hasMany('App\Swipe');
	}
}
