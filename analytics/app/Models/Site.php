<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Site extends Model
{
    use HasFactory;
    protected $table = 'sites';
    public $timestamps = true;

    protected $fillable = ['fqdn', 'active'];

    /**
     * The users that belong to the site.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_sites')->withPivot('role');
    }
}
