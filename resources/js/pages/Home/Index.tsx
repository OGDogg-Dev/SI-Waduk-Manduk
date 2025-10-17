import { Head, Link } from '@inertiajs/react';
import { useMemo } from 'react';
import { useQueries, useQuery } from '@tanstack/react-query';
import dayjs from 'dayjs';
import 'dayjs/locale/id';
import AnnouncementBanner from '@/components/common/AnnouncementBanner';
import Card from '@/components/common/Card';
import EmptyState from '@/components/common/EmptyState';
import ErrorState from '@/components/common/ErrorState';
import LoadingState from '@/components/common/LoadingState';
import MapView from '@/components/common/MapView';
import Section from '@/components/common/Section';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { fetcher } from '@/lib/api';
import type { Announcement, Attraction, PaginatedResponse } from '@/lib/types';
import { formatCurrency } from '@/lib/utils';
import PublicLayout from '@/layouts/PublicLayout';
import { useStatusToday } from '@/hooks/useStatusToday';

const highlightTypes = ['WAHANA', 'SPOT'] as const;

dayjs.locale('id');

const HomePage = () => {
    const statusQuery = useStatusToday();

    const highlightQueries = useQueries({
        queries: highlightTypes.map((type) => ({
            queryKey: ['attractions', 'highlight', type],
            queryFn: () => fetcher<PaginatedResponse<Attraction>>('/attractions', { per_page: 3, type }),
        })),
    });

    const announcementQuery = useQuery({
        queryKey: ['announcements', 'latest'],
        queryFn: () => fetcher<PaginatedResponse<Announcement>>('/announcements', { per_page: 1 }),
    });

    const highlightAttractions = useMemo(() => {
        const map = new Map<number, Attraction>();
        highlightQueries.forEach((query) => {
            query.data?.data?.forEach((item) => {
                map.set(item.id, item);
            });
        });
        return Array.from(map.values()).slice(0, 3);
    }, highlightQueries.map((query) => query.data));

    const highlightMarkers = useMemo(() => {
        return highlightAttractions
            .filter((item) => item.latitude && item.longitude)
            .map((item) => ({
                position: [item.latitude as number, item.longitude as number] as [number, number],
                label: item.name,
            }));
    }, [highlightAttractions]);

    const galleryImages = useMemo(() => {
        return highlightAttractions
            .flatMap((attraction) => attraction.images ?? [])
            .slice(0, 6)
            .map((image) => ({
                id: `${image.id}-${image.url}`,
                url: image.url,
                alt: image.name ?? 'Atraksi Waduk Manduk',
            }));
    }, [highlightAttractions]);

    const latestAnnouncement = announcementQuery.data?.data?.[0] ?? null;

    const highlightIsLoading = highlightQueries.some((query) => query.isLoading);
    const highlightError = highlightQueries.find((query) => query.isError);

    return (
        <PublicLayout>
            <Head title="Beranda">
                <meta name="description" content="Informasi resmi wisata Waduk Manduk dan layanan hari ini." />
                <meta property="og:title" content="Waduk Manduk - Wisata Air Ponorogo" />
                <meta property="og:description" content="Temukan atraksi, tiket, dan status operasional Waduk Manduk secara terkini." />
            </Head>

            <section className="relative overflow-hidden bg-gradient-to-br from-primary/10 via-primary/5 to-background">
                <div className="mx-auto flex w-full max-w-6xl flex-col gap-10 px-4 py-16 md:flex-row md:items-center md:px-6">
                    <div className="flex-1 space-y-6">
                        <Badge className="w-fit rounded-full bg-primary/90">Selamat Datang di Waduk Manduk</Badge>
                        <h1 className="text-4xl font-bold leading-tight md:text-5xl">
                            Nikmati Panorama Senja dan Wisata Keluarga di Waduk Manduk
                        </h1>
                        <p className="text-base text-muted-foreground md:text-lg">
                            Temukan pengalaman wisata air terbaik di Ponorogo: wahana perahu bebek, spot foto, kuliner UMKM, dan agenda event seru.
                        </p>
                        <div className="flex flex-wrap gap-3">
                            <Button asChild size="lg" className="rounded-full">
                                <Link href="/tickets" prefetch>
                                    Cek Harga Tiket
                                </Link>
                            </Button>
                            <Button asChild variant="outline" size="lg" className="rounded-full">
                                <a href="https://maps.app.goo.gl/Z2pXSeYprwkq4bgE7" target="_blank" rel="noreferrer">
                                    Lihat Rute
                                </a>
                            </Button>
                        </div>
                    </div>
                    <div className="flex-1">
                        <MapView className="h-72" markers={highlightMarkers} />
                    </div>
                </div>
            </section>

            <Section title="Status Operasional Hari Ini" description="Pantau jadwal buka dan penutupan area wisata secara real-time.">
                {statusQuery.isLoading && <LoadingState />}
                {statusQuery.isError && <ErrorState onRetry={() => statusQuery.refetch()} />}
                {statusQuery.data && (
                    <Card className="bg-background/60">
                        <div className="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                            <div className="space-y-3">
                                <Badge
                                    className={`w-fit rounded-full px-4 py-1 text-sm font-semibold ${
                                        statusQuery.data.open_now ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white'
                                    }`}
                                >
                                    {statusQuery.data.open_now ? 'Sedang Buka' : 'Tutup Sementara'}
                                </Badge>
                                <div className="text-sm text-muted-foreground">
                                    <p>
                                        Jam operasional: <span className="font-medium text-foreground">{statusQuery.data.open_time ?? '-'} - {statusQuery.data.close_time ?? '-'}</span>
                                    </p>
                                    <p className="text-xs">
                                        Data diperbarui {dayjs().format('DD MMM YYYY HH:mm')}.
                                    </p>
                                </div>
                            </div>
                            <div className="space-y-2 text-sm text-muted-foreground">
                                {statusQuery.data.closures_today.length > 0 ? (
                                    statusQuery.data.closures_today.map((closure) => (
                                        <div key={closure.id} className="rounded-xl bg-destructive/10 p-3">
                                            <p className="font-medium text-destructive">
                                                {closure.attraction_name ?? 'Area tertentu'} ditutup sementara
                                            </p>
                                            <p className="text-xs">
                                                {dayjs(closure.start_at).format('DD MMM HH:mm')} -
                                                {closure.end_at ? ` ${dayjs(closure.end_at).format('DD MMM HH:mm')}` : ' hingga pemberitahuan'}
                                            </p>
                                            <p className="text-xs text-muted-foreground">Alasan: {closure.reason}</p>
                                        </div>
                                    ))
                                ) : (
                                    <p className="rounded-xl bg-emerald-500/10 p-3 text-emerald-600 dark:text-emerald-300">
                                        Tidak ada penutupan area hari ini. Selamat berkunjung!
                                    </p>
                                )}
                            </div>
                        </div>
                    </Card>
                )}
            </Section>

            <Section title="Destinasi Unggulan" description="Tiga rekomendasi atraksi terbaik untuk memulai petualangan Anda.">
                {highlightIsLoading && <LoadingState />}
                {highlightError && (
                    <ErrorState onRetry={() => highlightQueries.forEach((query) => query.refetch())} message="Gagal memuat daftar atraksi." />
                )}
                {!highlightIsLoading && highlightAttractions.length === 0 && (
                    <EmptyState title="Belum ada atraksi" description="Data atraksi akan segera tersedia." />
                )}
                {highlightAttractions.length > 0 && (
                    <div className="grid gap-6 md:grid-cols-3">
                        {highlightAttractions.map((attraction) => (
                            <Card key={attraction.id} className="h-full">
                                <div className="flex flex-col gap-4">
                                    <div className="space-y-2">
                                        <Badge variant="outline" className="rounded-full">
                                            {attraction.type}
                                        </Badge>
                                        <h3 className="text-xl font-semibold">{attraction.name}</h3>
                                        <p className="text-sm text-muted-foreground line-clamp-3">
                                            {attraction.excerpt ?? attraction.description}
                                        </p>
                                    </div>
                                    <p className="text-sm font-medium text-primary">
                                        Harga dasar: {formatCurrency(attraction.base_price)}
                                    </p>
                                    <Button asChild variant="secondary" className="mt-auto rounded-full">
                                        <Link href={`/attractions/${attraction.slug}`} prefetch>
                                            Lihat Detail
                                        </Link>
                                    </Button>
                                </div>
                            </Card>
                        ))}
                    </div>
                )}

                {highlightMarkers.length > 0 && (
                    <div className="rounded-2xl border border-border/60 bg-background/60 p-4">
                        <h3 className="text-lg font-semibold">Lokasi Destinasi</h3>
                        <MapView className="mt-4 h-72" markers={highlightMarkers} />
                    </div>
                )}
            </Section>

            {galleryImages.length > 0 && (
                <Section title="Galeri Senja Waduk Manduk" description="Suasana terbaik dari destinasi unggulan kami.">
                    <div className="grid gap-4 md:grid-cols-3">
                        {galleryImages.map((image) => (
                            <img
                                key={image.id}
                                src={image.url}
                                alt={image.alt}
                                loading="lazy"
                                className="h-48 w-full rounded-2xl object-cover shadow-sm"
                            />
                        ))}
                    </div>
                </Section>
            )}

            {latestAnnouncement && (
                <Section title="Pengumuman Terbaru">
                    <AnnouncementBanner announcement={latestAnnouncement} />
                </Section>
            )}
        </PublicLayout>
    );
};

export default HomePage;
