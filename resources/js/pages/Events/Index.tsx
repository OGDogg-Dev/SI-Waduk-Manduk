import { Head } from '@inertiajs/react';
import { useState } from 'react';
import EmptyState from '@/components/common/EmptyState';
import ErrorState from '@/components/common/ErrorState';
import EventCard from '@/components/common/EventCard';
import LoadingState from '@/components/common/LoadingState';
import Section from '@/components/common/Section';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { usePaginated } from '@/hooks/usePaginated';
import type { EventItem } from '@/lib/types';
import PublicLayout from '@/layouts/PublicLayout';

const EventsIndex = () => {
    const [query, setQuery] = useState('');
    const [from, setFrom] = useState('');
    const [to, setTo] = useState('');

    const paginated = usePaginated<EventItem, { q?: string; date_from?: string; date_to?: string }>({
        queryKey: 'events',
        path: '/events',
        params: {
            q: query || undefined,
            date_from: from || undefined,
            date_to: to || undefined,
        },
    });

    return (
        <PublicLayout>
            <Head title="Agenda Event">
                <meta name="description" content="Daftar event terbaru yang berlangsung di Waduk Manduk." />
            </Head>

            <Section title="Event Mendatang" description="Filter berdasarkan tanggal atau kata kunci untuk rencana kunjungan Anda.">
                <div className="grid gap-4 rounded-2xl border border-border/60 bg-background/60 p-4 md:grid-cols-4">
                    <div className="md:col-span-2">
                        <label htmlFor="q" className="text-sm font-medium">
                            Cari event
                        </label>
                        <Input
                            id="q"
                            placeholder="Contoh: Festival"
                            value={query}
                            onChange={(event) => setQuery(event.target.value)}
                            className="mt-2"
                        />
                    </div>
                    <div>
                        <label htmlFor="from" className="text-sm font-medium">
                            Dari tanggal
                        </label>
                        <Input id="from" type="date" value={from} onChange={(event) => setFrom(event.target.value)} className="mt-2" />
                    </div>
                    <div>
                        <label htmlFor="to" className="text-sm font-medium">
                            Hingga tanggal
                        </label>
                        <Input id="to" type="date" value={to} onChange={(event) => setTo(event.target.value)} className="mt-2" />
                    </div>
                    <div className="md:col-span-4 flex items-center justify-end gap-3">
                        <Button
                            variant="ghost"
                            className="rounded-full"
                            onClick={() => {
                                setQuery('');
                                setFrom('');
                                setTo('');
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
                        title="Belum ada event mendatang"
                        description="Nantikan pengumuman event terbaru dari pengelola Waduk Manduk."
                    />
                )}

                {paginated.items.length > 0 && (
                    <div className="grid gap-6 md:grid-cols-3">
                        {paginated.items.map((event) => (
                            <EventCard key={event.id} event={event} />
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

export default EventsIndex;
