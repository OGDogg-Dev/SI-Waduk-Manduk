import { Head } from '@inertiajs/react';
import EmptyState from '@/components/common/EmptyState';
import AnnouncementBanner, { announcementSeverityStyle } from '@/components/common/AnnouncementBanner';
import ErrorState from '@/components/common/ErrorState';
import LoadingState from '@/components/common/LoadingState';
import Section from '@/components/common/Section';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { usePaginated } from '@/hooks/usePaginated';
import type { Announcement } from '@/lib/types';
import { cn } from '@/lib/utils';
import PublicLayout from '@/layouts/PublicLayout';

const AnnouncementsIndex = () => {
    const paginated = usePaginated<Announcement>({
        queryKey: 'announcements',
        path: '/announcements',
    });

    const alertAnnouncement = paginated.items.find((item) => item.severity === 'ALERT');
    const announcements = alertAnnouncement
        ? paginated.items.filter((item) => item.id !== alertAnnouncement.id)
        : paginated.items;

    return (
        <PublicLayout>
            <Head title="Pengumuman">
                <meta name="description" content="Pengumuman resmi terkait kondisi Waduk Manduk dan informasi penting pengunjung." />
            </Head>

            <Section title="Pengumuman Terbaru" description="Pantau kabar terkini dari pengelola Waduk Manduk.">
                {paginated.isLoading && <LoadingState lines={5} />}
                {paginated.isError && <ErrorState onRetry={() => paginated.refetch()} />}

                {!paginated.isLoading && paginated.items.length === 0 && (
                    <EmptyState
                        title="Belum ada pengumuman"
                        description="Kabar terbaru akan ditampilkan di sini begitu tersedia."
                    />
                )}

                {alertAnnouncement && <AnnouncementBanner announcement={alertAnnouncement} />}

                {announcements.length > 0 && (
                    <div className="grid gap-4">
                        {announcements.map((announcement) => (
                            <div
                                key={announcement.id}
                                className="rounded-2xl border border-border/60 bg-background/60 p-6 shadow-sm"
                            >
                                <div className="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                    <div className="space-y-2">
                                        <Badge
                                            className={cn(
                                                'w-fit rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide',
                                                announcementSeverityStyle[announcement.severity],
                                            )}
                                        >
                                            {announcement.severity}
                                        </Badge>
                                        <h3 className="text-xl font-semibold">{announcement.title}</h3>
                                        <p className="text-sm text-muted-foreground line-clamp-3">
                                            {announcement.excerpt ?? announcement.content}
                                        </p>
                                    </div>
                                    <div className="text-sm text-muted-foreground md:text-right">
                                        {announcement.published_at && <p>Publikasi: {new Date(announcement.published_at).toLocaleString('id-ID')}</p>}
                                        {announcement.expired_at && (
                                            <p className="text-destructive">
                                                Berlaku hingga: {new Date(announcement.expired_at).toLocaleString('id-ID')}
                                            </p>
                                        )}
                                    </div>
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

export default AnnouncementsIndex;
