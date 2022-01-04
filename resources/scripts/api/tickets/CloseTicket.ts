import http from '@/api/http';

export default (id: number): Promise<any> => {
    return new Promise<void>((resolve, reject) => {
        http.post(`/api/client/tickets/close/${id}`)
            .then(() => resolve())
            .catch(reject);
    });
};