import http from '@/api/http';

export default (uuid: string, pluginId: string): Promise<any> => {
    return new Promise((resolve, reject) => {
        http.post(`/api/client/servers/${uuid}/plugins/uninstall`, {
            pluginId,
        }).then((data) => {
            resolve(data.data || []);
        }).catch(reject);
    });
};
