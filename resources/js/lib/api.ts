import axios, { AxiosError } from 'axios';
import type { ApiErrorResponse } from './types';

/**
 * Kelas error khusus untuk membungkus respons API yang tidak berhasil.
 */
export class ApiClientError extends Error {
    public readonly status: number;
    public readonly payload: ApiErrorResponse;

    constructor(status: number, payload: ApiErrorResponse) {
        super(payload.message);
        this.name = 'ApiClientError';
        this.status = status;
        this.payload = payload;
    }
}

export const api = axios.create({
    baseURL: '/api/v1',
    timeout: 10_000,
    headers: {
        Accept: 'application/json',
    },
});

type ErrorData = Partial<ApiErrorResponse> | undefined;

type ErrorResponse = AxiosError<ErrorData>;

api.interceptors.response.use(
    (response) => response,
    (error: ErrorResponse) => {
        if (error.response) {
            const { status, data } = error.response;
            const payload: ApiErrorResponse = {
                message:
                    data?.message ??
                    'Terjadi kesalahan pada layanan Waduk Manduk. Silakan coba lagi.',
                errors: data?.errors ?? {},
            };
            return Promise.reject(new ApiClientError(status, payload));
        }

        if (error.request) {
            return Promise.reject(
                new ApiClientError(0, {
                    message:
                        'Tidak dapat menjangkau server. Periksa koneksi internet Anda.',
                }),
            );
        }

        return Promise.reject(
            new ApiClientError(0, {
                message: error.message,
            }),
        );
    },
);

export const fetcher = async <T>(url: string, params?: Record<string, unknown>) => {
    const response = await api.get<T>(url, { params });
    return response.data;
};

export default api;
