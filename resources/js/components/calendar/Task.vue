<template>
<div :class="{done: props.task.done}" class="text-base-content col-span-2 flex justify-between group border-b border-base-content/10 cursor-pointer"><p @click="openModal(props.task)" role="button" >{{ props.task.name }} </p><Checkmark :checked="checkedValue" type="checkbox"   class="opacity-0 group-hover:opacity-100" @updateChecked="handleTaskStatus"/></div>
                <!-- Modal -->
              <TaskModal v-if="selectedTask" :task="selectedTask" @close="closeModal" @updateTask="handleTask"/>
              
            
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import {router, usePage} from '@inertiajs/vue3';
import {useDateState} from '../../composables/useDateState';
import type { Task } from '../../types';
import Checkmark from './Checkmark.vue'; 
import TaskModal from './TaskModal.vue';

interface Props {
    task: Task
};
const props = defineProps<Props>();

const { selectedYear, selectedMonth, selectedDate, setSelectedDate } = useDateState();
const selectedTask = ref(null);
const page = usePage();

const checkedValue = computed(() => props.task.done);
const handleTaskStatus = () => {
    const updatedTask = {...props.task, done: !props.task.done};
    handleTask(updatedTask);
}
const emit = defineEmits(['taskStatus']);
const handleTask = (updatedTask: Task) => {
   const requestData = {
        id: updatedTask.id,
        name: updatedTask.name,
        due_date: updatedTask.due_date,
        notes: updatedTask.notes,
        sub_tasks: updatedTask.sub_tasks,
        user_id: page.props.auth.user.id,
        calendar_id: page.props.calendar.id,
        done: updatedTask.done  
    };
   console.log('Sending request data:', requestData);
   router.put(
    `/tasks/${updatedTask.id}`,
    requestData,
    {
        onSuccess: () => {
            router.reload();
        },
        onError: () => {
            console.error('Error updating task');
        }
    }
)
}

const openModal = (task) => {
    selectedTask.value = { ...task };
};

const closeModal = () => {
    selectedTask.value = null;
};

const addSubtask = () => {
    if (selectedTask.value) {
        selectedTask.value.sub_tasks = selectedTask.value.sub_tasks || [];
        selectedTask.value.sub_tasks.push('');
    }
};

const removeSubtask = (index) => {
    if (selectedTask.value && selectedTask.value.sub_tasks) {
        selectedTask.value.sub_tasks.splice(index, 1);
    }
};

const submitForm = () => {
    closeModal();
};



</script>

<style scoped>
.done{
    text-decoration: line-through;
    color: var(--color-neutral-300);
}
</style>