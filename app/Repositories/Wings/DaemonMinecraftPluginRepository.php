<?php

namespace Pterodactyl\Repositories\Wings;

use Webmozart\Assert\Assert;
use Pterodactyl\Models\Server;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\TransferException;
use Pterodactyl\Exceptions\Http\Connection\DaemonConnectionException;

class DaemonMinecraftPluginRepository extends DaemonRepository
{
    /**
     * Install a plugin in the server
     *
     * @param string $downloadUrl
     * @param string $filename
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws DaemonConnectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function install(string $downloadUrl, string $filename): ResponseInterface
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/plugins/install', $this->server->uuid),
                [
                    'json' => [
                        'download_url' => $downloadUrl,
                        'filename' => $filename,
                    ],
                ]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }
}
