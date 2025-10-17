import type { PropsWithChildren, ReactNode } from 'react';
import { cn } from '@/lib/utils';

export interface SectionProps extends PropsWithChildren {
    title?: ReactNode;
    description?: ReactNode;
    className?: string;
    headerClassName?: string;
    contentClassName?: string;
}

/**
 * Komponen section standar dengan heading dan konten fleksibel.
 */
export const Section = ({
    title,
    description,
    className,
    headerClassName,
    contentClassName,
    children,
}: SectionProps) => (
    <section className={cn('mx-auto w-full max-w-6xl px-4 py-10 md:px-6', className)}>
        {(title || description) && (
            <header className={cn('mb-8 flex flex-col gap-2 text-center md:text-left', headerClassName)}>
                {title && <h2 className="text-3xl font-bold tracking-tight md:text-4xl">{title}</h2>}
                {description && (
                    <p className="text-base text-muted-foreground md:text-lg">{description}</p>
                )}
            </header>
        )}
        <div className={cn('space-y-6', contentClassName)}>{children}</div>
    </section>
);

export default Section;
