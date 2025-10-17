import { Link } from '@inertiajs/react';

const footerLinks = [
    { href: '/attractions', label: 'Atraksi' },
    { href: '/events', label: 'Event' },
    { href: '/announcements', label: 'Pengumuman' },
    { href: '/merchants', label: 'UMKM' },
];

/**
 * Footer situs publik dengan informasi kontak singkat.
 */
export const Footer = () => (
    <footer className="border-t border-border/60 bg-muted/40">
        <div className="mx-auto w-full max-w-6xl px-4 py-10 md:px-6">
            <div className="grid gap-8 md:grid-cols-3">
                <div>
                    <h3 className="text-lg font-semibold">Waduk Manduk</h3>
                    <p className="mt-2 text-sm text-muted-foreground">
                        Destinasi wisata air di Ponorogo dengan panorama matahari terbenam, wahana keluarga,
                        dan UMKM lokal.
                    </p>
                </div>
                <div>
                    <h4 className="text-sm font-semibold uppercase tracking-widest text-muted-foreground">
                        Tautan Cepat
                    </h4>
                    <ul className="mt-3 space-y-2 text-sm">
                        {footerLinks.map((link) => (
                            <li key={link.href}>
                                <Link href={link.href} prefetch className="transition-colors hover:text-primary">
                                    {link.label}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </div>
                <div>
                    <h4 className="text-sm font-semibold uppercase tracking-widest text-muted-foreground">
                        Kontak
                    </h4>
                    <p className="mt-3 text-sm text-muted-foreground">
                        Desa Manduk, Kec. Bungkal, Ponorogo
                        <br />
                        Telepon: (0352) 123-456
                        <br />
                        WhatsApp: 0812-3456-7890
                    </p>
                </div>
            </div>
            <div className="mt-8 flex flex-col items-center justify-between gap-4 border-t border-border/40 pt-6 text-sm text-muted-foreground md:flex-row">
                <span>&copy; {new Date().getFullYear()} Pengelola Waduk Manduk. Hak cipta dilindungi.</span>
                <span>Dibuat dengan cinta untuk wisata Ponorogo.</span>
            </div>
        </div>
    </footer>
);

export default Footer;
