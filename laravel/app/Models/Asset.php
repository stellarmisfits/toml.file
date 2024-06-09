<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Http\Resources\Asset as AssetResource;
use App\Models\Traits\BelongsToTeam;
use App\Models\Traits\HasOrganizations;

class Asset extends BaseModel implements HasMedia
{
    use BelongsToTeam, HasMediaTrait;

    protected $fillable = [
        'name',
        'description',
        'details',
        'conditions',
        'anchor_asset_type',
        'anchor_asset',
        'redemption_instructions',
        'collateral_addresses',
        'collateral_address_messages',
        'collateral_address_signatures',
        'fixed_number',
        'max_number',
        'is_unlimited',
        'display_decimals',
    ];

    protected $casts = [
        'regulated' => 'boolean',
        'anchored' => 'boolean',
        'is_unlimited' => 'boolean',
    ];

    public function registerMediaCollections()
    {
        $this->addMediaCollection('images');
        $this->addMediaCollection('logo')->singleFile();
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('images')
            ->fit(Manipulations::FIT_CROP, 1000, 750)
            ->performOnCollections('images');

        $this->addMediaConversion('logo')
            ->fit(Manipulations::FIT_CROP, 250, 250)
            ->performOnCollections('logo');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * @param  mixed
     * @return string
     */
    public function getLogoAttribute(): ?string
    {
        $url = $this->getFirstMediaUrl('logo', 'logo');
        if ($url) {
            return url($url);
        }

        return null;
    }

    /**
     * @return AssetResource
     */
    public function getResourceAttribute(): AssetResource
    {
        return new AssetResource($this);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Asset->Account relationship
     *
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Asset->Organization relationship
     */
    public function organization(): ?BelongsTo
    {
        return optional($this->account)->organization();
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
            $query->whereHas('account.organization', function ($query) use ($organization_uuid) {
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
            $query->whereDoesntHave('account.organization', function ($query) use ($organization_uuid) {
                $query->where('organizations.uuid', $organization_uuid);
            });
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param string $account_uuid
     * @return Builder
     */
    public function scopeAccountUuidFilter(Builder $query, string $account_uuid = null)
    {
        if (!empty($account)) {
            $query->whereHas('account', function ($query) use ($account_uuid) {
                $query->where('accounts.uuid', $account_uuid);
            });
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param string $code
     * @return Builder
     */
    public function scopeCodeFilter(Builder $query, $code)
    {
        if (!empty($code)) {
            $query->where('code', $code);
        }

        return $query;
    }
}
