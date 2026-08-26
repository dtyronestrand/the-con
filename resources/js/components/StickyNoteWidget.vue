<template>
    <div class="flex h-full flex-col">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Quick Notes</h2>
            <button
                @click="addNote"
                :disabled="isAdding"
                aria-label="Add new note"
                class="flex items-center gap-2 rounded bg-blue-500 px-3 py-1 text-sm text-white hover:bg-blue-600 focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
            >
                <Spinner v-if="isAdding" class="h-4 w-4" />
                <span v-else aria-hidden="true">+</span>
                New Note
            </button>
        </div>

        <div class="flex flex-wrap items-start gap-4">
            <StickyNoteItem v-for="note in notes" :key="note.id" :note="note" />
        </div>
    </div>
</template>

<script setup lang="ts">
import StickyNoteItem from '@/components/StickyNoteItem.vue';
import Spinner from '@/components/ui/spinner/Spinner.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    notes: any[];
}>();

const isAdding = ref(false);

const addNote = () => {
    if (isAdding.value) return;
    isAdding.value = true;
    router.post(
        '/sticky-notes/',
        {},
        {
            preserveScroll: true,
            onFinish: () => {
                isAdding.value = false;
            },
        },
    );
};
</script>

<style scoped></style>
