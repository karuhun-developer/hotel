<?php

namespace App\Actions\Cms\M3u\Source;

use App\Enums\CommonStatusEnum;
use App\Models\M3u\M3uChannel;
use App\Models\M3u\M3uSource;
use Illuminate\Support\Facades\Http;

class FetchChannelsAction
{
    /**
     * Fetch channels from M3U source URL and sync to database.
     */
    public function handle(M3uSource $m3uSource): int
    {
        $response = $this->callSource($m3uSource);

        $channels = $this->parseM3u($response);

        return $this->syncChannels($m3uSource, $channels);
    }

    /**
     * Call the M3U source API.
     */
    private function callSource(M3uSource $m3uSource): string
    {
        $request = Http::timeout(30)->withoutVerifying();

        // Apply headers
        if (! empty($m3uSource->headers)) {
            $headers = json_decode($m3uSource->headers, true);
            if (is_array($headers)) {
                $request = $request->withHeaders($headers);
            }
        }

        // Make request based on type
        if (strtoupper($m3uSource->type) === 'POST') {
            $body = ! empty($m3uSource->body) ? $m3uSource->body : '';
            $response = $request->withBody($body, 'application/x-www-form-urlencoded')->post($m3uSource->url);
        } else {
            $response = $request->get($m3uSource->url);
        }

        $response->throw();

        return $response->body();
    }

    /**
     * Parse M3U format content into array of channels.
     */
    private function parseM3u(string $content): array
    {
        preg_match_all(
            '/(?P<tag>#EXTINF:-1)|(?:(?P<prop_key>[-a-z]+)=\"(?P<prop_val>[^"]+)")|(?<something>,[^\r\n]+)|(?<url>http[^\s]+)/',
            $content,
            $match
        );

        $count = count($match[0]);
        $result = [];
        $index = -1;

        for ($i = 0; $i < $count; $i++) {
            $item = $match[0][$i];

            if (! empty($match['tag'][$i])) {
                $index++;
            } elseif (! empty($match['prop_key'][$i])) {
                $result[$index][$match['prop_key'][$i]] = $match['prop_val'][$i];
            } elseif (! empty($match['something'][$i])) {
                $result[$index]['name'] = str_replace(',', '', $item);
            } elseif (! empty($match['url'][$i])) {
                $result[$index]['url'] = $item;
            }
        }

        return $result;
    }

    /**
     * Sync parsed channels to database.
     */
    private function syncChannels(M3uSource $m3uSource, array $channels): int
    {
        $synced = 0;

        foreach ($channels as $channel) {
            if (! isset($channel['name'], $channel['url'])) {
                continue;
            }

            M3uChannel::updateOrCreate([
                'm3u_source_id' => $m3uSource->id,
                'name' => $channel['name'],
            ], [
                'url' => $channel['url'],
                'status' => CommonStatusEnum::ACTIVE,
            ]);

            $synced++;
        }

        return $synced;
    }
}
