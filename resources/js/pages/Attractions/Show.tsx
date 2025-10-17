import { Head, Link } from '@inertiajs/react';
import { useQuery } from '@tanstack/react-query';
import dayjs from 'dayjs';
import 'dayjs/locale/id';
import Card from '@/components/common/Card';
import EmptyState from '@/components/common/EmptyState';
import ErrorState from '@/components/common/ErrorState';
import LoadingState from '@/components/common/LoadingState';
import MapView from '@/components/common/MapView';
import Section from '@/components/common/Section';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { fetcher } from '@/lib/api';
import type { Attraction, Facility, TicketType } from '@/lib/types';
import { formatCurrency } from '@/lib/utils';
import PublicLayout from '@/layouts/PublicLayout';
import { useStatusToday } from '@/hooks/useStatusToday';

interface AttractionDetail extends Attraction {
    facilities?: Facility[];
    ticket_types?: TicketType[];
}

dayjs.locale('id');

interface AttractionShowProps {
    slug: string;
}

const AttractionShow = ({ slug }: AttractionShowProps) => {
    const attractionQuery = useQuery({
        queryKey: ['attractions', slug],
        queryFn: () => fetcher<AttractionDetail>(`/attractions/${slug}`),
    });

    const statusQuery = useStatusToday({
        attractionId: attractionQuery.data?.id,
        enabled: Boolean(attractionQuery.data?.id),
    });

    const attraction = attractionQuery.data;
    const status = statusQuery.data;
    const pageTitle = attraction ? attraction.name : 'Detail Atraksi';
    const pageDescription =
        attraction
            ? `Informasi lengkap ${attraction.name} di Waduk Manduk termasuk jadwal buka dan lokasi.`
            : 'Detail atraksi Waduk Manduk';

    return (
        <PublicLayout>
            <Head title={pageTitle}>
                <meta name="description" content={pageDescription} />
                {attraction?.images?.[0]?.url ? (
                    <meta property="og:image" content={attraction.images[0].url} />
                ) : null}
            </Head>

            <Section>
                <nav className="mb-6 text-sm text-muted-foreground" aria-label="Breadcrumb">
                    <ol className="flex flex-wrap items-center gap-2">
                        <li>
                            <Link href="/" prefetch className="text-foreground transition hover:text-primary">
                                Beranda
                            </Link>
                        </li>
                        <li aria-hidden="true">/</li>
                        <li>
                            <Link href="/attractions" prefetch className="text-foreground transition hover:text-primary">
                                Atraksi
                            </Link>
                        </li>
                        <li aria-hidden="true">/</li>
                        <li className="text-foreground">{attraction?.name ?? 'Detail'}</li>
                    </ol>
                </nav>

                {attractionQuery.isLoading && <LoadingState lines={10} />}
                {attractionQuery.isError && <ErrorState onRetry={() => attractionQuery.refetch()} />}

                {attraction && (
                    <div className="space-y-12">
                        <div className="space-y-6">
                            <Badge variant="outline" className="rounded-full px-4 py-1">
                                {attraction.type}
                            </Badge>
                            <h1 className="text-4xl font-bold md:text-5xl">{attraction.name}</h1>
                            <p className="max-w-3xl text-base text-muted-foreground md:text-lg">
                                {attraction.description}
                            </p>
                            <div className="flex flex-wrap gap-3">
                                <Button asChild className="rounded-full">
                                    <a
                                        href={`https://www.google.com/maps/dir/?api=1&destination=${
                                            attraction.latitude ?? ''
                                        },${attraction.longitude ?? ''}`}
                                        target="_blank"
                                        rel="noreferrer"
                                    >
                                        Buka Rute
                                    </a>
                                </Button>
                                <Button asChild variant="outline" className="rounded-full">
                                    <Link href="/contact" prefetch>
                                        Hubungi Pengelola
                                    </Link>
                                </Button>
                            </div>
                        </div>

                        <div className="grid gap-6 md:grid-cols-2">
                            <Card title="Status Operasional" className="h-full">
                                {statusQuery.isLoading && <LoadingState />}
                                {statusQuery.isError && (
                                    <ErrorState
                                        message="Tidak dapat memuat status operasional."
                                        onRetry={() => statusQuery.refetch()}
                                    />
                                )}
                                {status && (
                                    <div className="space-y-4">
                                        <Badge
                                            className={`w-fit rounded-full px-4 py-1 ${
                                                status.open_now
                                                    ? 'bg-emerald-500 text-white'
                                                    : 'bg-red-500 text-white'
                                            }`}
                                        >
                                            {status.open_now ? 'Sedang Buka' : 'Tutup' }
                                        </Badge>
                                        <p className="text-sm text-muted-foreground">
                                            Jam operasional: {status.open_time ?? '-'} - {status.close_time ?? '-'}
                                        </p>
                                        {status.closures_today.length > 0 ? (
                                            <div className="space-y-3">
                                                {status.closures_today.map((closure) => (
                                                    <div key={closure.id} className="rounded-xl bg-destructive/10 p-3">
                                                        <p className="text-sm font-semibold text-destructive">
                                                            {closure.attraction_name ?? 'Area ini'} ditutup sementara
                                                        </p>
                                                        <p className="text-xs text-muted-foreground">
                                                            {dayjs(closure.start_at).format('DD MMM HH:mm')} -
                                                            {closure.end_at
                                                                ? ` ${dayjs(closure.end_at).format('DD MMM HH:mm')}`
                                                                : ' hingga pemberitahuan'}
                                                        </p>
                                                        <p className="text-xs text-muted-foreground">Alasan: {closure.reason}</p>
                                                    </div>
                                                ))}
                                            </div>
                                        ) : (
                                            <p className="rounded-xl bg-emerald-500/10 p-3 text-sm text-emerald-600 dark:text-emerald-300">
                                                Tidak ada penutupan jadwal hari ini.
                                            </p>
                                        )}
                                    </div>
                                )}
                            </Card>
                            <Card title="Informasi Harga" className="h-full">
                                <p className="text-sm text-muted-foreground">
                                    Harga dasar atraksi ini adalah {formatCurrency(attraction.base_price)}. Untuk informasi lebih
                                    lanjut mengenai tiket terusan, silakan cek halaman tiket atau hubungi petugas.
                                </p>
                                {attraction.ticket_types && attraction.ticket_types.length > 0 && (
                                    <ul className="mt-4 space-y-2 text-sm">
                                        {attraction.ticket_types.map((ticket) => (
                                            <li key={ticket.id} className="flex items-center justify-between rounded-xl bg-muted/40 p-3">
                                                <span>{ticket.name}</span>
                                                <span className="font-medium text-primary">
                                                    {formatCurrency(ticket.weekday_price)}
                                                </span>
                                            </li>
                                        ))}
                                    </ul>
                                )}
                            </Card>
                        </div>

                        <div className="grid gap-6 md:grid-cols-2">
                            <Card title="Lokasi">
                                <MapView
                                    className="h-72"
                                    center={
                                        attraction.latitude && attraction.longitude
                                            ? [attraction.latitude, attraction.longitude]
                                            : undefined
                                    }
                                    markers={
                                        attraction.latitude && attraction.longitude
                                            ? [
                                                  {
                                                      position: [
                                                          attraction.latitude,
                                                          attraction.longitude,
                                                      ] as [number, number],
                                                      label: attraction.name,
                                                  },
                                              ]
                                            : undefined
                                    }
                                />
                            </Card>
                            <Card title="Fasilitas">
                                {attraction.facilities && attraction.facilities.length > 0 ? (
                                    <ul className="grid grid-cols-1 gap-3 text-sm md:grid-cols-2">
                                        {attraction.facilities.map((facility) => (
                                            <li key={facility.id} className="rounded-xl bg-muted/40 p-3">
                                                <p className="font-medium">{facility.name}</p>
                                                {facility.description && (
                                                    <p className="text-xs text-muted-foreground">{facility.description}</p>
                                                )}
                                            </li>
                                        ))}
                                    </ul>
                                ) : (
                                    <EmptyState
                                        title="Belum ada data fasilitas"
                                        description="Fasilitas terkait akan diperbarui oleh pengelola."
                                    />
                                )}
                            </Card>
                        </div>

                        {attraction.images && attraction.images.length > 0 && (
                            <div className="space-y-4">
                                <h2 className="text-2xl font-semibold">Galeri</h2>
                                <div className="grid gap-4 md:grid-cols-3">
                                    {attraction.images.map((image) => (
                                        <img
                                            key={image.id}
                                            src={image.url}
                                            alt={`${attraction.name} - ${image.name}`}
                                            loading="lazy"
                                            className="h-48 w-full rounded-2xl object-cover shadow-sm"
                                        />
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                )}
            </Section>
        </PublicLayout>
    );
};

export default AttractionShow;
