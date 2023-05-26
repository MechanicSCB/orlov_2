<?php


namespace App\Classes;


use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoHandler
{
    public function getVideoParamsFromUrl(?string $videoUrl): ?array
    {
        if (Str::contains($videoUrl, ['youtu'])) {
            return $this->getVideoParamsFromYoutubeUrl($videoUrl);
        }

        if (Str::contains($videoUrl, ['vk.com'])) {
            return $this->getVideoParamsFromVkUrl($videoUrl);
        }

        return null;
    }

    public function getVideoParamsFromYoutubeUrl(?string $youtubeUrl): array
    {
        $params = [
            'code' => $this->getVideoIdFromYoutubeUrl($youtubeUrl),
            'start_time' => $this->getVideoTimeFromYoutubeUrl($youtubeUrl),
        ];

        return array_filter($params);
    }

    public function getVideoParamsFromVkUrl(?string $vkUrl): array
    {
        $videoParams = [];
        $tmp = explode('&', Str::after($vkUrl, '?'));

        foreach ($tmp as $param) {
            [$key, $value] = explode('=', $param);
            $videoParams[$key] = $value;
        }

        $videoParams['start_time'] = $this->getVideoTimeFromVkParam(@$videoParams['t']);

        return $videoParams;
    }

    public function getVideoIdFromUrl(?string $videoUrl): ?string
    {
        if (Str::contains($videoUrl, ['youtu'])) {
            return $this->getVideoIdFromYoutubeUrl($videoUrl);
        }

        return null;
    }

    public function getVideoIdFromYoutubeUrl(?string $videoUrl): ?string
    {
        // https://www.youtube.com/watch?v=38hFfYEJ-G8&feature=emb_imp_woyt
        // https://youtu.be/LrAKhbMCl2k?t=5
        // https://www.youtube.com/embed/p6mEwDRUdvk

        $videoUrl = str_replace(['/embed/', 'watch?v=', 'youtu.be/'], 'delimiterFrom', $videoUrl);
        $videoUrl = Str::after($videoUrl, 'delimiterFrom');
        $videoId = str_replace(['&', '?'], 'delimiterTo', $videoUrl, $count);

        if ($count) {
            $videoId = Str::before($videoId, 'delimiterTo');
        }

        if (strlen($videoId) !== 11) {
            return null;
        }

        return $videoId;
    }

    public function getVideoTimeFromYoutubeUrl(?string $videoUrl): ?int
    {
        $videoParams = [];
        $tmp = explode('&', Str::after($videoUrl, '?'));

        foreach ($tmp as $param) {
            if(count($explode = explode('=', $param)) !== 2){
                continue;
            }

            [$key, $value] = $explode;
            $videoParams[$key] = $value;
        }

        $seconds = $videoParams['start'] ?? @$videoParams['t'];

        return is_numeric($seconds) ? $seconds : null;
    }

    public function getVideoTimeFromVkParam(?string $vkTime): ?int
    {
        if (! $vkTime) {
            return null;
        }

        $minutes = Str::before($vkTime, 'm');
        $seconds = Str::between($vkTime, 'm', 's');

        if (! is_numeric($minutes) || ! is_numeric($seconds) || $seconds > 59) {
            return null;
        }

        return $minutes * 60 + $seconds;
    }

    /**
     * Save video preview and return it's path
     *
     * @param string $videoUrl
     * @return string|null
     */
    public function savePreviewFromVideoId(?string $videoId): ?string
    {
        if (! $videoId) {
            return null;
        }

        // $preview = @file_get_contents("https://i.ytimg.com/vi/$videoId/maxresdefault.jpg");
        // // $preview = Http::get("https://i.ytimg.com/vi/$videoId/maxresdefault.jpg");
        //
        // dd(tmr(),strlen($preview));
        // if ($preview = @file_get_contents("https://i.ytimg.com/vi/$videoId/maxresdefault.jpg")) {
        if ($preview = Http::get("https://i.ytimg.com/vi/$videoId/maxresdefault.jpg")) {
            $previewPath = "posts/post-previews/$videoId.jpg";
            Storage::put("public/$previewPath", $preview);
        }

        return @$previewPath;
    }


    public function getVideoEmbedUrl(array|string $videoParams): ?string
    {
        $videoParams = is_array($videoParams) ? $videoParams : json_decode($videoParams, 1);

        if (@$videoParams['code']) {
            $url = "http://www.youtube.com/embed/{$videoParams['code']}?rel=0&wmode=transparent";

            if(@$videoParams['start_time']){
                $url .= "&start={$videoParams['start_time']}";
            }

            return $url;
        }

        if (@$videoParams['oid'] && @$videoParams['id'] && @$videoParams['hash']) {
            $time = $this->secondsToTime(@$videoParams['start_time']);

            return "https://vk.com/video_ext.php?oid={$videoParams['oid']}&id={$videoParams['id']}&hash={$videoParams['hash']}&t=$time";
        }

        return null;
    }


    /**
     * Save video preview and return it's path
     *
     * @param string $videoUrl
     * @return string|null
     */
    public function savePreviewFromVideoUrl(?string $videoUrl): ?string
    {
        if (! $videoId = $this->getVideoIdFromUrl($videoUrl)) {
            return null;
        }

        return $this->savePreviewFromVideoId($videoId);
    }

    private function secondsToTime(?int $seconds): ?string
    {
        if(! $seconds){
            return null;
        }

        $min = (int)($seconds / 60);
        $sec = $seconds % 60;

        return "{$min}m{$sec}s";
    }

}
