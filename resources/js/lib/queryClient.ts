import { QueryClient } from '@tanstack/react-query';

/**
 * Membuat instance QueryClient dengan konfigurasi default untuk aplikasi publik.
 */
export const createQueryClient = () =>
    new QueryClient({
        defaultOptions: {
            queries: {
                staleTime: 1000 * 60 * 2, // 2 menit
                gcTime: 1000 * 60 * 10,
                refetchOnWindowFocus: false,
                retry: 1,
            },
            mutations: {
                retry: 0,
            },
        },
    });

export type AppQueryClient = ReturnType<typeof createQueryClient>;
