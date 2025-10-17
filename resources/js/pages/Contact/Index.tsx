import { Head } from '@inertiajs/react';
import { useMutation } from '@tanstack/react-query';
import { useState } from 'react';
import toast from 'react-hot-toast';
import Card from '@/components/common/Card';
import MapView from '@/components/common/MapView';
import Section from '@/components/common/Section';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import api, { ApiClientError } from '@/lib/api';
import type { InquiryPayload } from '@/lib/types';
import PublicLayout from '@/layouts/PublicLayout';
import { z } from 'zod';

const inquirySchema = z.object({
    name: z.string().min(3, 'Nama minimal 3 karakter'),
    email: z.string().email('Email tidak valid').optional().or(z.literal('').transform(() => undefined)),
    phone: z.string().optional(),
    type: z.enum(['PERTANYAAN', 'SARAN', 'PENGADUAN']),
    message: z.string().min(10, 'Pesan minimal 10 karakter'),
});

const inquiryTypes = [
    { value: 'PERTANYAAN', label: 'Pertanyaan' },
    { value: 'SARAN', label: 'Saran' },
    { value: 'PENGADUAN', label: 'Pengaduan' },
];

const ContactIndex = () => {
    const [form, setForm] = useState<InquiryPayload>({
        name: '',
        email: undefined,
        phone: undefined,
        type: 'PERTANYAAN',
        message: '',
    });

    const mutation = useMutation({
        mutationFn: async (payload: InquiryPayload) => {
            const response = await api.post('/inquiries', payload);
            return response.data;
        },
        onSuccess: () => {
            toast.success('Pesan Anda berhasil dikirim. Kami akan merespons secepatnya.');
            setForm({ name: '', email: undefined, phone: undefined, type: 'PERTANYAAN', message: '' });
        },
        onError: (error: unknown) => {
            if (error instanceof ApiClientError) {
                toast.error(error.payload.message);
            } else {
                toast.error('Gagal mengirim pesan. Silakan coba lagi.');
            }
        },
    });

    const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        const parsed = inquirySchema.safeParse(form);

        if (!parsed.success) {
            const firstError = parsed.error.issues[0]?.message ?? 'Periksa kembali formulir Anda.';
            toast.error(firstError);
            return;
        }

        mutation.mutate(parsed.data);
    };

    return (
        <PublicLayout>
            <Head title="Kontak Pengelola">
                <meta name="description" content="Kirim pertanyaan, saran, atau pengaduan Anda kepada pengelola Waduk Manduk." />
            </Head>

            <Section title="Hubungi Kami" description="Pengelola Waduk Manduk siap membantu kebutuhan informasi Anda.">
                <div className="grid gap-8 md:grid-cols-2">
                    <Card title="Formulir Pesan">
                        <form className="space-y-4" onSubmit={handleSubmit}>
                            <div className="space-y-2">
                                <Label htmlFor="name">Nama lengkap</Label>
                                <Input
                                    id="name"
                                    value={form.name}
                                    onChange={(event) => setForm((prev) => ({ ...prev, name: event.target.value }))}
                                    required
                                />
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="email">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    value={form.email ?? ''}
                                    onChange={(event) =>
                                        setForm((prev) => ({
                                            ...prev,
                                            email: event.target.value || undefined,
                                        }))
                                    }
                                />
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="phone">Nomor telepon</Label>
                                <Input
                                    id="phone"
                                    value={form.phone ?? ''}
                                    onChange={(event) => setForm((prev) => ({ ...prev, phone: event.target.value || undefined }))}
                                />
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="type">Jenis pesan</Label>
                                <Select
                                    value={form.type}
                                    onValueChange={(value) =>
                                        setForm((prev) => ({
                                            ...prev,
                                            type: value as InquiryPayload['type'],
                                        }))
                                    }
                                >
                                    <SelectTrigger id="type">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {inquiryTypes.map((option) => (
                                            <SelectItem key={option.value} value={option.value}>
                                                {option.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="message">Pesan</Label>
                                <Textarea
                                    id="message"
                                    rows={5}
                                    value={form.message}
                                    onChange={(event) => setForm((prev) => ({ ...prev, message: event.target.value }))}
                                    required
                                />
                            </div>
                            <Button type="submit" className="rounded-full" disabled={mutation.isPending}>
                                {mutation.isPending ? 'Mengirim...' : 'Kirim Pesan'}
                            </Button>
                        </form>
                    </Card>
                    <Card title="Informasi Kontak">
                        <p className="text-sm text-muted-foreground">
                            Pengelola Waduk Manduk
                            <br />
                            Desa Manduk, Kec. Bungkal, Ponorogo
                        </p>
                        <div className="space-y-1 text-sm text-muted-foreground">
                            <p>Telepon kantor: (0352) 123-456</p>
                            <p>WhatsApp: 0812-3456-7890</p>
                            <p>Email: info@wadukmanduk.local</p>
                        </div>
                        <MapView className="h-64" />
                        <Button asChild variant="secondary" className="w-fit rounded-full">
                            <a href="https://wa.me/6281234567890" target="_blank" rel="noreferrer">
                                Chat via WhatsApp
                            </a>
                        </Button>
                    </Card>
                </div>
            </Section>
        </PublicLayout>
    );
};

export default ContactIndex;
