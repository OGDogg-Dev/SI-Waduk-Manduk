import { Head } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import Card from '@/components/common/Card';
import EmptyState from '@/components/common/EmptyState';
import ErrorState from '@/components/common/ErrorState';
import LoadingState from '@/components/common/LoadingState';
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
import type { Merchant } from '@/lib/types';
import PublicLayout from '@/layouts/PublicLayout';

const categoryOptions = [
    { value: '', label: 'Semua kategori' },
    { value: 'KULINER', label: 'Kuliner' },
    { value: 'SOUVENIR', label: 'Souvenir' },
    { value: 'SEWA_PERALATAN', label: 'Sewa Peralatan' },
    { value: 'LAINNYA', label: 'Lainnya' },
];

const MerchantsIndex = () => {
    const [category, setCategory] = useState('');
    const [query, setQuery] = useState('');

    const paginated = usePaginated<Merchant, { category?: string; q?: string }>({
        queryKey: 'merchants',
        path: '/merchants',
        params: {
            category: category || undefined,
            q: query || undefined,
        },
    });

    const items = paginated.items;

    const grouped = useMemo(() => {
        return items.reduce<Record<string, Merchant[]>>((acc, merchant) => {
            const key = merchant.category;
            acc[key] = acc[key] ? [...acc[key], merchant] : [merchant];
            return acc;
        }, {});
    }, [items]);

    return (
        <PublicLayout>
            <Head title="UMKM Mitra">
                <meta name="description" content="Temukan pedagang kuliner, souvenir, dan jasa pendukung wisata Waduk Manduk." />
            </Head>

            <Section title="UMKM dan Mitra" description="Dukung pelaku usaha lokal yang siap melayani kebutuhan Anda selama berwisata.">
                <div className="grid gap-4 rounded-2xl border border-border/60 bg-background/60 p-4 md:grid-cols-3">
                    <div className="md:col-span-2">
                        <label htmlFor="search" className="text-sm font-medium">
                            Cari UMKM
                        </label>
                        <Input
                            id="search"
                            placeholder="Contoh: Sate, souvenir"
                            value={query}
                            onChange={(event) => setQuery(event.target.value)}
                            className="mt-2"
                        />
                    </div>
                    <div>
                        <label htmlFor="category" className="text-sm font-medium">
                            Kategori
                        </label>
                        <Select value={category} onValueChange={setCategory}>
                            <SelectTrigger id="category" className="mt-2">
                                <SelectValue placeholder="Semua kategori" />
                            </SelectTrigger>
                            <SelectContent>
                                {categoryOptions.map((option) => (
                                    <SelectItem key={option.value} value={option.value}>
                                        {option.label}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                {paginated.isLoading && <LoadingState lines={6} />}
                {paginated.isError && <ErrorState onRetry={() => paginated.refetch()} />}

                {!paginated.isLoading && items.length === 0 && (
                    <EmptyState
                        title="UMKM tidak ditemukan"
                        description="Coba ubah kata kunci pencarian atau pilih kategori lain."
                    />
                )}

                {Object.keys(grouped).length > 0 && (
                    <div className="space-y-8">
                        {Object.entries(grouped).map(([key, merchants]) => (
                            <div key={key} className="space-y-4">
                                <h3 className="text-2xl font-semibold">{key.replace('_', ' ')}</h3>
                                <div className="grid gap-6 md:grid-cols-3">
                                    {merchants.map((merchant) => (
                                        <Card key={merchant.id} className="h-full">
                                            <div className="flex h-full flex-col gap-4">
                                                <div className="flex items-center justify-between">
                                                    <h4 className="text-lg font-semibold">{merchant.name}</h4>
                                                    {merchant.is_verified && (
                                                        <Badge className="rounded-full bg-emerald-500 text-white">
                                                            Terverifikasi
                                                        </Badge>
                                                    )}
                                                </div>
                                                <p className="text-sm text-muted-foreground line-clamp-4">
                                                    {merchant.location ?? 'Lokasi tersedia di area wisata.'}
                                                </p>
                                                <div className="mt-auto flex flex-wrap gap-2 text-sm text-muted-foreground">
                                                    {merchant.phone && <span>Telp: {merchant.phone}</span>}
                                                    {merchant.whatsapp && <span>WA: {merchant.whatsapp}</span>}
                                                </div>
                                                {merchant.whatsapp && (
                                                    <Button asChild variant="secondary" className="rounded-full">
                                                        <a
                                                            href={`https://wa.me/${merchant.whatsapp.replace(/[^0-9]/g, '')}`}
                                                            target="_blank"
                                                            rel="noreferrer"
                                                        >
                                                            Chat WhatsApp
                                                        </a>
                                                    </Button>
                                                )}
                                            </div>
                                        </Card>
                                    ))}
                                </div>
                            </div>
                        ))}
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

export default MerchantsIndex;
