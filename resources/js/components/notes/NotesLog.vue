<template>
    <div class="flex h-full min-h-0 w-full">
        <div class="flex w-28 shrink-0 flex-col ml-8 font-display text-sm text-on-surface uppercase">
            <button
                type="button"
                class="cursor-pointer border-t-2 border-ink bg-panel-primary-strong py-4 hover:brightness-110"
                :class="{ 'brightness-125': filter === 'all' }"
                @click="filter = 'all'"
            >
                All
            </button>
            <button
                type="button"
                class="cursor-pointer border-t-2 border-ink bg-panel-secondary-strong py-4 text-center leading-tight hover:brightness-110"
                :class="{ 'brightness-125': filter === 'open' }"
                @click="filter = 'open'"
            >
                Open<br />Items
            </button>
            <button
                type="button"
                class="cursor-pointer border-t-2 border-ink bg-panel-secondary py-4 text-ink hover:brightness-110"
                :class="{ 'brightness-125': filter === 'pins' }"
                @click="filter = 'pins'"
            >
                Pins
            </button>
            <div class="flex-1 border-t-2 border-ink bg-panel-secondary-subtle"></div>
            <button
                type="button"
                class="cursor-pointer border-t-2 border-ink bg-panel-primary-strong py-4 hover:brightness-110"
                :class="{ 'brightness-125': filter === 'archive' }"
                @click="filter = 'archive'"
            >
                Archive
            </button>
        </div>

        <div class="flex min-h-0 min-w-0 flex-1 flex-col bg-surface">
            <div
                class="flex flex-wrap items-center gap-2.5 border-b border-border px-4 py-2.5"
            >
                <span
                    v-for="[tag, count] in tagCounts"
                    :key="tag"
                    class="cursor-pointer px-2.5 py-1 font-mono text-[13px]"
                    :class="
                        activeTag === tag
                            ? 'bg-tertiary text-ink'
                            : 'text-on-surface/60 hover:bg-surface-overlay hover:text-on-surface'
                    "
                    @click="activeTag = activeTag === tag ? null : tag"
                    >#{{ tag }}<span class="opacity-60"> {{ count }}</span></span
                >
                <input
                    v-model="search"
                    type="text"
                    placeholder="/find title, body or task…"
                    class="ml-auto min-w-[160px] bg-transparent px-2 py-1 text-right font-mono text-sm text-on-surface outline-none placeholder:text-neutral"
                />
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto px-3">
                <NoteLogRow
                    v-for="note in filteredNotes"
                    :key="note.id"
                    :note="note"
                    @open-todo="$emit('open-todo')"
                />
                <p
                    v-if="!filteredNotes.length"
                    class="p-8 text-center font-mono text-sm text-on-surface/50"
                >
                    No notes here.
                </p>
            </div>

            <div class="mt-auto border-t border-border p-4">
                <div
                    class="flex items-center gap-3 bg-surface-raised px-4 py-3.5"
                    :class="{ 'border-l-4 border-tertiary': capture.trim() }"
                >
                    <span class="font-mono text-lg text-tertiary">&gt;</span>
                    <input
                        v-model="capture"
                        type="text"
                        placeholder="call the roofer back [ ] friday #roof"
                        class="flex-1 bg-transparent font-mono text-base text-on-surface outline-none placeholder:text-neutral"
                        @keydown.enter="logCapture"
                    />
                    <span
                        v-if="capture.trim()"
                        class="cursor-pointer rounded-full bg-tertiary px-4 py-2 font-sans text-[11px] font-semibold tracking-wide text-ink uppercase hover:bg-tertiary-strong"
                        @click="logCapture"
                        >Log it</span
                    >
                </div>
                <div class="flex items-center gap-4 px-5 pt-2 font-mono text-xs text-neutral">
                    <span
                        >[ ] makes a task · dates like "friday" become due dates · #tag
                        files it</span
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import type { Note } from '@/types';

import NoteLogRow from './NoteLogRow.vue';

const props = defineProps<{ notes: Note[] }>();
defineEmits<{ (e: 'open-todo'): void }>();

const filter = ref<'all' | 'open' | 'pins' | 'archive'>('all');
const activeTag = ref<string | null>(null);
const search = ref('');
const capture = ref('');

const tagCounts = computed(() => {
    const counts = new Map<string, number>();
    for (const note of props.notes) {
        if (note.archived) continue;
        for (const tag of note.tags ?? []) {
            counts.set(tag, (counts.get(tag) ?? 0) + 1);
        }
    }
    return [...counts.entries()].sort((a, b) => b[1] - a[1]);
});

const filteredNotes = computed(() => {
    let list = props.notes;

    if (filter.value === 'archive') {
        list = list.filter((n) => n.archived);
    } else {
        list = list.filter((n) => !n.archived);
        if (filter.value === 'open') list = list.filter((n) => n.tasks.some((t) => !t.done));
        if (filter.value === 'pins') list = list.filter((n) => n.pinned);
    }

    if (activeTag.value) {
        list = list.filter((n) => (n.tags ?? []).includes(activeTag.value as string));
    }

    if (search.value.trim()) {
        const q = search.value.trim().toLowerCase();
        list = list.filter(
            (n) =>
                (n.title ?? '').toLowerCase().includes(q) ||
                (n.content ?? '').toLowerCase().includes(q) ||
                n.tasks.some((t) => t.name.toLowerCase().includes(q)),
        );
    }

    return list;
});

function logCapture() {
    const text = capture.value.trim();
    if (!text) return;
    router.post(
        '/notes/quick-capture',
        { text },
        { preserveScroll: true, onSuccess: () => (capture.value = '') },
    );
}
</script>
