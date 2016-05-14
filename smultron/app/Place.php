<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model {
	protected $fillable = [
		'title',
		'category',
		'url',
		'room_id'
	];
}
