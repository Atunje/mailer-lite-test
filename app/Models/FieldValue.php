<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldValue extends Model
{
    //no primary key column
    protected $primaryKey = null;
    public $incrementing = false;

    //no timestamps columns
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subscriber_id',
        'field_id',
        'value'
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function title()
    {
        return $this->field->title;
    }
}
