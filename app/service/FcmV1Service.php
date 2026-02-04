<?php

namespace App\service;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmV1Service
{
    private function accessToken(): string
    {
        $credPath = base_path(config('services.fcm_v1.credentials'));
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        $creds = new ServiceAccountCredentials($scopes, $credPath);
        $token = $creds->fetchAuthToken();

        if (!isset($token['access_token'])) {
            throw new \RuntimeException('Failed to get Google access token for FCM v1');
        }

        return $token['access_token'];
    }

public function sendToToken(string $token, string $title, string $body, array $data = []): void
{
    $projectId = config('services.fcm_v1.project_id');
    $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

    $payloadData = array_map('strval', array_merge($data, [
        'title' => $title,
        'body'  => $body,
        'url'   => $data['url'] ?? '/dashboard/notifications',
    ]));

    Http::withToken($this->accessToken())
        ->acceptJson()
        ->post($url, [
            'message' => [
                'token' => $token,

                // ✅ مهم: خلي notification كمان (مش بس webpush)
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],

                // ✅ data دائماً سترينغ
                'data' => $payloadData,

                // ✅ WebPush settings (الأهم للمتصفح)
                'webpush' => [
                    'headers' => [
                        'Urgency' => 'high',
                        'TTL'     => '3600', // 1 hour
                    ],

                    // ✅ هذا اللي Chrome بيعتمد عليه لعرض الإشعار بالخلفية
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                        'icon'  => url('/favicon.ico'),
                    ],

                    'data' => $payloadData,

                    'fcm_options' => [
                        'link' => url($data['url'] ?? '/dashboard/notifications'),
                    ],
                ],
            ],
        ])
        ->throw();
}


    public function sendToTokens(array $tokens, string $title, string $body, array $data = []): void
    {
        $tokens = array_values(array_filter(array_unique($tokens)));
        foreach ($tokens as $t) {
            try {
                $this->sendToToken($t, $title, $body, $data);
            } catch (\Throwable $e) {
    Log::warning("FCM send failed", [
        'err' => $e->getMessage(),
        'token' => $t,
    ]);
            }
        }
    }
}
