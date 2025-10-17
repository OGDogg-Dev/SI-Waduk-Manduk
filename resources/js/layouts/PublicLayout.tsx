import type { PropsWithChildren } from 'react';
import Footer from '@/components/common/Footer';
import Header from '@/components/common/Header';

export const PublicLayout = ({ children }: PropsWithChildren) => (
    <div className="flex min-h-screen flex-col bg-background text-foreground">
        <Header />
        <main className="flex-1 bg-gradient-to-b from-background via-background to-muted/20">
            {children}
        </main>
        <Footer />
    </div>
);

export default PublicLayout;
