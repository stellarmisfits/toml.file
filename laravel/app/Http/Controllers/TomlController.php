<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Services\TomlService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;
use Yosymfony\Toml\TomlBuilder;

class TomlController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  string  $key
     * @return \Illuminate\Http\Response
     */
    public function show($key)
    {
        $org = Organization::where(['alias' => $key, 'published' => true])->firstOrFail();
        $ts = new TomlService($org);
        $tb = $ts->generateToml();

        return response($tb->getTomlString(), 200)
            ->header('Content-Type', 'text/plain');
    }
}
