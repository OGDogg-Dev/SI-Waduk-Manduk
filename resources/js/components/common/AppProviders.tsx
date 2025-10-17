import type { PropsWithChildren } from 'react';
import { useState } from 'react';
import { HydrationBoundary, QueryClientProvider, type DehydratedState } from '@tanstack/react-query';
import { Toaster } from 'react-hot-toast';
import { createQueryClient } from '@/lib/queryClient';

export interface AppProvidersProps extends PropsWithChildren {
    dehydratedState?: DehydratedState;
}

/**
 * Pembungkus global untuk menyediakan QueryClient dan toaster notifikasi.
 */
export const AppProviders = ({ children, dehydratedState }: AppProvidersProps) => {
    const [queryClient] = useState(() => createQueryClient());

    return (
        <QueryClientProvider client={queryClient}>
            <HydrationBoundary state={dehydratedState}>{children}</HydrationBoundary>
            <Toaster position="top-right" toastOptions={{ duration: 4000 }} />
        </QueryClientProvider>
    );
};

export default AppProviders;
