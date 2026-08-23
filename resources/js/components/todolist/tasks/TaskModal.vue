<template>
    <div
        v-if="task"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70"
        @click="closeModal"
    >
        <div
            class="mx-4 h-auto w-full max-w-md rounded-lg rounded-xl border border-panel-secondary-strong bg-surface-overlay p-6 text-on-surface"
            @click.stop
        >
            <form @submit.prevent="saveForm" class="space-y-4">
                <input
                    v-if="taskToUpdate"
                    v-model="taskToUpdate.name"
                    type="text"
                    class="decorated w-full"
                    placeholder="Task Name"
                />
                <div v-if="taskToUpdate">
                    <div class="mb-2 flex items-center justify-between">
                        <p>Subtasks</p>
                        <button
                            @click.prevent="addSubtask"
                            class="px-2 text-lg text-tertiary"
                        >
                            +
                        </button>
                    </div>
                    <div
                        v-for="(subtask, index) in taskToUpdate.sub_tasks"
                        :key="index"
                        class="group mb-2 flex items-center"
                    >
                        <input
                            v-model="subtask.name"
                            type="text"
                            :class="{ 'line-through': subtask.done }"
                            class="input mr-2 w-full border-none"
                            placeholder="Subtask Name"
                        />
                        <input
                            class="mr-2 opacity-0 group-hover:opacity-100"
                            :checked="subtask.done"
                            @change="subtask.done = !subtask.done"
                            type="checkbox"
                        />
                        <button
                            @click.prevent="removeSubtask(index)"
                            class="px-2 text-lg text-error"
                        >
                            -
                        </button>
                    </div>
                </div>

                <div v-if="taskToUpdate">
                    <p class="mb-2">Notes</p>
                    <QuillEditor
                        theme="snow"
                        v-model:content="taskToUpdate.notes"
                        contentType="html"
                        class="mb-2"
                    />
                </div>

                <div class="flex gap-2">
                    <Button
                        type="submit"
                        background="var(--tertiary)"
                        style="color: var(--ink)"
                        classList="left-round "
                        >Save</Button
                    >
                    <Button
                        @click="closeModal"
                        type="button"
                        background="var(--panel-secondary)"
                        classList="right-round"
                        >Cancel</Button
                    >
                    <Trash2 class="cursor-pointer text-error" @click="deleteTask" />
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { Task } from '@/types';

import { router } from '@inertiajs/vue3';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

import Button from '../../lcars/Button.vue';
interface Props {
    task?: Task | null;
}

const props = defineProps<Props>();
const emit = defineEmits(['close', 'updateTask']);
const taskToUpdate = ref(props.task ? { ...props.task } : null);

const addSubtask = () => {
    if (taskToUpdate.value) {
        (taskToUpdate.value.sub_tasks ??= []).push({
            name: '',
            done: false,
            id: Date.now(),
            due_date: null,
        });
    }
};

const removeSubtask = (index: number) => {
    taskToUpdate.value?.sub_tasks?.splice(index, 1);
};

const saveForm = () => {
    if (taskToUpdate.value) {
        console.log('Saving task with notes:', taskToUpdate.value.notes);
        emit('updateTask', taskToUpdate.value);
        closeModal();
    }
};
const deleteTask = () => {
    router.delete(`/tasks/${taskToUpdate.value?.id}`, {
        onSuccess: () => {
            closeModal();
        },
    });
};

const closeModal = () => {
    emit('close');
};
</script>

<style scoped>
input:focus {
    border-color: var(--tertiary);
    box-shadow: 0 0 0 3px color-mix(in oklab, var(--tertiary) 40%, transparent);
    outline-color: var(--tertiary);
}
.decorated {
    border-left: 0.2rem solid var(--panel-primary);
    padding-right: 0.5rem;
    padding-left: 0.5rem;
    border-right: 0.2rem solid var(--panel-primary);
    caret-color: var(--on-surface);
}
</style>
