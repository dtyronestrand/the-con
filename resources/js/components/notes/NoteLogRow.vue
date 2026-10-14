<template>
    <div class="border-b border-border">
        <!-- VIEW -->
        <div
            v-if="mode === 'view'"
            class="group flex gap-4 px-1 py-[15px] hover:bg-surface-raised"
        >
            <div
                class="w-[100px] shrink-0 pt-[3px] font-mono text-xs text-neutral"
            >
                {{ dateLine1 }}<br />{{ dateLine2 }}
            </div>
            <div class="w-1 shrink-0" :style="{ background: note.color }"></div>
            <div class="flex min-w-0 flex-1 flex-col gap-1.5">
                <div class="flex flex-wrap items-baseline gap-2.5">
                    <span
                        v-if="note.pinned"
                        class="font-mono text-xs text-tertiary"
                        >★ PINNED</span
                    >
                    <span
                        class="font-sans text-lg font-semibold text-on-surface"
                        >{{ note.title || 'Untitled' }}</span
                    >
                </div>

                <div
                    v-if="note.content"
                    class="max-w-[68ch] font-sans text-[15px] leading-relaxed text-on-surface/80"
                    v-html="note.content"
                ></div>

                <div
                    v-if="note.tasks.length || note.demoted_tasks.length"
                    class="mt-0.5 flex flex-col gap-0.5 font-mono text-sm"
                >
                    <div
                        v-for="task in note.tasks"
                        :key="task.id"
                        class="flex cursor-pointer items-center gap-2.5 px-2.5 py-0.5"
                        :class="
                            task.done
                                ? 'bg-tertiary-subtle text-ink'
                                : 'text-on-surface hover:bg-surface-overlay'
                        "
                        @click="toggleDone(task)"
                    >
                        <span>{{ task.done ? '[x]' : '[ ]' }}</span>
                        <span :class="{ 'line-through': task.done }">{{
                            task.name
                        }}</span>
                        <span
                            v-if="task.done"
                            class="ml-auto font-sans text-[11px] font-medium tracking-wide uppercase opacity-70"
                            >Done · {{ formatShort(task.updated_at) }}</span
                        >
                        <span
                            v-else-if="isOverdue(task)"
                            class="ml-auto bg-neutral px-2 py-0.5 font-sans text-[11px] font-medium tracking-wide text-on-surface uppercase"
                            >Overdue · {{ formatShort(task.due_date) }}</span
                        >
                        <span
                            v-else-if="task.due_date"
                            class="ml-auto font-sans text-[11px] font-medium tracking-wide text-on-surface/60 uppercase"
                            >{{ formatShort(task.due_date) }}</span
                        >
                    </div>
                    <div
                        v-for="(demoted, i) in note.demoted_tasks"
                        :key="'demoted-' + i"
                        class="flex items-baseline gap-2.5 border-l-2 border-neutral px-2.5 py-0.5 font-sans"
                    >
                        <span class="text-[15px] text-on-surface/60">{{
                            demoted.text
                        }}</span>
                        <span
                            class="bg-neutral px-2 py-0.5 text-[11px] font-medium tracking-wide text-on-surface uppercase"
                            >No longer a task</span
                        >
                        <span
                            class="cursor-pointer text-[11px] font-medium tracking-wide text-tertiary uppercase hover:text-tertiary-strong"
                            @click="promote(i)"
                            >Make a task</span
                        >
                    </div>
                </div>

                <div class="mt-0.5 font-mono text-xs text-neutral">
                    <span v-for="t in note.tags" :key="t">#{{ t }} </span>
                    <span v-if="note.tags.length && note.tasks.length">· </span>
                    <span v-if="note.tasks.length" class="text-on-surface/60"
                        >{{ note.tasks.length }} task<span
                            v-if="note.tasks.length !== 1"
                            >s</span
                        ><span v-if="openCount"
                            >, {{ openCount }} open</span
                        ></span
                    >
                </div>
            </div>
            <div
                class="flex items-start gap-3.5 pt-1 font-sans text-[11px] font-medium tracking-wide text-on-surface/60 uppercase opacity-0 transition-opacity group-hover:opacity-100"
            >
                <span
                    class="cursor-pointer hover:text-tertiary"
                    @click="togglePin"
                    >Pin</span
                >
                <span
                    class="cursor-pointer hover:text-on-surface"
                    @click="enterEdit"
                    >Edit</span
                >
                <span
                    class="cursor-pointer hover:text-on-surface"
                    @click="mode = 'deleting'"
                    >Del</span
                >
            </div>
        </div>

        <!-- DELETE CONFIRM -->
        <div
            v-else-if="mode === 'deleting'"
            class="my-1.5 flex flex-wrap items-center gap-5 bg-error px-4.5 py-4"
        >
            <div class="flex min-w-[380px] flex-1 flex-col gap-1.5">
                <div
                    class="font-display text-sm tracking-wide text-on-surface uppercase"
                >
                    {{ deleteHeadline }}
                </div>
                <div
                    v-if="openTasks.length"
                    class="max-w-[68ch] font-sans text-sm leading-normal text-on-surface/90"
                >
                    {{ openTaskNames }} still open and will disappear from
                    To&nbsp;Do as well. This can't be undone.
                </div>
                <div
                    v-else
                    class="font-sans text-sm leading-normal text-on-surface/90"
                >
                    This can't be undone.
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span
                    v-if="note.tasks.length"
                    class="cursor-pointer rounded-full border border-on-surface/50 px-4.5 py-2 font-sans text-[11px] font-semibold tracking-wide text-on-surface uppercase hover:bg-on-surface/15"
                    @click="destroyNote(true)"
                    >Keep the tasks</span
                >
                <span
                    class="cursor-pointer px-3.5 py-2 font-sans text-[11px] font-semibold tracking-wide text-on-surface uppercase hover:underline"
                    @click="mode = 'view'"
                    >Cancel</span
                >
                <span
                    class="cursor-pointer rounded-full bg-on-surface px-5 py-2.5 font-sans text-[11px] font-semibold tracking-wide text-error uppercase hover:bg-white"
                    @click="destroyNote(false)"
                    >Delete all</span
                >
            </div>
        </div>

        <!-- EDITING -->
        <div
            v-else
            class="my-1.5 flex gap-4 border-l-4 border-tertiary bg-surface-overlay px-4.5 py-4"
        >
            <div
                class="w-[96px] shrink-0 pt-1.5 font-mono text-xs text-neutral"
            >
                {{ dateLine1 }}<br />{{ dateLine2 }}
            </div>
            <div class="flex min-w-0 flex-1 flex-col gap-3">
                <input
                    v-model="draft.title"
                    type="text"
                    placeholder="Untitled"
                    class="w-full border-0 border-b border-tertiary bg-surface-raised px-2.5 py-2 font-sans text-xl font-semibold text-on-surface outline-none"
                />

                <div class="flex items-center gap-2">
                    <QuillEditor
                        v-model:content="draft.content"
                        contentType="html"
                        theme="snow"
                        :toolbar="quillToolbar"
                        class="min-w-0 flex-1"
                    />
                    <button
                        type="button"
                        class="shrink-0 self-start bg-tertiary-subtle px-2 py-1 font-mono text-xs text-ink"
                        @click="focusNewTask"
                    >
                        [&nbsp;]&nbsp;task
                    </button>
                </div>

                <div class="flex flex-col gap-2 bg-surface-raised px-4 py-3.5">
                    <div
                        v-for="task in note.tasks"
                        :key="task.id"
                        class="flex flex-col gap-2.5"
                    >
                        <div
                            class="flex cursor-pointer items-center gap-2.5 px-2.5 py-1 font-mono text-sm"
                            :class="
                                task.done
                                    ? 'bg-tertiary-subtle text-ink'
                                    : 'text-on-surface hover:bg-surface-overlay'
                            "
                            @click="toggleDone(task)"
                        >
                            <span>{{ task.done ? '[x]' : '[ ]' }}</span>
                            <span
                                class="flex-1"
                                :class="{ 'line-through': task.done }"
                                >{{ task.name }}</span
                            >
                            <span
                                class="font-sans text-[11px] font-medium tracking-wide uppercase opacity-70"
                                >Task #{{ task.id
                                }}<span v-if="task.done">
                                    · done
                                    {{ formatShort(task.updated_at) }}</span
                                ></span
                            >
                        </div>

                        <div
                            v-if="!task.done && dueOpenFor === task.id"
                            class="flex flex-col gap-2.5 border-l-2 border-tertiary bg-surface px-3 py-2.5"
                            @click.stop
                        >
                            <div class="flex flex-wrap items-center gap-2.5">
                                <span
                                    class="font-sans text-[11px] font-medium tracking-wide text-on-surface/60 uppercase"
                                    >Due</span
                                >
                                <span
                                    v-if="task.due_date"
                                    class="bg-panel-secondary-strong px-2.5 py-1 font-mono text-[13px] text-on-surface"
                                    >{{ formatShort(task.due_date) }}</span
                                >
                                <button
                                    type="button"
                                    class="px-2.5 py-1 font-mono text-[13px] text-on-surface/60 hover:bg-surface-overlay hover:text-on-surface"
                                    @click="setDue(task, todayIso())"
                                >
                                    Today
                                </button>
                                <button
                                    type="button"
                                    class="px-2.5 py-1 font-mono text-[13px] text-on-surface/60 hover:bg-surface-overlay hover:text-on-surface"
                                    @click="setDue(task, tomorrowIso())"
                                >
                                    Tomorrow
                                </button>
                                <input
                                    type="date"
                                    class="border border-border bg-surface px-1.5 py-1 font-mono text-xs text-on-surface"
                                    :value="task.due_date ?? ''"
                                    @change="
                                        setDue(
                                            task,
                                            ($event.target as HTMLInputElement)
                                                .value || null,
                                        )
                                    "
                                />
                                <button
                                    type="button"
                                    class="px-2.5 py-1 font-mono text-[13px] text-on-surface/60 hover:bg-surface-overlay hover:text-on-surface"
                                    @click="setDue(task, null)"
                                >
                                    Clear
                                </button>
                            </div>
                            <div class="flex items-center gap-3.5">
                                <span
                                    class="font-sans text-[11px] font-medium tracking-wide text-on-surface/60 uppercase"
                                    >Calendar</span
                                >
                                <span
                                    class="ml-auto cursor-pointer font-sans text-[11px] font-medium tracking-wide text-on-surface/60 uppercase hover:text-on-surface"
                                    @click="$emit('open-todo')"
                                    >Open in To&nbsp;Do</span
                                >
                                <span
                                    class="cursor-pointer font-sans text-[11px] font-medium tracking-wide text-on-surface/60 uppercase hover:text-on-surface"
                                    @click="unlink(task)"
                                    >Unlink</span
                                >
                            </div>
                        </div>
                        <div
                            v-else-if="!task.done"
                            class="flex items-center gap-2.5 pl-2.5"
                        >
                            <button
                                v-if="!task.due_date"
                                type="button"
                                class="border border-dashed border-border px-1.5 py-0.5 font-sans text-[11px] font-medium tracking-wide text-on-surface/60 uppercase"
                                @click="dueOpenFor = task.id"
                            >
                                + Due
                            </button>
                            <button
                                v-else
                                type="button"
                                class="font-sans text-[11px] font-medium tracking-wide uppercase"
                                :class="
                                    isOverdue(task)
                                        ? 'bg-neutral px-2 py-0.5 text-on-surface'
                                        : 'text-on-surface/60'
                                "
                                @click="dueOpenFor = task.id"
                            >
                                <span v-if="isOverdue(task)">Overdue · </span
                                >{{ formatShort(task.due_date) }}
                            </button>
                            <span
                                class="cursor-pointer font-sans text-[11px] font-medium tracking-wide text-on-surface/60 uppercase hover:text-on-surface"
                                @click="unlink(task)"
                                >Unlink</span
                            >
                        </div>
                    </div>

                    <div
                        v-for="(demoted, i) in note.demoted_tasks"
                        :key="'demoted-' + i"
                        class="flex items-baseline gap-2.5 border-l-2 border-neutral px-2.5 py-1 font-mono text-sm"
                    >
                        <span class="text-[15px] text-on-surface/60">{{
                            demoted.text
                        }}</span>
                        <span
                            class="bg-neutral px-2 py-0.5 font-sans text-[11px] font-medium tracking-wide text-on-surface uppercase"
                            >No longer a task</span
                        >
                        <span
                            class="cursor-pointer font-sans text-[11px] font-medium tracking-wide text-tertiary uppercase hover:text-tertiary-strong"
                            @click="promote(i)"
                            >Make a task</span
                        >
                    </div>

                    <div
                        class="flex items-center gap-2.5 px-2.5 py-1 font-mono text-sm text-on-surface/60"
                    >
                        <span>[ ]</span>
                        <input
                            ref="newTaskInput"
                            v-model="newTaskName"
                            type="text"
                            placeholder="new task…"
                            class="flex-1 bg-transparent text-on-surface outline-none placeholder:text-on-surface/40"
                            @keydown.enter="addTask"
                        />
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3.5">
                    <div class="flex flex-wrap items-center gap-1.5">
                        <span
                            v-for="(t, i) in draft.tags"
                            :key="t"
                            class="flex items-center gap-2 bg-neutral px-2.5 py-1 font-mono text-[13px] text-on-surface"
                        >
                            #{{ t
                            }}<span
                                class="cursor-pointer text-panel-secondary-subtle"
                                @click="draft.tags.splice(i, 1)"
                                >×</span
                            >
                        </span>
                        <input
                            v-model="newTag"
                            type="text"
                            placeholder="type a tag…"
                            class="border border-dashed border-border px-2.5 py-1 font-mono text-[13px] text-on-surface/60 outline-none placeholder:text-on-surface/60"
                            @keydown.enter.prevent="addTag"
                        />
                    </div>
                    <div class="ml-auto flex items-center gap-2">
                        <span
                            class="font-sans text-[11px] font-medium tracking-wide text-on-surface/60 uppercase"
                            >Color</span
                        >
                        <span
                            v-for="c in colorOptions"
                            :key="c"
                            class="h-3.5 w-3.5 cursor-pointer"
                            :style="{
                                background: c,
                                outline:
                                    draft.color === c
                                        ? '2px solid var(--tertiary)'
                                        : 'none',
                                outlineOffset: '2px',
                            }"
                            @click="draft.color = c"
                        ></span>
                    </div>
                </div>

                <div
                    class="flex items-center gap-4 border-t border-border pt-3.5"
                >
                    <span
                        class="cursor-pointer font-sans text-[11px] font-medium tracking-wide text-on-surface/60 uppercase hover:text-tertiary"
                        @click="togglePin"
                        >Pin</span
                    >
                    <span
                        class="cursor-pointer font-sans text-[11px] font-medium tracking-wide text-on-surface/60 uppercase hover:text-on-surface"
                        @click="toggleArchive"
                        >Archive</span
                    >
                    <span
                        class="cursor-pointer font-sans text-[11px] font-medium tracking-wide text-on-surface/60 uppercase hover:text-on-surface"
                        @click="mode = 'deleting'"
                        >Delete</span
                    >
                    <span class="ml-auto font-mono text-xs text-neutral">{{
                        autosaveLabel
                    }}</span>
                    <span
                        class="cursor-pointer rounded-full bg-tertiary px-4.5 py-2 font-sans text-[11px] font-semibold tracking-wide text-ink uppercase hover:bg-tertiary-strong"
                        @click="mode = 'view'"
                        >Done</span
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import dayjs from 'dayjs';
import { debounce } from 'lodash';
import { computed, nextTick, reactive, ref, watch } from 'vue';

import type { AppPageProps, Note, Task } from '@/types';

const props = defineProps<{ note: Note }>();
defineEmits<{ (e: 'open-todo'): void }>();

const page = usePage<AppPageProps>();

const mode = ref<'view' | 'editing' | 'deleting'>('view');
const dueOpenFor = ref<Task['id'] | null>(null);
const newTaskName = ref('');
const newTag = ref('');
const newTaskInput = ref<HTMLInputElement | null>(null);
const lastSavedAt = ref<Date | null>(null);

const colorOptions = ['#1034b1', '#0056c5', '#67abff', '#b1d9ff'];
const quillToolbar = [
    ['bold', 'italic', 'underline'],
    [{ list: 'bullet' }, { list: 'ordered' }],
    ['link'],
];

const draft = reactive({
    title: props.note.title ?? '',
    content: props.note.content ?? '',
    tags: [...(props.note.tags ?? [])],
    color: props.note.color,
});

const openTasks = computed(() => props.note.tasks.filter((t) => !t.done));
const openCount = computed(() => openTasks.value.length);

const dateLine1 = computed(() =>
    dayjs(props.note.created_at).format('DD MMM').toUpperCase(),
);
const dateLine2 = computed(() => dayjs(props.note.created_at).format('HH:mm'));

const deleteHeadline = computed(() =>
    props.note.tasks.length
        ? `Delete this note and its ${props.note.tasks.length} task${props.note.tasks.length === 1 ? '' : 's'}?`
        : 'Delete this note?',
);
const openTaskNames = computed(() => {
    const names = openTasks.value.map((t) => `“${t.name}”`);
    if (names.length <= 1) return names.join('') + (names.length ? ' is' : '');
    return (
        names.slice(0, -1).join(', ') +
        ' and ' +
        names[names.length - 1] +
        ' are'
    );
});

const autosaveLabel = computed(() =>
    lastSavedAt.value
        ? `AUTOSAVED ${dayjs(lastSavedAt.value).format('HH:mm:ss')} · ${props.note.tasks.length} TASKS SYNCED`
        : `${props.note.tasks.length} TASKS SYNCED`,
);

function formatShort(d?: string | null) {
    return d ? dayjs(d).format('DD MMM') : '';
}
function isOverdue(task: Task) {
    return (
        !task.done &&
        !!task.due_date &&
        dayjs(task.due_date).isBefore(dayjs(), 'day')
    );
}
function todayIso() {
    return dayjs().format('YYYY-MM-DD');
}
function tomorrowIso() {
    return dayjs().add(1, 'day').format('YYYY-MM-DD');
}

function enterEdit() {
    draft.title = props.note.title ?? '';
    draft.content = props.note.content ?? '';
    draft.tags = [...(props.note.tags ?? [])];
    draft.color = props.note.color;
    mode.value = 'editing';
}

const saveNote = debounce(() => {
    router.put(
        `/notes/${props.note.id}`,
        {
            title: draft.title,
            content: draft.content,
            tags: draft.tags,
            color: draft.color,
        },
        {
            preserveScroll: true,
            onSuccess: () => (lastSavedAt.value = new Date()),
        },
    );
}, 500);

watch(draft, () => {
    if (mode.value === 'editing') saveNote();
});

function togglePin() {
    router.put(
        `/notes/${props.note.id}`,
        { pinned: !props.note.pinned },
        { preserveScroll: true },
    );
}
function toggleArchive() {
    router.put(
        `/notes/${props.note.id}`,
        { archived: !props.note.archived },
        { preserveScroll: true },
    );
}

function destroyNote(keepTasks: boolean) {
    router.delete(`/notes/${props.note.id}`, {
        data: { keep_tasks: keepTasks },
        preserveScroll: true,
    });
}

function addTag() {
    const value = newTag.value.trim().replace(/^#/, '');
    if (value && !draft.tags.includes(value)) draft.tags.push(value);
    newTag.value = '';
}

function pushTaskUpdate(task: Task, changes: Partial<Task>) {
    const merged = { ...task, ...changes };
    router.put(
        `/tasks/${task.id}`,
        {
            id: merged.id,
            name: merged.name,
            due_date: merged.due_date,
            notes: merged.notes ?? null,
            sub_tasks: JSON.stringify(merged.sub_tasks ?? []),
            user_id: page.props.auth.user.id,
            done: merged.done,
        },
        { preserveScroll: true },
    );
}

function toggleDone(task: Task) {
    pushTaskUpdate(task, { done: !task.done });
}
function setDue(task: Task, due_date: string | null) {
    pushTaskUpdate(task, { due_date });
    dueOpenFor.value = null;
}
function unlink(task: Task) {
    router.delete(`/tasks/${task.id}`, { preserveScroll: true });
}
function addTask() {
    const name = newTaskName.value.trim();
    if (!name) return;
    router.post(
        `/notes/${props.note.id}/tasks`,
        { name },
        { preserveScroll: true, onSuccess: () => (newTaskName.value = '') },
    );
}
function promote(index: number) {
    router.post(
        `/notes/${props.note.id}/promote`,
        { index },
        { preserveScroll: true },
    );
}

function focusNewTask() {
    nextTick(() => newTaskInput.value?.focus());
}
</script>

<style scoped>
:deep(.ql-toolbar),
:deep(.ql-container) {
    border: none !important;
    background: var(--surface-raised);
}
:deep(.ql-editor) {
    color: var(--on-surface);
    font-family: 'IBM Plex Sans', sans-serif;
    font-size: 15px;
    min-height: 4rem;
}
</style>
