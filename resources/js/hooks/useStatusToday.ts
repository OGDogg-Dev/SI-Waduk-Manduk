import { useQuery } from '@tanstack/react-query';
import { fetcher } from '@/lib/api';
import type { StatusToday } from '@/lib/types';

export interface UseStatusTodayOptions {
    attractionId?: number;
    enabled?: boolean;
}

/**
 * Hook pembungkus untuk mengambil status operasional hari ini.
 */
export const useStatusToday = (options: UseStatusTodayOptions = {}) => {
    const { attractionId, enabled = true } = options;

    return useQuery<StatusToday, Error>({
        queryKey: ['status-today', attractionId ?? null],
        queryFn: async () =>
            fetcher<StatusToday>('/status-today', {
                attraction_id: attractionId,
            }),
        enabled,
    });
};
