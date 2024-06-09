<?php

namespace App\Models;

use Dyrynda\Database\Support\GeneratesUuid;
use Spatie\MediaLibrary\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use GeneratesUuid;

    protected $uuidVersion = 'ordered';
}
