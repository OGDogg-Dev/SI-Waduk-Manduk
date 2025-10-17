import dayjs from 'dayjs';
import 'dayjs/locale/id';
import { cn } from '@/lib/utils';
import type { Announcement, AnnouncementSeverity } from '@/lib/types';
import Card from './Card';

const severityStyle: Record<AnnouncementSeverity, string> = {
    INFO: 'bg-sky-500/10 text-sky-700 dark:text-sky-200',
    WARNING: 'bg-amber-500/10 text-amber-700 dark:text-amber-200',
    ALERT: 'bg-red-500/10 text-red-700 dark:text-red-200',
};

dayjs.locale('id');

export interface AnnouncementBannerProps {
    announcement: Announcement;
}

export const AnnouncementBanner = ({ announcement }: AnnouncementBannerProps) => (
    <Card className="border border-primary/40 bg-primary/5">
        <div className="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div className="space-y-2">
                <span
                    className={cn(
                        'inline-flex w-fit items-center rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wider',
                        severityStyle[announcement.severity],
                    )}
                >
                    {announcement.severity}
                </span>
                <h3 className="text-xl font-semibold">{announcement.title}</h3>
                <p className="text-sm text-muted-foreground">
                    {announcement.excerpt ?? announcement.content}
                </p>
            </div>
            <div className="text-sm text-muted-foreground md:text-right">
                {announcement.published_at && (
                    <p>Dipublikasikan {dayjs(announcement.published_at).format('DD MMM YYYY HH:mm')}</p>
                )}
                {announcement.expired_at && (
                    <p className="text-destructive">
                        Berlaku hingga {dayjs(announcement.expired_at).format('DD MMM YYYY HH:mm')}
                    </p>
                )}
            </div>
        </div>
    </Card>
);

export default AnnouncementBanner;
