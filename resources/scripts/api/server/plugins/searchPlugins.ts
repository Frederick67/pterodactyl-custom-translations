import http from '@/api/http';
import { SearchPluginsResponse } from '@/components/server/plugins/PluginInstallerContainer';

export default async (uuid: string, size: number, page: number, query?: string): Promise<SearchPluginsResponse> => {
    const { data } = await http.post(`/api/client/servers/${uuid}/plugins/search`, {
        size, page, query,
    });

    return (data.data || []);
};
