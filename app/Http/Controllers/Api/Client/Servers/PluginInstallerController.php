<?php

namespace Pterodactyl\Http\Controllers\Api\Client\Servers;

use GuzzleHttp\Client;
use Pterodactyl\Models\Server;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\GuzzleException;
use Pterodactyl\Exceptions\DisplayException;
use Pterodactyl\Repositories\Wings\DaemonFileRepository;
use Pterodactyl\Http\Controllers\Api\Client\ClientApiController;
use Pterodactyl\Repositories\Wings\DaemonMinecraftPluginRepository;
use Pterodactyl\Exceptions\Http\Connection\DaemonConnectionException;
use Pterodactyl\Http\Requests\Api\Client\Servers\PluginInstallerRequest;

class PluginInstallerController extends ClientApiController
{
    /**
     * @var \Pterodactyl\Repositories\Wings\DaemonMinecraftPluginRepository
     */
    protected $daemonMinecraftPluginRepository;

    /**
     * @var \Pterodactyl\Repositories\Wings\DaemonFileRepository
     */
    protected $daemonFileRepository;

    /**
     * PluginInstallerController constructor.
     * @param DaemonMinecraftPluginRepository $daemonMinecraftPluginRepository
     * @param DaemonFileRepository $daemonFileRepository
     */
    public function __construct(DaemonMinecraftPluginRepository $daemonMinecraftPluginRepository, DaemonFileRepository $daemonFileRepository)
    {
        parent::__construct();

        $this->daemonMinecraftPluginRepository = $daemonMinecraftPluginRepository;
        $this->daemonFileRepository = $daemonFileRepository;
    }

    /**
     * @param PluginInstallerRequest $request
     * @param Server $server
     * @return array
     * @throws DisplayException
     */
    public function search(PluginInstallerRequest $request, Server $server): array
    {
        $query = trim(strip_tags($request->input('query', '')));
        $page = (int) $request->input('page', 1);
        $size = (int) $request->input('size', 10);

        if (empty($query)) {
            $uri = 'https://api.spiget.org/v2/resources?size=' . $size . '&page=' . $page;
        } else {
            $uri = 'https://api.spiget.org/v2/search/resources/' . urlencode($query) . '?size=' . $size . '&page=' . $page;
        }

        $client = new Client();
        try {
            $response = $client->request('GET', $uri, [
                'headers' => [
                    'User-Agent' => 'pterodactyl-plugin-manager/1.x',
                ]
            ]);
        } catch (GuzzleException $e) {
            throw new DisplayException('Failed to search in plugins.');
        }

        $pages = [];
        $currentPage = (int) $response->getHeaders()['X-Page-Index'][0];
        $maxPage = (int) $response->getHeaders()['X-Page-Count'][0];
        $startPage = $currentPage - 2;

        $plugins = json_decode($response->getBody(), true);
        $installedPlugins = DB::table('plugins')->where('server_id', '=', $server->id)->get();

        foreach ($plugins as $key => $plugin) {
            $plugins[$key]['installed'] = 0;

            foreach ($installedPlugins as $installedPlugin) {
                if ($plugin['id'] == $installedPlugin->plugin_id) {
                    $plugins[$key]['installed'] = 1;
                }
            }
        }

        for ($i = $startPage; $i < $startPage + 5; $i++) {
            if ($startPage < 1) {
                $startPage++;
                continue;
            }

            if ($i > $maxPage) {
                continue;
            }

            array_push($pages, $i);
        }

        return [
            'success' => true,
            'data' => [
                'plugins' => $plugins,
                'total' => (int) $response->getHeaders()['X-Total'][0],
                'maxPage' => $maxPage,
                'page' => $currentPage,
                'pages' => $pages,
            ],
        ];
    }

    /**
     * @param PluginInstallerRequest $request
     * @param Server $server
     * @return array
     * @throws DisplayException
     */
    public function install(PluginInstallerRequest $request, Server $server): array
    {
        $pluginId = trim(strip_tags($request->input('pluginId', 0)));

        $plugin = DB::table('plugins')->where('server_id', '=', $server->id)->where('plugin_id', '=', $pluginId)->get();
        if (count($plugin) > 0) {
            throw new DisplayException('This plugin is already installed.');
        }

        $client = new Client();
        try {
            $response = $client->request('GET', sprintf('https://api.spiget.org/v2/resources/%s', $pluginId), [
                'headers' => [
                    'User-Agent' => 'pterodactyl-plugin-manager/1.x',
                ]
            ]);
        } catch (GuzzleException $e) {
            throw new DisplayException('Failed to get plugins details.');
        }

        try {
            $body = json_decode($response->getBody(), true);
            $filename = explode('.', explode('/', $body['file']['url'])[1])[0];
        } catch (\Exception $e) {
            $filename = $pluginId;
        }

        try {
            $install = $this->daemonMinecraftPluginRepository->setServer($server)->install(sprintf('https://api.spiget.org/v2/resources/%s/download', $pluginId), $filename . '.jar');
        } catch (DaemonConnectionException $e) {
            throw new DisplayException('Failed to install the plugin.');
        }

        if (json_decode($install->getBody())->success != true) {
            throw new DisplayException('Failed to install the plugin.');
        }

        DB::table('plugins')->insert([
            'server_id' => $server->id,
            'plugin_id' => $pluginId,
            'plugin_name' => json_decode($install->getBody())->filename,
        ]);

        return [
            'success' => true,
            'data' => [],
        ];
    }

    /**
     * @param PluginInstallerRequest $request
     * @param Server $server
     * @return array
     * @throws %%__USER__%% DisplayException
     */
    public function uninstall(PluginInstallerRequest $request, Server $server): array
    {
        $pluginId = trim(strip_tags($request->input('pluginId', 0)));

        $plugin = DB::table('plugins')->where('server_id', '=', $server->id)->where('plugin_id', '=', $pluginId)->get();
        if (count($plugin) < 1) {
            throw new DisplayException('This plugin isn\'t installed.');
        }

        try {
            $this->daemonFileRepository->setServer($server)->deleteFiles('/plugins/', [$plugin[0]->plugin_name]);
        } catch (DaemonConnectionException $e) {
            throw new DisplayException('Failed to uninstall the plugin.');
        }

        DB::table('plugins')->where('server_id', '=', $server->id)->where('plugin_id', '=', $pluginId)->delete();

        return [
            'success' => true,
            'data' => [],
        ];
    }
}
