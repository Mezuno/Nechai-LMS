<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Assignment;
use App\Models\Statistic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function assignedQuizzes()
    {
        return $this->belongsToMany(Quiz::class, 'assignments', 'student_id', 'quiz_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'student_id', 'id');
    }

    public function statistic()
    {
        return $this->hasOne(Statistic::class, 'student_id', 'id');
    }

    public function statistics()
    {
        return $this->hasMany(Statistic::class, 'student_id', 'id');
    }

    public function scopeSearch($query, $searchParam)
    {
        return $query->where('name', 'like', '%'.$searchParam.'%')
            ->orwhere('email', 'like', '%'.$searchParam.'%');
    }

    public function getAvatarsPath()
    {
        return "img/avatars/";
    }

    public function clearAvatars()
    {
        $id = auth()->id();
        $path = "img/avatars";

        foreach ( glob( public_path("$path/$id.*") ) as $avatar ) {
            unlink($avatar);
        }
    }
}
