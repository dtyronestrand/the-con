<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { format, parseISO } from 'date-fns'; // Optional: for nice date formatting
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
    <div class="p-6 rounded-lg shadow max-w-md">
        <h2 class="text-xl font-bold mb-4 text-white">Your Agenda</h2>

        <div v-if="!isConnected" class="text-center py-8">
            <p class="text-white mb-4">Connect your calendar to see upcoming events.</p>
            <a href="/auth/outlook" class="bg-(--blue) text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Connect Outlook Calendar
            </a>
        </div>

        <div v-else-if="events.length === 0" class="text-center py-8">
            <p class="text-gray-500">No events found for the next 3 days.</p>
        </div>

        <div v-else class="space-y-6">
            <div v-for="(events, date) in groupedEvents" :key="date">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">
                    {{ format(parseISO(date), 'EEEE, MMM d') }}
                </h3>
                <ul class="space-y-3">
                    <li v-for="event in events" :key="event.id" class="border-l-4 border-blue-500 pl-3 py-1">
                        <a :href="event.webLink" target="_blank" class="block hover:bg-gray-50 transition p-1 -ml-1 rounded">
                            <div class="font-medium text-gray-900">{{ event.subject }}</div>
                            <div class="text-sm text-gray-500">
                                {{ formatDate(event.start.dateTime) }} - {{ formatDate(event.end.dateTime) }}
                            </div>
                            <div v-if="event.location.displayName" class="text-xs text-gray-400 mt-1">
                                📍 {{ event.location.displayName }}
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>