<?php

namespace Ecommify\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'core';

    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id', 'source_id');
    }
}
