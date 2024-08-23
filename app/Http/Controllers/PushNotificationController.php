<?php

namespace App\Http\Controllers;

use App\Models\PushNotification;
use Illuminate\Http\Request;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class PushNotificationController extends Controller
{
    public function saveSubscription(Request $request)
    {
        $item = new PushNotification();
        $item->subscriptions = json_decode($request->sub);
        $item->save();
        return response()->json(['message' => 'Added successfully!'], 200);
    }

    public function sendNotification(Request $request)
    {

        $auth = [
            'VAPID' => [
                'subject' => 'https://web-notification.test',
                'publicKey' => 'BPK--sF6CtKR7m2jAA4ofZdmsSHi1nxcm3TGfepChwOYA2oE26P50f6hWnYdyFD5zaibY3gB6ExD0VGXUkgyBoc',
                'privateKey' => 'eqmkimjoeQbFGOA3OjwUviXvwQzK1Mys1t0zAib8A0s',
            ],
        ];

        $webPush = new WebPush($auth);

        $payload = json_encode([
            'title' => $request->name,
            'body' => $request->body,
            'url' => 23
        ]);

        $notifiables = PushNotification::all();

        foreach ($notifiables as $notifiable) {
            $webPush->sendOneNotification(
                Subscription::create($notifiable->subscriptions),
                $payload,
                ['TTL' => 5000]
            );
        }
    }
}
