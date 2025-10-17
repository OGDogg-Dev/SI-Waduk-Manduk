TUGAS: Bangun backend dan admin dashboard “Sistem Informasi Pariwisata Waduk Manduk” di proyek Laravel (repo SI-Waduk-Manduk) dengan spesifikasi di bawah. Gunakan Bahasa Indonesia di komentar/docblock. Tulis kode yang rapi, idempotent, PSR-12, dan siap dijalankan.

===================================================
A. STRUKTUR DATA (MIGRATION + MODEL + RELASI)
===================================================
1) Buat migration & model Eloquent (snake_case, id auto-increment, timestamps). Enum gunakan string biasa (validate di FormRequest/Rules). Tambahkan index seperlunya (slug unik, foreign key, dll). Tambahkan $fillable, $casts, relasi antar model.

Tabel & kolom:
- attractions:
  id, name, slug(unique), type enum['WAHANA','SPOT','GENERAL'],
  description longtext nullable, base_price decimal(10,2) nullable,
  is_active boolean default true, latitude decimal(10,7) nullable, longitude decimal(10,7) nullable,
  created_at, updated_at
- operating_hours:
  id, day_of_week tinyint (0=Ahad..6=Sabtu), open_time time, close_time time,
  is_closed boolean default false, attraction_id unsignedBigInteger nullable FK->attractions onDelete set null,
  created_at, updated_at
- ticket_types:
  id, name, slug(unique),
  weekday_price decimal(10,2), weekend_price decimal(10,2), holiday_price decimal(10,2),
  is_active boolean default true, created_at, updated_at
- events:
  id, title, slug(unique), description longtext, start_at datetime, end_at datetime nullable,
  venue varchar(150) nullable, is_published boolean default false, created_at, updated_at
- announcements:
  id, title, slug(unique), content longtext,
  severity enum['INFO','WARNING','ALERT'] default 'INFO',
  published_at datetime nullable, expired_at datetime nullable, created_at, updated_at
- facilities:
  id, name, icon nullable, description text nullable, is_available boolean default true, created_at, updated_at
- merchants:
  id, name, category enum['KULINER','SOUVENIR','SEWA_PERALATAN','LAINNYA'] default 'KULINER',
  phone varchar(30) nullable, whatsapp varchar(30) nullable, location text nullable,
  is_verified boolean default false, created_at, updated_at
- inquiries:
  id, name, email nullable, phone nullable,
  type enum['PERTANYAAN','SARAN','PENGADUAN'] default 'PERTANYAAN',
  message longtext, status enum['BARU','DIPROSES','SELESAI'] default 'BARU',
  handled_by unsignedBigInteger nullable FK->users onDelete set null,
  created_at, updated_at
- closures:
  id, reason text, start_at datetime, end_at datetime nullable,
  attraction_id unsignedBigInteger nullable FK->attractions onDelete set null,
  created_at, updated_at
- settings:
  id, key varchar(100) unique, value json nullable, created_at, updated_at

Relasi model:
- Attraction hasMany OperatingHour & Closure
- OperatingHour belongsTo Attraction
- Closure belongsTo Attraction
- Inquiry belongsTo User (handled_by -> users.id)
- User hasMany Inquiry (handled_by)
- TicketType, Facility, Merchant, Announcement, Event berdiri sendiri

Tambahan:
- Buat Observer “App/Observers/SluggableObserver.php” untuk generate slug dari name/title saat creating/updating jika slug kosong (pakai Str::slug, unique check).
- Daftarkan observer di AppServiceProvider untuk: Attraction, TicketType, Event, Announcement, Merchant, Facility (yang punya name/title + slug).
- Integrasi Spatie Media Library: pada model Attraction, Event, Merchant, Announcement sediakan media collection “images” (multiple) + “cover” (single).

===================================================
B. AUTH + RBAC (SANCTUM + SPATIE PERMISSION)
===================================================
1) Pastikan User model menggunakan trait HasApiTokens (Sanctum) dan HasRoles (Spatie).
2) Buat seeder “PermissionRoleSeeder”:
   - Roles: ['super_admin','admin','editor','officer'].
   - Permission per entitas (CRUD granular): {viewAny, view, create, update, delete} untuk: attractions, operating_hours, ticket_types, events, announcements, facilities, merchants, inquiries, closures, settings.
   - Mapping:
     * super_admin: semua permission
     * admin: semua CRUD kecuali delete pada settings
     * editor: manage konten (attractions, operating_hours, events, announcements, facilities, merchants, closures) — tidak boleh mengubah settings & ticket_types delete
     * officer: read-only semua + boleh update status inquiries
   - Assign role super_admin ke user admin@wadukmanduk.local jika ada; bila belum ada, buat user tersebut dengan password sementara “password123”.
3) Buat Policy per model (php artisan make:policy) dan terapkan logika permission di method: viewAny, view, create, update, delete.
4) Proteksi route privat API dengan Sanctum dan policy/permission (Gate::allows atau middleware custom).

===================================================
C. SEEDERS + FACTORIES (DATA REALISTIS)
===================================================
Buat:
- database/seeders/MasterDataSeeder.php
  * attractions:
    1) “Perahu Bebek” (WAHANA, base_price 15000)
    2) “Spot Sunset Bukit Manduk” (SPOT, base_price null)
    3) “Dermaga Utama” (GENERAL)
  * operating_hours global (attraction_id null):
    - Senin–Jumat 08:00–17:00 (day_of_week 1..5)
    - Sabtu–Minggu 07:00–18:00 (day_of_week 6..0)
  * ticket_types:
    - “Tiket Masuk”: weekday 5000, weekend 7000, holiday 10000
    - “Parkir Motor”: weekday 2000, weekend 3000, holiday 3000
    - “Sewa Perahu Bebek (30 menit)”: weekday 15000, weekend 20000, holiday 20000
  * facilities: Mushola, Toilet, Tempat Sampah, Gazebo, Tempat Charger
  * merchants: 8 UMKM (5 KULINER, 3 SOUVENIR) dengan nomor WA dummy
- database/seeders/ContentSeeder.php
  * events (publish):
    - “Festival Mancing Manduk”, start_at: 2025-11-10 06:00, venue: “Dermaga Utama”, is_published: true
  * announcements:
    - “Pengumuman Cuaca” severity WARNING (published_at now)
    - “Area Bukit Manduk Ditutup Sementara” severity ALERT (expired_at now+3 hari)
  * inquiries: 3 data dummy (tiap type)
  * closures: 1 penutupan untuk “Spot Sunset Bukit Manduk” selama 2 hari (perbaikan)
- database/seeders/DatabaseSeeder.php memanggil:
  PermissionRoleSeeder, MasterDataSeeder, ContentSeeder

Factories:
- Sediakan factory untuk entity yang perlu data dummy.
- Siapkan helper attachSampleMedia(Model $m) (opsional): jika ada file di storage/app/seeds, lampirkan ke collection “images”.

Target: `php artisan migrate:fresh --seed` berjalan tanpa error.

===================================================
D. SERVICE STATUS OPERASIONAL
===================================================
Buat kelas service: App/Services/OperatingStatusService.php
- Method: getStatusForToday(?int $attractionId = null): array
  Menghitung open_now(bool), open_time, close_time (string HH:MM) berdasarkan operating_hours.
  Prioritas:
    * Jika ada operating_hours khusus attractionId untuk hari ini, gunakan itu.
    * Jika tidak, gunakan operating_hours global (attraction_id null).
  Perhitungkan closures aktif hari ini (jika ada penutupan yang meliputi waktu sekarang, open_now = false).
  Kembalikan juga daftar closures_today[] (id, reason, start_at, end_at, attraction_name).

===================================================
E. API v1 (ROUTES + CONTROLLER + RESOURCE)
===================================================
Struktur routes:
- routes/api.php
  Route::prefix('api/v1')->group(function () {
    // Publik
    GET /status-today                 -> StatusTodayController@index
    GET /announcements                -> AnnouncementController@index (published only, paginate)
    GET /events                       -> EventController@index (published upcoming, ?q, ?date_from, ?date_to, paginate)
    GET /attractions                  -> AttractionController@index (aktif, ?type, ?q, paginate)
    GET /attractions/{slug}           -> AttractionController@show
    GET /ticket-types                 -> TicketTypeController@index
    GET /facilities                   -> FacilityController@index
    GET /merchants                    -> MerchantController@index (?category, ?q)

    // Publik form
    POST /inquiries                   -> InquiryController@store (rate limit 5/min per IP)

    // Privat (Sanctum auth + permission)
    Route::middleware('auth:sanctum')->group(function () {
      apiResource untuk: attractions, operating-hours, ticket-types, events,
        announcements, facilities, merchants, inquiries (kecuali store publik), closures, settings;
      PATCH /inquiries/{id}/status     -> InquiryStatusController@update (ubah status + handled_by)
    });
  });

Controller/Resource:
- Gunakan FormRequest untuk validasi.
- Gunakan API Resource (transformer) guna response konsisten:
  * fields umum: id, title/name, slug, excerpt (jika ada), images[] (url), geo{lat,lng}, timestamps.
- Caching: gunakan Cache::remember 5 menit untuk endpoint publik (kecuali POST).
- Throttle POST inquiries: rate limit 5/min per IP (via middleware atau RateLimiter).
- Gunakan OperatingStatusService pada StatusTodayController.

Testing:
- Buat minimal 2 Feature Test (Pest/Laravel):
  * GET /api/v1/status-today -> struktur JSON sesuai { open_now, open_time, close_time, closures_today[] }
  * POST /api/v1/inquiries valid -> 201, dan cek rate limit berfungsi.

===================================================
F. ADMIN DASHBOARD (FILAMENT v3)
===================================================
Buat Filament Resources:
- AttractionResource
- OperatingHourResource
- TicketTypeResource
- EventResource
- AnnouncementResource
- FacilityResource
- MerchantResource
- InquiryResource
- ClosureResource
- SettingResource

Ketentuan umum:
- Forms:
  * name/title (required), slug (readOnly, auto dari observer), description (RichEditor),
  * toggle is_active/is_published,
  * Select enum (jelas opsinya),
  * Upload gambar via Media Library: collection 'images' (multiple) + 'cover' (single jika perlu),
  * Event: DateTimePicker untuk start_at, end_at.
  * Announcement: severity Select; tampilkan Badge warna di tabel (INFO biru, WARNING kuning, ALERT merah).
  * Inquiry: status Select; onSave bila status berubah, set handled_by = auth()->id().
- Tables:
  * searchable, sortable, filterable (status/jenis/kategori/published),
  * badge/ikon untuk status publikasi/aktif.
- Relation Manager:
  * Di AttractionResource tambahkan OperatingHoursRelationManager & ClosuresRelationManager.
- Navigasi:
  * Kelompokkan menu: “Konten” (Attraction, Event, Announcement, Facility),
    “Operasional” (OperatingHour, Closure, TicketType),
    “UMKM” (Merchant),
    “Komunikasi” (Inquiry),
    “Pengaturan” (Setting).
- Hak Akses:
  * Integrasikan Policy/Permission: override canViewAny/canCreate/canEdit/canDelete pada tiap Resource agar sesuai role.
  * Sembunyikan menu Resource jika user tidak punya permission.

===================================================
G. UTILITAS & QUALITY
===================================================
- Tambahkan phpdoc, komentar Indonesia singkat di class/ method penting.
- Buat route api resource menggunakan Route::apiResource & nama controller konvensi.
- Pastikan semua namespace benar, impor class dengan use.
- Pastikan storage link: gunakan path media 'public' (Media Library).
- Tambahkan helper global untuk format harga (opsional).
- Pastikan semua perintah artisan yang relevan disebut di README singkat:
  * php artisan migrate:fresh --seed
  * php artisan storage:link
  * php artisan serve
  * akses admin: /admin (Filament)

===================================================
H. KRITERIA SELESAI
===================================================
- Migrate & seed berjalan tanpa error.
- /admin tampil, login bisa (user super_admin).
- Endpoint publik /api/v1/* berfungsi & ter-cache.
- RBAC bekerja, menu Filament tersembunyi sesuai role.
- Test fitur lulus (Pest).
- Kode bersih, konsisten, dan terdokumentasi ringkas.

SELESAI. Silakan buat semua file (migration, model, observer, service, policy, controller, request, resource, seeder, test, dan resource Filament) sesuai spesifikasi di atas.
