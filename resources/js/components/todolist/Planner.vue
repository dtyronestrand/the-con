<template>
    <div class="flex ml-8 items-center justify-center flex-col">
    <CircleArrowLeft class="text-white size-8"/>
         <CircleArrowRight class="text-white size-8"/>
    </div>
    <div class="w-full ml-4 mr-8 week-container md:gap-4 md:grid md:grid-cols-3">
        <div v-for="(day, index) in weekView" :key="day.format('YYYY-MM-DD')" :class="`day-${index}`" class="  col-span-1" >
        <BarWithTitle :classList="'text-2xl'" background="var(--indigo)" >
        <p> {{ day.format('dddd') }}<span class="pl-4">{{ day.format('D') }}</span></p>   
        </BarWithTitle>
        <div v-if="props.tasks && props.tasks.length > 0">
        <span v-for="(task, taskIndex) in props.tasks" :key="task.id">
        <span v-if="task.due_date === day.format('YYYY-MM-DD')">
        <Task :task="task" class="w-full h-[1rem] "/>
        </span>
        </span>
        </div>
        <TaskInput class="w-full decorated ":disabled="false" :due-date="day.format('YYYY-MM-DD')"/>
        <span v-for="i in (8 - (props.tasks?.filter(task => task.due_date === day.format('YYYY-MM-DD')).length ?? 0) - 1)">
        <TaskInput class="w-full " :disabled="true" :due="day.format('YYYY-MM-DD')"/>
        </span>
        </div>
        <div class="">
        <BarWithTitle classList="text-2xl" background="var(--indigo)" >Someday</BarWithTitle>
        <div v-for="task in somedayTasks" :key="task.id">
        <Task :task="task" class="w-full h-[1rem] "/>
        </div>
        <TaskInput class="w-full decorated " :disabled="false" :due_date="null"/>
        <span v-for="i in (8 - somedayTasks.length - 1)">
        <TaskInput class="w-full " :disabled="true" :due_date="null"/>
        </span>
        </div>
    </div>
</template>

<script setup lang="ts">
import dayjs from'dayjs';
import BarWithTitle from '../lcars/BarWithTitle.vue';
import TaskInput from './tasks/TaskInput.vue';
import Task from './tasks/Task.vue';
import {computed, watchEffect, ref} from "vue";
import {CircleArrowLeft, CircleArrowRight} from 'lucide-vue-next'
import {useDateState} from '../../composables/useDateState';
import {usePage, useForm, router} from "@inertiajs/vue3";
import Bar from '../lcars/Bar.vue';
import type { Task as TaskType } from '@/types';

interface Props {
    tasks?: TaskType[];
}
const props = defineProps<Props>();
const page = usePage();
const {selectedYear, selectedMonth, selectedDate, setSelectedDate} = useDateState();
const somedayTasks = computed(() => props.tasks?.filter(task => !task.due_date) ?? []);
const weekView = computed(()=> {
    const selectedDay = dayjs(`${selectedYear.value}-${selectedMonth.value+1}-${selectedDate.value}`);
    const startOfWeek = selectedDay.startOf('week').add(1, 'day'); // Start on Monday

    return Array.from({length: 3}, (_, i) => startOfWeek.add(i, 'day'));
})
</script>

<style scoped>

</style>