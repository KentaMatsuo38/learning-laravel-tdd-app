<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Models\Reservation;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;
    public function getVacancyLevelAttribute(): VacancyLevel
    {
        return new VacancyLevel($this->remainingCount());
    }

    public function remainingCount(): int
    {
        return $this->capacity-$this->reservations()->count();
    }
    public function __toString()
    {
        return $this->mark();
    }
    public function reservations():HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
