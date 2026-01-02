<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;
use App\Models\Vessel;
use App\Models\ServiceLine;
use App\Models\Category;
use App\Models\User;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'contact_email',
        'cc_emails',
        'organization_id',
        'vessel_id',
        'service_line_id',
        'category_id',
        'assigned_to_user_id',
    ];

    public function organization() {
        return $this->belongsTo(Organization::class);
    }

    public function vessel() {
        return $this->belongsTo(Vessel::class);
    }

    public function serviceLine() {
        return $this->belongsTo(ServiceLine::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function assignedToUser() {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }
}
