<?php

namespace App\Trait;

use Illuminate\Http\Request;

trait WithoutReplicationAction
{
    public function authorizedToReplicate(Request $request): false
    {
        return false;
    }
}
