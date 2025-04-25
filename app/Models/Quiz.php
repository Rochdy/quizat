<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Quiz extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'notes',
        'starts_at',
        'expires_at',
        'slug',
        'is_available',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'is_available' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class)->whereNotNull('score');
    }

    #[Scope]
    public function currentlyAvailable(Builder $query)
    {
        $query->where('is_available', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')->whereNull('expires_at');
            })
            ->orWhere(function ($query) {
                $now = Carbon::now();
                $query->where('starts_at', '<=', $now)
                    ->where('expires_at', '>=', $now);
            });
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->slug = Str::uuid();
        });
    }
}
