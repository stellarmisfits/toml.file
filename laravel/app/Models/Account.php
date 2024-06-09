<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\BelongsToTeam;
use App\Models\Traits\HasOrganizations;

class Account extends BaseModel
{
    use BelongsToTeam;

    protected $casts = [
        'home_domain_updated_at' => 'datetime'
    ];

    protected $fillable = [
        'name',
        'alias',
        'public_key',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * @return bool
     */
    public function getDomainVerifiedAttribute(): bool
    {
        if(!$this->organization_id) {
            return false;
        }

        if(!$this->home_domain){
            return false;
        }

        if($this->home_domain !== $this->organization->url) {
            return false;
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Account->Organization relationship
     *
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Account->Assets relationship
     *
     * @return HasMany
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Account->Validators relationship
     *
     * @return HasMany
     */
    public function validators(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * @param Builder $query
     * @param string $publicKey
     * @return Builder
     */
    public function scopePublicKeyFilter($query, string $publicKey = null)
    {

        if (!empty($publicKey)) {
            $query->where('public_key', $publicKey->id);
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param string $organization_uuid
     * @return Builder
     */
    public function scopeLinkedOrganizationUuidFilter($query, string $organization_uuid = null)
    {

        if (!empty($organization_uuid)) {
            $query->whereHas('organization', function ($query) use ($organization_uuid) {
                $query->where('organizations.uuid', $organization_uuid);
            });
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param string $organization_uuid
     * @return Builder
     */
    public function scopeUnlinkedOrganizationUuidFilter($query, string $organization_uuid = null)
    {

        if (!empty($organization_uuid)) {
            $query->whereDoesntHave('organization', function ($query) use ($organization_uuid) {
                $query->where('organizations.uuid', $organization_uuid);
            });
        }

        return $query;
    }


    /**
     * @param Builder $query
     * @param Asset $asset
     * @return Builder
     */
    public function scopeAssetFilter($query, Asset $asset)
    {

        if (!empty($asset)) {
            $query->whereHas('assets', function ($query) use ($asset) {
                $query->where('assets.id', $asset->id);
            });
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param User $user
     * @return Builder
     */
    public function scopeVerifiedFilter($query, ?User $user)
    {

        $query->where(function ($query) use ($user) {
            $query->where('verified', true);

            if ($user) {
                $query->orWhere('user_id', $user->id);
            }
        });

        return $query;
    }
}
