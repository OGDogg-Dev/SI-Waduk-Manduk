import { Skeleton } from '@/components/ui/skeleton';

export interface LoadingStateProps {
    lines?: number;
}

export const LoadingState = ({ lines = 3 }: LoadingStateProps) => (
    <div className="space-y-3">
        {Array.from({ length: lines }).map((_, index) => (
            <Skeleton key={index} className="h-5 w-full rounded-lg" />
        ))}
    </div>
);

export default LoadingState;
