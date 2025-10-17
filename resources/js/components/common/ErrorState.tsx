import type { PropsWithChildren } from 'react';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';

export interface ErrorStateProps extends PropsWithChildren {
    title?: string;
    message?: string;
    onRetry?: () => void;
}

export const ErrorState = ({
    title = 'Terjadi kesalahan',
    message = 'Kami tidak dapat memuat data saat ini. Silakan coba beberapa saat lagi.',
    onRetry,
    children,
}: ErrorStateProps) => (
    <Alert variant="destructive" className="rounded-2xl border border-destructive/50">
        <AlertTitle className="font-semibold">{title}</AlertTitle>
        <AlertDescription className="space-y-4 text-sm">
            <p>{message}</p>
            {onRetry && (
                <Button size="sm" variant="secondary" onClick={onRetry} className="rounded-full">
                    Coba Lagi
                </Button>
            )}
            {children}
        </AlertDescription>
    </Alert>
);

export default ErrorState;
