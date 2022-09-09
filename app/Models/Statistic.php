<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Statistic extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'statistics';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'statistic_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'quiz_id');
    }

    public function scopeSearch($query, $searchParam)
    {
        return $query->where('quiz_id', 'like', '%'.$searchParam.'%')
            ->orwhere('student_id', 'like', '%'.$searchParam.'%');
    }
}
