<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    /**
     * Define a one-to-one relationship with the SiteSetting model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function siteSetting(): HasOne
    {
        return $this->hasOne(SiteSetting::class, 'site_id');
    }
}
