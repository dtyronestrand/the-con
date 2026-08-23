<script setup lang="ts">
import { format, parseISO } from 'date-fns'; // Optional: for nice date formatting
import { computed } from 'vue';

import type { Event } from '@/types'; // Define Event type as per your data structure
const props = defineProps<{
    isConnected: boolean;
    events: Event[];
}>();

// Helper to group events by day for better UI
const groupedEvents = computed(() => {
    if (!props.events) return {};

    return props.events.reduce((groups: Record<string, Event[]>, event) => {
        const date = event.start.dateTime.split('T')[0];
        if (!groups[date]) {
            groups[date] = [];
        }
        groups[date].push(event);
        return groups;
    }, {});
});

const formatDate = (isoString: string) => {
    return format(parseISO(isoString), 'h:mm a');
};
</script>

<template>
    <div class="max-w-md bg-surface-raised p-6 shadow">
        <h2 class="mb-4 text-xl font-semibold text-on-surface">
            Your Agenda
        </h2>

        <div v-if="!isConnected" class="py-8 text-center">
            <p class="mb-4 text-on-surface">
                Connect your calendar to see upcoming events.
            </p>
            <a
                href="/auth/outlook"
                class="rounded bg-tertiary px-4 py-2 text-ink transition hover:bg-tertiary-strong"
            >
                Connect Outlook Calendar
            </a>
        </div>

        <div v-else-if="events.length === 0" class="py-8 text-center">
            <p class="text-neutral">No events found for the next 3 days.</p>
        </div>

        <div v-else class="space-y-6">
            <div v-for="(events, date) in groupedEvents" :key="date">
                <h3
                    class="mb-2 text-sm font-semibold tracking-wider text-neutral uppercase"
                >
                    {{ format(parseISO(date), 'EEEE, MMM d') }}
                </h3>
                <ul class="space-y-3">
                    <li
                        v-for="event in events"
                        :key="event.id"
                        class="border-l-4 border-panel-secondary-strong py-1 pl-3"
                    >
                        <a
                            :href="event.webLink"
                            target="_blank"
                            class="-ml-1 block rounded p-1 transition hover:bg-tertiary/20"
                        >
                            <div class="font-medium text-on-surface">
                                {{ event.subject }}
                            </div>
                            <div
                                class="font-mono text-sm text-panel-secondary tabular-nums"
                            >
                                {{ formatDate(event.start.dateTime) }} -
                                {{ formatDate(event.end.dateTime) }}
                            </div>
                            <div
                                v-if="event.location.displayName"
                                class="mt-1 text-xs text-neutral"
                            >
                                📍 {{ event.location.displayName }}
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
