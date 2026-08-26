<template>
    <div class="flex h-full flex-col">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Quick Notes</h2>
            <button
                @click="addNote"
                class="rounded bg-blue-500 px-3 py-1 text-sm text-white hover:bg-blue-600"
            >
                + New Note
            </button>
        </div>

        <div class="flex flex-wrap items-start gap-4">
            <NoteItem v-for="note in notes" :key="note.id" :note="note" />
        </div>
    </div>
</template>

<script setup lang="ts">
import NoteItem from '@/components/NoteItem.vue';
import { router } from '@inertiajs/vue3';

defineProps<{
    notes: any[];
}>();

const addNote = () => {
    router.post('/notes/', {
        preserveScroll: true,
    });
};
</script>

<style scoped></style>
