<?php

namespace App\Models;

use App\Models\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Query\Builder;
use App\Http\Resources\Organization as OrganizationResource;

use Illuminate\Validation\ValidationException;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\Image\Manipulations;
 use Spatie\MediaLibrary\Models\Media;
 use Spatie\MediaLibrary\HasMedia\HasMedia;
 use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Organization extends BaseModel implements HasMedia
{
    use HasMediaTrait, BelongsToTeam;

    // use HasMediaTrait;

    protected $casts = [
        'published' => 'boolean'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'alias',
        'description',
        'dba',
        'custom_url',

        // documentation properties
        'address',
        'address_attestation',
        'phone',
        'phone_attestation',
        'keybase',
        'twitter',
        'github',
        'email',
        'licensing_authority',
        'license_type',
        'license_number',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if($model->phone){
                $model->phone = PhoneNumber::make($model->phone)->formatE164();
            }
        });
    }


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
     * @return OrganizationResource
     */
    public function getResourceAttribute(): OrganizationResource
    {
        return new OrganizationResource($this);
    }

    /**
     * @return string
     */
    public function getUrlAttribute(): string
    {
        if ($this->custom_url) {
            return $this->custom_url;
        }

        return $this->hosted_url;

        return $url;
    }

    /**
     * @return string
     */
    public function getHostedUrlAttribute(): string
    {
        $appUrl = parse_url(config('app.url'));
        $url = $this->alias . '.' . $appUrl['host'];

        if (!empty($appUrl['port'])) {
            $url = $url . ':' . $appUrl['port'];
        }

        return $url;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Organization->SigningKey relationship
     *
     * @return BelongsTo
     */
    public function signingKey(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'signing_key_id');
    }

    /**
     * Organization->UriRequestSigningKey relationship
     *
     * @return BelongsTo
     */
    public function uriRequestSigningKey(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'uri_request_signing_key_id');
    }

    /**
     * Organization->Accounts relationship
     *
     * @return HasMany
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Organization->Assets relationship
     *
     * @return HasManyThrough
     */
    public function assets(): HasManyThrough
    {
        return $this->hasManyThrough(Asset::class, Account::class);
    }

    /**
     * Organization->Principals relationship
     *
     * @return BelongsToMany
     */
    public function principals(): BelongsToMany
    {
        return $this->belongsToMany(Principal::class, 'organization_principals');
    }

    /**
     * Organization->Validators relationship
     *
     * @return BelongsToMany
     */
    public function validators(): BelongsToMany
    {
        return $this->belongsToMany(Validator::class, 'organization_validators');
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
     * @param string $resource_uuid
     * @param string $resource_type
     * @param string $resource_query
     * @return Builder
     * @throws
     */
    public function scopeResourceFilter(
        $query,
        string $resource_uuid = null,
        string $resource_type = null,
        string $resource_query = null
    ) {
        if ($resource_uuid && $resource_type && $resource_query) {
            $types = ['assets', 'accounts', 'principals', 'validators'];
            throw_unless(in_array($resource_type, $types), new \LogicException('Incorrect resource_type'));

            if ($resource_query ==='linked') {
                $query->whereHas($resource_type, function ($query) use ($resource_uuid, $resource_type) {
                    $query->where($resource_type . '.uuid', $resource_uuid);
                });
            }

            if ($resource_query ==='unlinked') {
                $query->whereDoesntHave($resource_type, function ($query) use ($resource_uuid, $resource_type) {
                    $query->where($resource_type . '.uuid', $resource_uuid);
                });
            }
        }

        return $query;
    }


    /**
     * @param Builder $query
     * @param string $slug
     * @return Builder
     */
    public function scopeSlugFilter($query, $slug)
    {

        if (!empty($slug)) {
            $query->where('slug', $slug);
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param User $user
     * @return Builder
     */
    public function scopePublishedFilter($query, ?User $user)
    {

        $query->where(function ($query) use ($user) {
            $query->where('published', true);

            if ($user) {
                $query->orWhereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                });
            }
        });

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    |
    |
    */
}
