import { Head, Link } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import Card from '@/components/common/Card';
import EmptyState from '@/components/common/EmptyState';
import ErrorState from '@/components/common/ErrorState';
import LoadingState from '@/components/common/LoadingState';
import MapView from '@/components/common/MapView';
import Section from '@/components/common/Section';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { usePaginated } from '@/hooks/usePaginated';
import type { Attraction } from '@/lib/types';
import { formatCurrency } from '@/lib/utils';
import PublicLayout from '@/layouts/PublicLayout';

const typeOptions = [
    { value: '', label: 'Semua tipe' },
    { value: 'WAHANA', label: 'Wahana' },
    { value: 'SPOT', label: 'Spot Foto' },
    { value: 'GENERAL', label: 'Umum' },
];

const AttractionsIndex = () => {
    const [search, setSearch] = useState('');
    const [type, setType] = useState('');

    const paginated = usePaginated<Attraction, { type?: string; q?: string }>({
        queryKey: 'attractions',
        path: '/attractions',
        params: {
            type: type || undefined,
            q: search || undefined,
        },
    });

    const markers = useMemo(
        () =>
            paginated.items
                .filter((item) => item.latitude && item.longitude)
                .map((item) => ({
                    position: [item.latitude as number, item.longitude as number] as [number, number],
                    label: item.name,
                })),
        [paginated.items],
    );

    return (
        <PublicLayout>
            <Head title="Daftar Atraksi">
                <meta name="description" content="Jelajahi wahana, spot foto, dan fasilitas utama di Waduk Manduk." />
            </Head>

            <Section
                title="Atraksi Wisata"
                description="Gunakan pencarian dan filter tipe untuk menemukan destinasi sesuai minat Anda."
            >
                <div className="grid gap-4 rounded-2xl border border-border/60 bg-background/60 p-4 md:grid-cols-4">
                    <div className="md:col-span-2">
                        <label className="text-sm font-medium" htmlFor="search">
                            Cari atraksi
                        </label>
                        <Input
                            id="search"
                            placeholder="Contoh: Perahu Bebek"
                            value={search}
                            onChange={(event) => setSearch(event.target.value)}
                            className="mt-2"
                        />
                    </div>
                    <div>
                        <label className="text-sm font-medium" htmlFor="type">
                            Tipe
                        </label>
                        <Select value={type} onValueChange={setType}>
                            <SelectTrigger id="type" className="mt-2">
                                <SelectValue placeholder="Semua tipe" />
                            </SelectTrigger>
                            <SelectContent>
                                {typeOptions.map((option) => (
                                    <SelectItem key={option.value} value={option.value}>
                                        {option.label}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>
                    <div className="flex items-end">
                        <Button
                            variant="ghost"
                            className="w-full rounded-full"
                            onClick={() => {
                                setSearch('');
                                setType('');
                            }}
                        >
                            Reset filter
                        </Button>
                    </div>
                </div>

                {paginated.isLoading && <LoadingState lines={6} />}
                {paginated.isError && <ErrorState onRetry={() => paginated.refetch()} />}

                {!paginated.isLoading && paginated.items.length === 0 && (
                    <EmptyState
                        title="Atraksi tidak ditemukan"
                        description="Coba ubah kata kunci pencarian atau filter tipe."
                    />
                )}

                {paginated.items.length > 0 && (
                    <div className="grid gap-6 md:grid-cols-3">
                        {paginated.items.map((attraction) => (
                            <Card key={attraction.id} className="h-full">
                                <div className="flex flex-col gap-4">
                                    <div className="flex items-center justify-between">
                                        <h3 className="text-xl font-semibold">{attraction.name}</h3>
                                        <Badge variant="outline" className="rounded-full">
                                            {attraction.type}
                                        </Badge>
                                    </div>
                                    <p className="text-sm text-muted-foreground line-clamp-4">
                                        {attraction.excerpt ?? attraction.description}
                                    </p>
                                    <p className="text-sm font-medium text-primary">
                                        Harga dasar: {formatCurrency(attraction.base_price)}
                                    </p>
                                    <Button asChild variant="secondary" className="mt-auto rounded-full">
                                        <Link href={`/attractions/${attraction.slug}`} prefetch>
                                            Lihat detail
                                        </Link>
                                    </Button>
                                </div>
                            </Card>
                        ))}
                    </div>
                )}

                {markers.length > 0 && (
                    <div className="mt-8">
                        <h3 className="text-lg font-semibold">Peta Lokasi Atraksi</h3>
                        <MapView className="mt-4 h-80" markers={markers} />
                    </div>
                )}

                {paginated.hasNextPage && (
                    <div className="flex justify-center">
                        <Button
                            onClick={() => paginated.fetchNextPage()}
                            disabled={paginated.isFetchingNextPage}
                            className="rounded-full"
                        >
                            {paginated.isFetchingNextPage ? 'Memuat...' : 'Muat lebih banyak'}
                        </Button>
                    </div>
                )}
            </Section>
        </PublicLayout>
    );
};

export default AttractionsIndex;
