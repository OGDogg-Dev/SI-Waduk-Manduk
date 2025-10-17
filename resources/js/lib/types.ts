export type AttractionType = 'WAHANA' | 'SPOT' | 'GENERAL';
export type MerchantCategory =
    | 'KULINER'
    | 'SOUVENIR'
    | 'SEWA_PERALATAN'
    | 'LAINNYA';
export type AnnouncementSeverity = 'INFO' | 'WARNING' | 'ALERT';

export interface ApiErrorResponse {
    message: string;
    errors?: Record<string, string[]>;
}

export interface MediaResource {
    id: number;
    name: string;
    url: string;
    type?: string;
}

export interface BaseEntity {
    id: number;
    created_at: string;
    updated_at: string;
}

export interface Attraction extends BaseEntity {
    name: string;
    slug: string;
    type: AttractionType;
    description: string | null;
    excerpt?: string | null;
    base_price: number | null;
    is_active: boolean;
    latitude: number | null;
    longitude: number | null;
    cover?: MediaResource | null;
    images: MediaResource[];
}

export interface TicketType extends BaseEntity {
    name: string;
    slug: string;
    weekday_price: number;
    weekend_price: number;
    holiday_price: number;
    is_active: boolean;
}

export interface EventItem extends BaseEntity {
    title: string;
    slug: string;
    description: string;
    excerpt?: string | null;
    start_at: string;
    end_at: string | null;
    venue: string | null;
    is_published: boolean;
    cover?: MediaResource | null;
    images: MediaResource[];
}

export interface Announcement extends BaseEntity {
    title: string;
    slug: string;
    content: string;
    excerpt?: string | null;
    severity: AnnouncementSeverity;
    published_at: string | null;
    expired_at: string | null;
}

export interface Facility extends BaseEntity {
    name: string;
    icon: string | null;
    description: string | null;
    is_available: boolean;
}

export interface Merchant extends BaseEntity {
    name: string;
    category: MerchantCategory;
    phone: string | null;
    whatsapp: string | null;
    location: string | null;
    is_verified: boolean;
    images: MediaResource[];
}

export interface StatusClosure {
    id: number;
    reason: string;
    start_at: string;
    end_at: string | null;
    attraction_name: string | null;
}

export interface StatusToday {
    open_now: boolean;
    open_time: string | null;
    close_time: string | null;
    closures_today: StatusClosure[];
}

export interface InquiryPayload {
    name: string;
    email?: string;
    phone?: string;
    type: 'PERTANYAAN' | 'SARAN' | 'PENGADUAN';
    message: string;
}

export interface PaginatedMeta {
    current_page: number;
    per_page: number;
    last_page: number;
    total: number;
    next_page_url?: string | null;
    prev_page_url?: string | null;
}

export interface PaginatedResponse<T> {
    data: T[];
    meta: PaginatedMeta;
    links?: {
        next?: string | null;
        prev?: string | null;
    };
}

export type WithPagination<T> = PaginatedResponse<T>;
