import { useInfiniteQuery, type QueryKey } from '@tanstack/react-query';
import { fetcher } from '@/lib/api';
import type { PaginatedResponse } from '@/lib/types';

type QueryKeyInput = QueryKey | string;

export interface UsePaginatedOptions<TParams extends Record<string, unknown>> {
    queryKey: QueryKeyInput;
    path: string;
    params?: TParams;
    enabled?: boolean;
}

/**
 * Hook generik untuk mengambil data paginasi API v1 dengan React Query.
 */
export const usePaginated = <
    TData,
    TParams extends Record<string, unknown> = Record<string, unknown>
>(options: UsePaginatedOptions<TParams>) => {
    const { queryKey, path, params, enabled = true } = options;

    const keyArray = Array.isArray(queryKey) ? queryKey : [queryKey];

    const query = useInfiniteQuery<PaginatedResponse<TData>>({
        queryKey: [...keyArray, params],
        queryFn: ({ pageParam = 1 }) =>
            fetcher<PaginatedResponse<TData>>(path, {
                ...params,
                page: pageParam,
            }),
        getNextPageParam: (lastPage) => {
            if (!lastPage.meta) {
                return undefined;
            }

            const { current_page, last_page } = lastPage.meta;
            return current_page < last_page ? current_page + 1 : undefined;
        },
        initialPageParam: 1,
        enabled,
    });

    const items = query.data?.pages.flatMap((page) => page.data) ?? [];

    return {
        ...query,
        items,
    };
};
