<template>
 <div
        class="relative shadow-md rounded-md p-3 flex flex-col group overflow-hidden"
        :style="{ backgroundColor: form.color, color:form.color === 'var(--indigo)' ? 'white' : 'black', width: form.width + 'px', height: form.height + 'px', resize: 'both' }"
        @mouseup="onResize"
    >
        <div class="flex justify-between items-center mb-2 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
            <div class="flex space-x-1">
                <button
                    v-for="c in colors" :key="c"
                    @click="form.color = c"
                    class="w-4 h-4 rounded-full border border-black/10 focus-visible:ring-2 focus-visible:ring-offset-1 focus-visible:ring-blue-500"
                    :style="{ backgroundColor: c }"
                    :aria-label="`Set note color to ${c}`"
                    :title="`Set note color to ${c}`"
    <div
        class="group relative flex flex-col overflow-hidden rounded-md p-3 shadow-md"
        :style="{
            backgroundColor: form.color,
            color: form.color === 'var(--indigo)' ? 'white' : 'black',
            width: form.width + 'px',
            height: form.height + 'px',
            resize: 'both',
        }"
        @mouseup="onResize"
    >
        <div
            class="mb-2 flex items-center justify-between opacity-0 transition-opacity group-hover:opacity-100 focus-within:opacity-100"
        >
            <div class="flex space-x-1">
                <button
                    v-for="c in colors"
                    :key="c"
                    @click="form.color = c"
                    class="h-4 w-4 rounded-full border border-black/10 focus-visible:ring-2 focus-visible:ring-black focus-visible:ring-offset-1 focus-visible:outline-none"
                    :style="{ backgroundColor: c }"
                    :aria-label="`Set color to ${c}`"
                    :title="`Set color to ${c}`"
                ></button>
            </div>
            <button
                @click="deleteNote"
                @mouseup.stop
                class="text-red-500 hover:text-red-700 focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-1 rounded-sm p-0.5"
                aria-label="Delete note"
                title="Delete note"
            >
                <Trash2 :size="16" />
                class="rounded text-red-500 hover:text-red-700 focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:outline-none"
                aria-label="Delete note"
                title="Delete note"
            >
                <Trash2 :size="16" aria-hidden="true" />
            </button>
        </div>

        <div class="flex-1 overflow-auto">
            <QuillEditor
                v-model:content="form.content"
                contentType="html"
                theme="snow"
                toolbar="minimal"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { debounce } from 'lodash';
import { Trash2 } from 'lucide-vue-next';
import { watch } from 'vue';

const props = defineProps<{ note: any }>();
const form = useForm({
    content: props.note.content,
    color: props.note.color,
    width: props.note.width,
    height: props.note.height,
});

const colors = [
    'var(--indigo)',
    'var(--anakiwa)',
    'var(--blue)',
    'var(--periwinkle)',
];

const saveNote = debounce(() => {
    form.put(`/sticky-notes/${props.note.id}`, {
        preserveScroll: true,
    });
}, 500);

const deleteNote = () => {
    saveNote.cancel(); // Cancel any pending
    form.delete(`/sticky-notes/${props.note.id}`, {
        preserveScroll: true,
    });
};

watch(() => form.content, saveNote);
watch(() => form.color, saveNote);

const onResize = (event: Event) => {
    if (!event.currentTarget) return;
    // currentTarget ALWAYS refers to the div the @mouseup listener is attached to
    const element = event.currentTarget as HTMLElement;
    const newWidth = element.offsetWidth;
    const newHeight = element.offsetHeight;

    // Only trigger a save if the dimensions actually changed
    // (This stops it from saving to the DB every time you just click around)
    if (form.width !== newWidth || form.height !== newHeight) {
        form.width = newWidth;
        form.height = newHeight;
        saveNote();
    }
};
</script>

<style scoped>
:deep(.ql-toolbar),
:deep(.ql-container) {
    border: none !important;
}
</style>
