import '../css/app.css';
import './styles/tailwind.css';

import { HydrationBoundary, QueryClientProvider, type DehydratedState } from '@tanstack/react-query';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { Toaster } from 'react-hot-toast';
import { createQueryClient } from './lib/queryClient';
import { initializeTheme } from './hooks/use-appearance';

initializeTheme();

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);
        const queryClient = createQueryClient();
        const dehydratedState =
            ((props.initialPage.props as Record<string, unknown>)?.dehydratedState as DehydratedState | undefined) ??
            undefined;

        root.render(
            <QueryClientProvider client={queryClient}>
                <HydrationBoundary state={dehydratedState}>
                    <App {...props} />
                </HydrationBoundary>
                <Toaster position="top-right" toastOptions={{ duration: 4000 }} />
            </QueryClientProvider>,
        );
    },
    progress: {
        color: '#4B5563',
    },
});
