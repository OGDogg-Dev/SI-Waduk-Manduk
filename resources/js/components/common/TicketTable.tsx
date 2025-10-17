import type { TicketType } from '@/lib/types';
import { formatCurrency } from '@/lib/utils';

export interface TicketTableProps {
    tickets: TicketType[];
}

export const TicketTable = ({ tickets }: TicketTableProps) => (
    <div className="overflow-hidden rounded-2xl border border-border/60">
        <table className="w-full border-collapse text-sm md:text-base">
            <thead className="bg-muted/60 text-left">
                <tr>
                    <th className="px-4 py-3 font-semibold">Jenis Tiket</th>
                    <th className="px-4 py-3 font-semibold">Hari Kerja</th>
                    <th className="px-4 py-3 font-semibold">Akhir Pekan</th>
                    <th className="px-4 py-3 font-semibold">Hari Libur</th>
                </tr>
            </thead>
            <tbody>
                {tickets.map((ticket) => (
                    <tr key={ticket.id} className="border-t border-border/40">
                        <td className="px-4 py-4 font-medium">{ticket.name}</td>
                        <td className="px-4 py-4 text-muted-foreground">
                            {formatCurrency(ticket.weekday_price)}
                        </td>
                        <td className="px-4 py-4 text-muted-foreground">
                            {formatCurrency(ticket.weekend_price)}
                        </td>
                        <td className="px-4 py-4 text-muted-foreground">
                            {formatCurrency(ticket.holiday_price)}
                        </td>
                    </tr>
                ))}
            </tbody>
        </table>
    </div>
);

export default TicketTable;
