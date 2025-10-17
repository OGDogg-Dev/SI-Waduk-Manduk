import type { PropsWithChildren } from 'react';
import { Card as ShadcnCard, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { cn } from '@/lib/utils';

export interface CardProps extends PropsWithChildren {
    title?: string;
    description?: string;
    className?: string;
    contentClassName?: string;
}

/**
 * Pembungkus kartu standar dengan gaya konsisten.
 */
export const Card = ({
    title,
    description,
    className,
    contentClassName,
    children,
}: CardProps) => (
    <ShadcnCard className={cn('rounded-2xl border border-border/60 shadow-sm', className)}>
        {(title || description) && (
            <CardHeader className="space-y-1">
                {title && <CardTitle className="text-2xl font-semibold">{title}</CardTitle>}
                {description && (
                    <p className="text-sm text-muted-foreground">{description}</p>
                )}
            </CardHeader>
        )}
        <CardContent className={cn('flex flex-col gap-4', contentClassName)}>{children}</CardContent>
    </ShadcnCard>
);

export default Card;
