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
    id: string;
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
    id: string;
    name: string;
    url: string;
    category_id: string;
}
export interface Task {
    name: string;
    notes?: string;
    sub_tasks?: Task[];
    id: string;
    done: boolean;
    due_date: string | null;
    attachments?: string[];
    note_id?: string | null;
    created_at?: string;
    updated_at?: string;
}
export interface DemotedTask {
    text: string;
    due_date: string | null;
    demoted_at: string;
}
export interface Note {
    id: string;
    user_id: string;
    title: string | null;
    content: string | null;
    color: string;
    tags: string[];
    pinned: boolean;
    archived: boolean;
    demoted_tasks: DemotedTask[];
    tasks: Task[];
    created_at: string;
    updated_at: string;
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
