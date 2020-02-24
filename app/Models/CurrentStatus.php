<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CurrentStatus
 * 
 * @property int $id
 * @property int $current_get
 * @property int $current_serve
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class CurrentStatus extends Model
{
	protected $table = 'current_statuses';

	protected $casts = [
		'current_get' => 'int',
		'current_serve' => 'int',
		'current_date' => 'date'
	];

	protected $fillable = [
		'current_get',
		'current_serve',
		'current_date'
	];
}
