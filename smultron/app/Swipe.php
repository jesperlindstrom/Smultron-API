<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Swipe extends Model {
	protected $fillable = [
		'place_id',
		'state',
		'user_id'
	];

	public function Place() {
		return $this->hasOne('App\Place');
	}
}
