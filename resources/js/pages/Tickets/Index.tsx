import { Head } from '@inertiajs/react';
import { useQuery } from '@tanstack/react-query';
import Card from '@/components/common/Card';
import EmptyState from '@/components/common/EmptyState';
import ErrorState from '@/components/common/ErrorState';
import LoadingState from '@/components/common/LoadingState';
import Section from '@/components/common/Section';
import TicketTable from '@/components/common/TicketTable';
import { Button } from '@/components/ui/button';
import { fetcher } from '@/lib/api';
import type { TicketType } from '@/lib/types';
import PublicLayout from '@/layouts/PublicLayout';

const TicketsIndex = () => {
    const ticketQuery = useQuery({
        queryKey: ['ticket-types'],
        queryFn: () => fetcher<TicketType[]>('/ticket-types'),
    });

    const tickets = ticketQuery.data ?? [];

    return (
        <PublicLayout>
            <Head title="Harga Tiket">
                <meta name="description" content="Daftar tiket masuk dan layanan tambahan di Waduk Manduk." />
            </Head>

            <Section
                title="Informasi Tiket"
                description="Harga dapat berubah sewaktu-waktu. Hubungi loket untuk informasi terbaru."
            >
                {ticketQuery.isLoading && <LoadingState lines={5} />}
                {ticketQuery.isError && <ErrorState onRetry={() => ticketQuery.refetch()} />}

                {!ticketQuery.isLoading && tickets.length === 0 && (
                    <EmptyState title="Belum ada data tiket" description="Data tiket akan segera diumumkan." />
                )}

                {tickets.length > 0 && (
                    <Card>
                        <TicketTable tickets={tickets} />
                        <p className="text-xs text-muted-foreground">
                            *Harga sudah termasuk asuransi wisata. Untuk rombongan &gt; 30 orang, hubungi petugas loket untuk
                            paket khusus.
                        </p>
                        <Button asChild className="w-fit rounded-full">
                            <a href="https://wa.me/6281234567890" target="_blank" rel="noreferrer">
                                Hubungi Loket
                            </a>
                        </Button>
                    </Card>
                )}
            </Section>
        </PublicLayout>
    );
};

export default TicketsIndex;
