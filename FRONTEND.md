TUGAS: Bangun FRONTEND DINAMIS (publik) untuk “Sistem Informasi Pariwisata Waduk Manduk” di proyek Laravel + Inertia + React (repo SI-Waduk-Manduk). Gunakan TypeScript, Tailwind, shadcn/ui, dan Radix UI. Semua komentar/teks penjelas dalam Bahasa Indonesia.

===================================================
0) DEPENDENSI FRONTEND (PAKET NPM)
===================================================
Tambahkan (jika belum ada):
- Data fetching & util: @tanstack/react-query, axios, dayjs, zod
- UI & notifikasi: react-hot-toast
- Peta: leaflet, react-leaflet (+ @types/leaflet)
- Aksesibilitas/ikon: lucide-react (bila belum)
- Inertia: @inertiajs/react (pastikan ada)
Instruksi install (cantumkan di README):
npm i @tanstack/react-query axios dayjs zod react-hot-toast leaflet react-leaflet lucide-react
npm i -D @types/leaflet

===================================================
1) STRUKTUR FOLDER FRONTEND
===================================================
Buat/rapikan di resources/js:
- resources/js/app.tsx  -> entry Vite+Inertia (bootstrap React Query, Toaster)
- resources/js/lib/api.ts -> axios instance (baseURL '/api/v1', interceptor error)
- resources/js/lib/queryClient.ts -> createQueryClient (default options caching)
- resources/js/lib/types.ts -> tipe TS untuk data API (Attraction, Event, Announcement, TicketType, Facility, Merchant, StatusToday, InquiryPayload, dsb.)
- resources/js/components/common/*
  - Header.tsx (navbar, highlight CTA “Beli Tiket/Hubungi WA” opsional)
  - Footer.tsx
  - Section.tsx (Section header + description)
  - Card.tsx (wrapper shadcn Card + varian)
  - EmptyState.tsx, ErrorState.tsx, LoadingState.tsx
  - MapView.tsx (Leaflet + marker koordinat attraction/dermaga)
  - TicketTable.tsx, EventCard.tsx, AnnouncementBanner.tsx
- resources/js/hooks/*
  - useStatusToday.ts (React Query GET /status-today)
  - usePaginated.ts helper
- resources/js/pages/* (Inertia pages)
  - Home/Index.tsx
  - Attractions/Index.tsx
  - Attractions/Show.tsx
  - Tickets/Index.tsx
  - Events/Index.tsx
  - Announcements/Index.tsx
  - Merchants/Index.tsx
  - Contact/Index.tsx (form Inquiry)
- resources/js/styles/tailwind.css (jika belum)
Catatan: gunakan komponen shadcn/ui (Button, Card, Badge, Input, Textarea, Select, Tabs, Dialog) sesuai kebutuhan.

===================================================
2) ROUTING LARAVEL (WEB) -> INERTIA PAGES
===================================================
Tambahkan di routes/web.php (controller stub boleh, tapi cukup closure juga bisa):
Route::get('/', fn() => Inertia::render('Home/Index'))->name('home');
Route::get('/attractions', fn() => Inertia::render('Attractions/Index'))->name('attractions.index');
Route::get('/attractions/{slug}', fn($slug) => Inertia::render('Attractions/Show', ['slug' => $slug]))->name('attractions.show');
Route::get('/tickets', fn() => Inertia::render('Tickets/Index'))->name('tickets.index');
Route::get('/events', fn() => Inertia::render('Events/Index'))->name('events.index');
Route::get('/announcements', fn() => Inertia::render('Announcements/Index'))->name('announcements.index');
Route::get('/merchants', fn() => Inertia::render('Merchants/Index'))->name('merchants.index');
Route::get('/contact', fn() => Inertia::render('Contact/Index'))->name('contact.index');

Catatan:
- SEO meta (title/description) diset di tiap page (Head dari @inertiajs/react).
- Jika SSR Inertia aktif, pastikan entry SSR mem-pass React Query dehydratedState (opsional).

===================================================
3) KONEKSI API (CLIENT-SIDE DATA FETCH)
===================================================
Gunakan endpoint publik API v1 dari backend:
GET /api/v1/status-today           -> widget status buka
GET /api/v1/attractions            -> listing + filter ?type, ?q
GET /api/v1/attractions/{slug}     -> detail
GET /api/v1/ticket-types           -> tabel harga
GET /api/v1/events                 -> upcoming, filter ?q, ?date_from, ?date_to
GET /api/v1/announcements          -> list published (paginate)
GET /api/v1/facilities             -> list fasilitas
GET /api/v1/merchants              -> ?category, ?q
POST /api/v1/inquiries             -> form kontak/aduan (rate limit 5/min/IP)

Implementasi:
- api.ts -> axios.create({ baseURL: '/api/v1', timeout 10s })
- Tambah response interceptor untuk normalisasi error {message, errors}.
- hooks: useQuery/useInfiniteQuery (TanStack) untuk list; simpan staleTime sesuai (mis. 2 menit).
- Handle loading/error state via komponen LoadingState & ErrorState.
- Pakai zod untuk validate shape minimal (opsional).

===================================================
4) DESAIN HALAMAN & KOMPONEN (DETAIL)
===================================================
4.1 Home/Index.tsx
- Hero dinamis: judul “Waduk Manduk”, tombol Rute (link ke Google Maps) & Tiket.
- Status Hari Ini: panggil useStatusToday() -> tampilkan badge “Buka/Tutup”, jam buka-tutup, dan closures_today (jika ada) berupa alert merah/kuning.
- Highlight: 3 kartu destinasi (data dari /attractions?type=WAHANA|SPOT, ambil 3 teratas), 1 banner pengumuman terbaru (severity jadi warna Badge).
- Galeri singkat (opsional): grid 6 foto (nantinya dari Media Library URL image yang dikirim API).

4.2 Attractions/Index.tsx
- Filter: Select Type (WAHANA/SPOT/GENERAL), Input Pencarian.
- Grid kartu: gambar cover, nama, tipe (Badge), harga dasar (jika ada).
- Aksi: klik kartu -> route ke /attractions/{slug}.
- Peta kecil opsional: MapView memplotkan titik lat/long result (cluster jika banyak).

4.3 Attractions/Show.tsx
- Ambil slug via props Inertia; fetch detail via /attractions/{slug}.
- Tampilkan: judul, gambar (carousel sederhana), deskripsi, koordinat (MapView center marker).
- Seksi info: jam operasional (tarik /status-today dengan attractionId opsional jika API mendukung), harga dasar, fasilitas terkait (opsional).
- Breadcrumb & tombol “Buka Rute”.

4.4 Tickets/Index.tsx
- Tabel harga dari /ticket-types (weekday/weekend/holiday).
- Komponen TicketTable (sortable, ada catatan).
- CTA “Beli tiket (opsional)” atau “Hubungi loket”.

4.5 Events/Index.tsx
- Filter tanggal: date_from, date_to; input q.
- Kartu Event: title, waktu (format dayjs), venue, badge “Published”.
- Tampilkan “Belum ada event mendatang” (EmptyState) jika kosong.

4.6 Announcements/Index.tsx
- Daftar pengumuman: severity -> Badge warna (INFO biru, WARNING kuning, ALERT merah), published_at, expired_at jika ada.
- Komponen AnnouncementBanner untuk menonjolkan ALERT paling baru.

4.7 Merchants/Index.tsx
- Filter kategori: KULINER/SOUVENIR/SEWA_PERALATAN/LAINNYA.
- Kartu Merchant: nama, kategori (Badge), tombol “Chat WA” (wa.me/nomor).
- List/grid responsif.

4.8 Contact/Index.tsx
- Form Inquiry: name, email/phone, type (PERTANYAAN/SARAN/PENGADUAN), message.
- Validasi client (zod) + server (FormRequest).
- Submit ke POST /inquiries; sukses -> toast “Pesan terkirim”, reset form.
- Info kontak resmi + peta lokasi waduk (MapView fokus dermaga utama).

===================================================
5) KOMPONEN TEKNIS & UTIL
===================================================
- <AppProviders>: bungkus Inertia app dengan QueryClientProvider & Toaster.
- useStatusToday(): GET /status-today -> {open_now, open_time, close_time, closures_today[]}
- MapView.tsx: React-Leaflet <MapContainer> height responsif, tile OSM, <Marker position={[lat,lng]}>; fallback jika koordinat null.
- Utility formatHarga(idr:number): string “RpX.XXX”.
- A11y: semua gambar pakai alt, tombol/toggle berlabel, warna kontras untuk ALERT.

===================================================
6) TAILWIND + SHADCN/UI
===================================================
- Gunakan komponen shadcn/ui (Button, Card, Badge, Input, Select, Textarea, Tabs).
- Style guideline ringkas: konten ber-grid, spacing cukup (p-6+), card rounded-2xl, shadow lembut.
- Dark mode: ikuti preferensi sistem (opsional).

===================================================
7) SEO & PERFORMANCE
===================================================
- Gunakan <Head> dari @inertiajs/react untuk title, meta description unik per page.
- OG tags dasar (title, description, image) untuk Home & Attraction Show.
- Lazy-load gambar (loading="lazy"); compress gambar dari CDN/storage bila tersedia.
- Prefetch Inertia link untuk navigasi cepat.

===================================================
8) TEST & KRITERIA SELESAI
===================================================
- Halaman termuat tanpa error; state loading/error tertangani.
- Panggilan ke endpoint publik sukses (mock bila belum).
- Form Inquiry mengirim dan menampilkan toast sukses/gagal.
- Linter & TypeScript passing; build Vite sukses.
- Tambahkan contoh e2e ringan (Pest + Laravel Dusk/Inertia SSR opsional).

===================================================
9) CONTOH KERANGKA KODE SINGKAT (HARUS DIBUAT)
===================================================
// resources/js/app.tsx -> setup Inertia + React Query + Toaster
// resources/js/lib/api.ts -> axios instance
// resources/js/lib/queryClient.ts -> createQueryClient
// resources/js/pages/Home/Index.tsx -> pakai useStatusToday()
// resources/js/components/common/* -> Header, Footer, MapView, TicketTable, AnnouncementBanner
// routes/web.php -> daftar route Inertia (poin 2)

TOLONG: Buat seluruh file di atas, isi komponen/page dengan contoh implementasi fungsional (bukan placeholder kosong). Gunakan TypeScript ketat untuk tipe data API. Pastikan UI terlihat rapi dengan shadcn/ui dan Tailwind.
