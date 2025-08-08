<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'title',
        'description',
        'attachment',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->created_at)->format('d-m-Y H:i');
    }

    public function getFormattedUpdatedAtAttribute()
    {
        return Carbon::parse($this->updated_at)->format('d-m-Y H:i');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeByDepartment($query, $departmentId)
    {
        if ($departmentId) {
            return $query->where('department_id', $departmentId);
        }
        return $query;
    }

    public function scopeForUser($query, $user)
    {
        // If user is admin (role_id = 1), show all announcements
        if ($user->role_id == 1) {
            return $query;
        }
        
        // For regular users, show announcements for their department or general announcements
        return $query->where(function($q) use ($user) {
            $q->where('department_id', $user->employee->department_id)
              ->orWhereNull('department_id');
        });
    }
}
