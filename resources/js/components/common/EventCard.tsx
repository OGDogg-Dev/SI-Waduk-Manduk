import dayjs from 'dayjs';
import 'dayjs/locale/id';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import Card from './Card';
import type { EventItem } from '@/lib/types';

export interface EventCardProps {
    event: EventItem;
}

dayjs.locale('id');

export const EventCard = ({ event }: EventCardProps) => {
    const start = dayjs(event.start_at).format('dddd, D MMMM YYYY HH:mm');
    const end = event.end_at ? dayjs(event.end_at).format('D MMMM YYYY HH:mm') : null;

    return (
        <Card className="h-full">
            <div className="flex flex-col gap-4">
                <div className="flex items-center justify-between gap-2">
                    <Badge variant={event.is_published ? 'default' : 'secondary'}>
                        {event.is_published ? 'Published' : 'Draft'}
                    </Badge>
                    {event.venue && <span className="text-sm text-muted-foreground">{event.venue}</span>}
                </div>
                <div>
                    <h3 className="text-xl font-semibold">{event.title}</h3>
                    <p className="mt-2 text-sm text-muted-foreground line-clamp-3">{event.excerpt ?? event.description}</p>
                </div>
                <div className="space-y-1 text-sm text-muted-foreground">
                    <p>
                        Mulai: <span className="font-medium text-foreground">{start}</span>
                    </p>
                    {end && (
                        <p>
                            Selesai: <span className="font-medium text-foreground">{end}</span>
                        </p>
                    )}
                </div>
                <Button variant="secondary" size="sm" className="self-start rounded-full">
                    Simpan Agenda
                </Button>
            </div>
        </Card>
    );
};

export default EventCard;
