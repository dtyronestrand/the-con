import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}
export interface Category {
    id: number;
    name: string;
    services: Service[];
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}
export interface Service {
    id: number;
    name: string;
    url: string;
    category_id: number;
}
export interface Task {
    name: string;
    notes?: string;
    sub_tasks?: Task[];
    id: number;
    done: boolean;
    due_date: string | null;
    attachments?: string[];
}
export interface Event {
    id: number;
    webLink: string;
    subject: string;
    start: {
        dateTime: string;
    };
    end: {
        dateTime: string;
    };
    location: {
        displayName: string;
    };
}
export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    auth: Auth;
    sidebarOpen: boolean;
    [key: string]: unknown;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export type BreadcrumbItemType = BreadcrumbItem;
