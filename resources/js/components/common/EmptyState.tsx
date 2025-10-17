import type { PropsWithChildren, ReactNode } from 'react';
import { cn } from '@/lib/utils';

export interface EmptyStateProps extends PropsWithChildren {
    icon?: ReactNode;
    title: string;
    description?: string;
    className?: string;
}

export const EmptyState = ({ icon, title, description, className, children }: EmptyStateProps) => (
    <div
        className={cn(
            'flex flex-col items-center justify-center rounded-2xl border border-dashed border-border/60 bg-muted/30 p-10 text-center',
            className,
        )}
    >
        {icon && <div className="mb-4 text-muted-foreground">{icon}</div>}
        <h3 className="text-lg font-semibold">{title}</h3>
        {description && <p className="mt-2 max-w-lg text-sm text-muted-foreground">{description}</p>}
        {children && <div className="mt-4 flex flex-wrap justify-center gap-3">{children}</div>}
    </div>
);

export default EmptyState;
