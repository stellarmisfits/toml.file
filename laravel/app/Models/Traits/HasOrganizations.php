<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use InvalidArgumentException;
use App\Models\Organization;

trait HasOrganizations
{
    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    |
    */

    //

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Get all of the organizations that the account belongs to.
     */
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(
            Organization::class,
            'organization_' . $this->getTable(),
        )->orderBy('name', 'asc');
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
     * @param string $organization_uuid
     * @return Builder
     */
    public function scopeLinkedOrganizationUuidFilter($query, string $organization_uuid = null)
    {
        if (!empty($organization_uuid)) {
            $query->whereHas('organizations', function ($query) use ($organization_uuid) {
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
            $query->whereDoesntHave('organizations', function ($query) use ($organization_uuid) {
                $query->where('organizations.uuid', $organization_uuid);
            });
        }

        return $query;
    }
}
