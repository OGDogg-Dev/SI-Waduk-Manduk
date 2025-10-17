import { Link, usePage } from '@inertiajs/react';
import { Menu } from 'lucide-react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Sheet, SheetContent, SheetTrigger } from '@/components/ui/sheet';
import { cn } from '@/lib/utils';

const navItems = [
    { href: '/', label: 'Beranda' },
    { href: '/attractions', label: 'Atraksi' },
    { href: '/tickets', label: 'Tiket' },
    { href: '/events', label: 'Event' },
    { href: '/announcements', label: 'Pengumuman' },
    { href: '/merchants', label: 'UMKM' },
    { href: '/contact', label: 'Kontak' },
];

/**
 * Header situs publik dengan navigasi responsif dan CTA WhatsApp.
 */
export const Header = () => {
    const { url } = usePage();
    const [open, setOpen] = useState(false);

    const renderNav = (className?: string) => (
        <nav className={cn('flex flex-col gap-4 md:flex-row md:items-center md:gap-6', className)}>
            {navItems.map((item) => {
                const isActive = url === item.href || (item.href !== '/' && url.startsWith(item.href));

                return (
                    <Link
                        key={item.href}
                        href={item.href}
                        prefetch
                        className={cn(
                            'text-base font-medium transition-colors hover:text-primary',
                            isActive ? 'text-primary' : 'text-muted-foreground',
                        )}
                        onClick={() => setOpen(false)}
                    >
                        {item.label}
                    </Link>
                );
            })}
        </nav>
    );

    return (
        <header className="sticky top-0 z-50 border-b border-border/60 bg-background/80 backdrop-blur">
            <div className="mx-auto flex w-full max-w-6xl items-center justify-between px-4 py-4 md:px-6">
                <Link href="/" prefetch className="text-xl font-bold tracking-tight text-primary">
                    Waduk Manduk
                </Link>

                <div className="hidden items-center gap-6 md:flex">
                    {renderNav('items-center')}
                    <div className="flex items-center gap-3">
                        <Button asChild variant="outline" className="rounded-full">
                            <a href="https://maps.app.goo.gl/Z2pXSeYprwkq4bgE7" target="_blank" rel="noreferrer">
                                Lihat Rute
                            </a>
                        </Button>
                        <Button asChild className="rounded-full">
                            <a href="https://wa.me/6281234567890" target="_blank" rel="noreferrer">
                                Hubungi WA
                            </a>
                        </Button>
                    </div>
                </div>

                <div className="flex items-center gap-3 md:hidden">
                    <Button asChild variant="outline" size="sm" className="rounded-full">
                        <a href="https://maps.app.goo.gl/Z2pXSeYprwkq4bgE7" target="_blank" rel="noreferrer">
                            Rute
                        </a>
                    </Button>
                    <Sheet open={open} onOpenChange={setOpen}>
                        <SheetTrigger asChild>
                            <Button variant="ghost" size="icon" aria-label="Buka menu navigasi">
                                <Menu className="h-5 w-5" />
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="right" className="w-72 space-y-6">
                            <div>
                                <h2 className="text-lg font-semibold">Navigasi</h2>
                                <p className="text-sm text-muted-foreground">
                                    Jelajahi informasi wisata Waduk Manduk.
                                </p>
                            </div>
                            {renderNav('items-start')}
                            <div className="flex gap-3">
                                <Button asChild variant="outline" className="flex-1 rounded-full">
                                    <a href="https://maps.app.goo.gl/Z2pXSeYprwkq4bgE7" target="_blank" rel="noreferrer">
                                        Rute
                                    </a>
                                </Button>
                                <Button asChild className="flex-1 rounded-full">
                                    <a href="https://wa.me/6281234567890" target="_blank" rel="noreferrer">
                                        WhatsApp
                                    </a>
                                </Button>
                            </div>
                        </SheetContent>
                    </Sheet>
                </div>
            </div>
        </header>
    );
};

export default Header;
