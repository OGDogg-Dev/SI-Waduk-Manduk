import { Suspense, lazy } from 'react';
import type { MapViewProps, MapMarker } from './MapViewLeaflet';
import { cn } from '@/lib/utils';

export type { MapViewProps, MapMarker } from './MapViewLeaflet';

const LazyLeafletMap = lazy(async () => ({
    default: (await import('./MapViewLeaflet')).default,
}));

/**
 * Pembungkus MapView agar kompatibel dengan rendering server (leaflet hanya dijalankan di client).
 */
export const MapView = (props: MapViewProps) => {
    if (typeof window === 'undefined') {
        return (
            <div className={cn('flex h-64 w-full items-center justify-center rounded-2xl bg-muted', props.className)}>
                <span className="text-sm text-muted-foreground">Peta akan tampil saat halaman dimuat.</span>
            </div>
        );
    }

    return (
        <Suspense
            fallback={
                <div className={cn('flex h-64 w-full items-center justify-center rounded-2xl bg-muted', props.className)}>
                    <span className="text-sm text-muted-foreground">Memuat peta...</span>
                </div>
            }
        >
            <LazyLeafletMap {...props} />
        </Suspense>
    );
};

export default MapView;
