<template>
    <div
        v-for="(view, index) in weekView"
        :key="view.format('YYYY-MM-DD')"
        :class="`day-${index}`"
        class="text-base-content bg-base-100  mx-5 col-span-1 text-lg"
    >
        <div class="grid grid-cols-2 grid-rows-1 border-b-4 border-base-content mb-5 mt-5" >
            <p >{{ view.format('dddd') }}</p>
            <p class="justify-self-end"> {{ view.format('D') }}</p>
            </div>
            <div  v-if="index < 5" >
            <span v-if="page.props.tasks && page.props.tasks.length > 0" >
            <span v-for="(task, taskIndex) in page.props.tasks" :key="task.id" >
            <div v-if="task.due_date === view.format('YYYY-MM-DD')" >
                <Task :task="task" @taskStatus="handleTaskStatus"/>
                
                
                </div>
            </span>
            </span>
            <TaskInput :disabled="false" :due="view.format('YYYY-MM-DD')" :calendarId="page.props.calendar.id" class="col-span-2 " />
            <span v-for ="i in (8-page.props.tasks.filter(task => task.due_date === view.format('YYYY-MM-DD')).length-1)" :key="i" class="col-span-2 ">
                <TaskInput :disabled="true" :due="view.format('YYYY-MM-DD')" :calendarId="page.props.calendar.id" />
            </span>
            </div>
            <span v-else>
                      <span v-if="page.props.tasks && page.props.tasks.length > 0" class="col-span-2">
            <span v-for="task in page.props.tasks" :key="task.id">
            <span v-if="task.due_date === view.format('YYYY-MM-DD')">
                <div role="button"  class="col-span-2 border-b border-base-content/10" :taskName="task" :disabled="true">{{ task.name }}</div>
                </span>
            </span>
            </span>
            <TaskInput :disabled="false" :due="view.format('YYYY-MM-DD')" :calendarId="page.props.calendar.id" class="col-span-2 " />
            <span v-for ="i in (3-taskList[index].tasks.length-1)" :key="i" class="col-span-2 ">
                <TaskInput :disabled="true" :due="view.format('YYYY-MM-DD')" :calendarId="page.props.calendar.id" />
            </span>
            </span>
    
    </div>
</template>

<script setup lang="ts">
import dayjs from 'dayjs';
import { computed, watchEffect, ref } from 'vue';
import { useDateState } from '../../composables/useDateState';
import {usePage, useForm, router} from '@inertiajs/vue3';
import TaskInput from './TaskInput.vue';
import Task from './Task.vue'; 
interface Props {
    task?: {
        name: string;
        due: string;
        notes: string;
        subtasks: { name: string; done: boolean }[];    
        done: boolean;
   
    };
}
const props = defineProps<Props>();
const page = usePage();
const { selectedYear, selectedMonth, selectedDate, setSelectedDate } = useDateState();


const weekView = computed(() => {
    const selectedDay = dayjs(`${selectedYear.value}-${selectedMonth.value + 1}-${selectedDate.value}`);
    const startOfWeek = selectedDay.startOf('week').add(1, 'day'); // Adjust to start on Monday

    return Array.from({ length: 7 }, (_, i) => startOfWeek.add(i, 'day'));
});

const taskList = computed(() => {
    return weekView.value.map(day => ({
        day: day.format('YYYY-MM-DD'),
        tasks: page.props.calendar.tasks.filter(task => task.due === day.format('YYYY-MM-DD'))
    }));
});
const selectedTask = ref(null);


const form = useForm({
    name: '',
    due_date: '',
    done: false,
    notes: '',
    subtasks: [],
    user_id: page.props.auth.user.id,
    calendar_id: page.props.calendar.id
});

const submitForm = () => {
    if (selectedTask.value) {
        form.name = selectedTask.value.name;
        form.due_date = selectedTask.value.due_date;
        form.subtasks = Array.isArray(selectedTask.value.subtasks) ? selectedTask.value.subtasks : [];
        form.user_id = page.props.auth.user.id;
        form.calendar_id = page.props.calendar.id;
        
        form.put(`/tasks/${selectedTask.value.id}`);
    }
};
const handleTaskStatus= (updatedTask) => {
    console.log('handleTaskStatus called with:', updatedTask);
    router.put(
        `/tasks/${updatedTask.id}`,
        {
            id: updatedTask.id,
            name: updatedTask.name,
            due_date: updatedTask.due_date,
            notes: updatedTask.notes,
            subtasks: updatedTask.subtasks,
            user_id: page.props.auth.user.id,
            calendar_id: page.props.calendar.id,
            done: updatedTask.done 
        },
        {
            onSuccess: () => {
                router.reload();
            },
            onError: () => {
                // Handle error
            }
        }
    );
};
const openModal = (task) => {
    selectedTask.value = { ...task, subtasks: task.subtasks || [] };
};

const closeModal = () => {
    selectedTask.value = null;
};

const addSubtask = () => {
    if (selectedTask.value) {
        selectedTask.value.sub_tasks.push({ name: '', done: false });// Add a new subtask with default values
    }
};

const removeSubtask = (index: number) => {
    if (selectedTask.value) {
        selectedTask.value.sub_tasks.splice(index, 1);
    }
};
watchEffect(()=>{
    console.log(taskList.value);
})
</script>

<style scoped>
@reference "../../../css/app.css";

.day-5 {
    @apply row-span-1;
    grid-column: 6;
    grid-row: 1;
}
.day-6 {
    @apply row-span-1;
    grid-column: 6;
    grid-row: 2;
}

.done {
    text-decoration: line-through;
    color: #888888;
}
</style>