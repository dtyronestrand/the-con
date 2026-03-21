<template>
    <div
        :class="{ done: props.task.done }"
        class="group col-span-2 flex cursor-pointer flex-row justify-between border-b border-(--indigo) text-white"
    >
        <p @click="openModal(props.task)" role="button">
            {{ props.task.name }}
        </p>
        <Checkmark
            :checked="checkedValue"
            type="checkbox"
            class="opacity-0 group-hover:opacity-100"
            @updateChecked="handleTaskStatus"
        />
    </div>
    <!-- Modal -->
    <TaskModal
        v-if="selectedTask"
        :task="selectedTask"
        @close="closeModal"
        @updateTask="handleTask"
    />
</template>

<script setup lang="ts">
import type { Task } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useDateState } from '../../../composables/useDateState';
import Checkmark from './Checkmark.vue';
import TaskModal from './TaskModal.vue';

interface Props {
    task: Task;
}
const props = defineProps<Props>();

const { selectedYear, selectedMonth, selectedDate, setSelectedDate } =
    useDateState();
const selectedTask = ref<Task | null>(null);
const page = usePage();

const checkedValue = computed(() => props.task.done);
const handleTaskStatus = () => {
    const updatedTask = {
        ...props.task,
        done: !props.task.done,
        due: props.task.due_date,
    };
    handleTask(updatedTask);
};
const emit = defineEmits(['taskStatus']);
const handleTask = (updatedTask: Task) => {
    const requestData = {
        id: updatedTask.id,
        name: updatedTask.name,
        due_date: updatedTask.due_date,
        notes: updatedTask.notes,
        sub_tasks: JSON.stringify(updatedTask.sub_tasks || []),
        user_id: page.props.auth.user.id,
        done: updatedTask.done,
    };
    console.log('Sending request data:', requestData);
    router.put(`/tasks/${updatedTask.id}`, requestData, {
        onSuccess: () => {
            router.reload();
        },
        onError: () => {
            console.error('Error updating task');
        },
    });
};

const openModal = (task: Task) => {
    selectedTask.value = { ...task };
};

const closeModal = () => {
    selectedTask.value = null;
};

const addSubtask = () => {
    if (selectedTask.value) {
        selectedTask.value.sub_tasks = selectedTask.value.sub_tasks || [];
        selectedTask.value.sub_tasks.push();
    }
};

const removeSubtask = (index: number) => {
    if (selectedTask.value && selectedTask.value.sub_tasks) {
        selectedTask.value.sub_tasks.splice(index, 1);
    }
};

const submitForm = () => {
    closeModal();
};
</script>

<style scoped>
.done {
    text-decoration: line-through;
    color: var(--color-neutral-300);
}
</style>
