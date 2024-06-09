<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Rules\Polymorphic;
use App\Rules\ValidateUuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Models\Account;
use App\Models\Asset;
use App\Http\Resources\Asset as AssetResource;
use App\Http\Resources\Organization as OrganizationResource;

class ImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return AssetResource || OrganizationResource
     */
    public function store(Request $request)
    {
         [
             'model_uuid'   => $model_uuid,
             'model_type'   => $model_type,
             'filename'     => $filename,
             'image_uuid'   => $image_uuid,
             'collection'   => $collection
         ] = $request->validate(
             [
                 'model_uuid'    => ['bail', 'required', 'string', new ValidateUuid],
                 'model_type'    => ['bail', 'required', 'string', Rule::in(['organization', 'asset'])],
                 'image_uuid'    => ['required', new ValidateUuid],
                 'collection'    => ['bail', 'required', 'string', Rule::in(['logo'])],
                 'filename'      => ['required', 'string', 'max:255'],
                 // 'content_type'  => ['required', 'string', 'max:255'],
             ],
             [
                 'collection.in' => 'The :attribute must be equal to \'logo\'',
                 'model_type.in' => 'The :attribute must be one of \'organization\' or \'asset\'',
             ]
         );

        switch ($model_type) {
            case 'organization':
                $model = (new Organization)->whereUuid($model_uuid)->firstOrFail();
                break;
            case 'asset':
                $model = (new Asset())->whereUuid($model_uuid)->firstOrFail();
                break;
            default:
                abort(422);
        }

        // validate the user is authorized to update the model
        // $this->authorize('update', $model);

        $base64 = base64_encode(\Storage::get('tmp/' .$image_uuid));

        $model->addMediaFromBase64($base64, ['image/png', 'image/jpeg'])
            ->usingFileName($filename)
            ->toMediaCollection($collection);

        return $model->resource;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return AssetResource || OrganizationResource
     */
    public function destroy(Request $request)
    {
        [
            'model_uuid'   => $model_uuid,
            'model_type'   => $model_type,
            'collection'   => $collection
        ] = $request->validate(
            [
                'model_uuid'    => ['bail', 'required', 'string', new ValidateUuid],
                'model_type'    => ['bail', 'required', 'string', Rule::in(['organization', 'asset'])],
                'collection'    => ['bail', 'required', 'string', Rule::in(['logo'])],
            ],
            [
                'collection.in' => 'The :attribute must be equal to \'logo\'',
                'model_type.in' => 'The :attribute must be one of \'organization\' or \'asset\'',
            ]
        );

        switch ($model_type) {
            case 'organization':
                $model = (new Organization)->whereUuid($model_uuid)->firstOrFail();
                break;
            case 'asset':
                $model = (new Asset())->whereUuid($model_uuid)->firstOrFail();
                break;
            default:
                abort(422);
        }

        $model->clearMediaCollection($collection);

        return $model->resource;
    }
}
