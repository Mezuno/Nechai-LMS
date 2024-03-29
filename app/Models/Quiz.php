<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quizzes';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'quiz_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function statistics()
    {
        return $this->hasMany(Statistic::class, 'quiz_id', 'quiz_id');
    }

    public function status()
    {
        return $this->belongsTo(Statistic::class, 'quiz_id', 'quiz_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id', 'quiz_id');
    }

    public function scopeSearch($query, $searchParam)
    {
        return $query->where('title', 'like', '%'.$searchParam.'%')
            ->orwhere('description', 'like', '%'.$searchParam.'%');
    }
}
