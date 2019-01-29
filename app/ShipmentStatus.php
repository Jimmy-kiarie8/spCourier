<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentStatus extends Model
{
	use SoftDeletes;
    public $fillable = [
        'status', 'user_id', 'branch_id', 'remark'
    ];
    public function shipment()
    {
        return $this->belongsTo('App\Shipment');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
