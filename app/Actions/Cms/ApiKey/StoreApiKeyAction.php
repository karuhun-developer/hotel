<?php

namespace App\Actions\Cms\ApiKey;

use App\Enums\CommonStatusEnum;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class StoreApiKeyAction
{
    /**
     * Handle the action.
     */
    public function handle(User $user, string $name): array
    {
        $salt = Str::password(15);

        $apiKey = $user->apiKeys()->create([
            'name' => $name,
            'salt' => bcrypt($salt),
            'expired_at' => null,
            'last_used_at' => null,
            'status' => CommonStatusEnum::ACTIVE,
        ]);

        $encryptedKey = Crypt::encrypt([
            'id' => $apiKey->id,
            'salt' => $salt,
        ]);

        return [
            'apiKey' => $apiKey,
            'plainTextKey' => $encryptedKey,
        ];
    }
}
