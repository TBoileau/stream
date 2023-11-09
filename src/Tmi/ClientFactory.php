<?php

declare(strict_types=1);

namespace App\Tmi;

use GhostZero\Tmi\Client;
use GhostZero\Tmi\ClientOptions;

final class ClientFactory
{
    public static function create(string $twitchUsername, string $twitchOAuthToken): Client
    {
        return new Client(new ClientOptions([
            'options' => ['debug' => true],
            'connection' => [
                'secure' => true,
                'reconnect' => true,
                'rejoin' => true,
            ],
            'identity' => [
                'username' => $twitchUsername,
                'password' => sprintf('oauth:%s', $twitchOAuthToken),
            ],
            'channels' => [$twitchUsername]
        ]));
    }
}
