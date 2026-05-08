<?php

namespace App\Actions\Cms\ApiKey;

use App\Models\User\ApiKey;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class RegenerateApiKeyAction
{
    /**
     * Handle the action.
     */
    public function handle(ApiKey $apiKey): string
    {
        $salt = Str::password(15);

        $apiKey->update([
            'salt' => bcrypt($salt),
        ]);

        return Crypt::encrypt([
            'id' => $apiKey->id,
            'salt' => $salt,
        ]);
    }
}
