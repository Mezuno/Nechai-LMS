<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assignments';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'assignment_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function students()
    {
        return $this->hasMany(User::class, 'id', 'student_id');
    }
}
