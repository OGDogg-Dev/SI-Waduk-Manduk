import '../css/app.css';
import './styles/tailwind.css';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { type DehydratedState } from '@tanstack/react-query';
import AppProviders from '@/components/common/AppProviders';
import { initializeTheme } from './hooks/use-appearance';

initializeTheme();

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);
        const dehydratedState =
            ((props.initialPage.props as Record<string, unknown>)?.dehydratedState as DehydratedState | undefined) ??
            undefined;

        root.render(
            <AppProviders dehydratedState={dehydratedState}>
                <App {...props} />
            </AppProviders>,
        );
    },
    progress: {
        color: '#4B5563',
    },
});
