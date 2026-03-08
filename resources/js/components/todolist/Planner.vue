<template>
    <CircleArrowLeft class="text-white ml-8 size-8"/>
    <div class="w-full ml-4 week-container md:gap-4 md:grid md:grid-cols-5 md:grid-row-9 md:item-start flex-row flex-wrap items-center">
        <div v-for="(day, index) in weekView" :key="day.format('YYYY-MM-DD')" :class="`day-${index}`" class="  col-span-1" >
        <BarWithTitle :classList="'text-2xl'" background="var(--indigo)" >
        <p> {{ day.format('dddd') }}<span class="pl-4">{{ day.format('D') }}</span></p>   
        </BarWithTitle>
        <div v-if="props.tasks && props.tasks.length > 0">
        <span v-for="(task, taskIndex) in props.tasks" :key="task.id">
        <div v-if="task.due === day.format('YYY-MM-DD')">
        <Task :task="task" class="w-full decorated mt-4"/>
        </div>
        </span>
        </div>
        <TaskInput class="w-full decorated mt-4":disabled="false" :due="day.format('YYYY-MM-DD')"/>
        <span v-for="i in (8 - (props.tasks?.filter(task => task.due === day.format('YYYY-MM-DD')).length ?? 0) - 1)">
        <TaskInput :disabled="true" :due="day.format('YYYY-MM-DD')"/>
        </span>
        </div>
        
        <CircleArrowRight class="text-white size-8"/>
        <div class="mr-12 -ml-16">
        <BarWithTitle classList="text-2xl" background="var(--indigo)" >Someday</BarWithTitle>
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

interface Props {
    tasks?: {
        id: number,
        name: string,
        due: string,
        notes: string,
        done: boolean,
        attachments: string[],
        subtasks: {
            name: string,
            done: boolean,
            notes: string,
            attachments: string[],
            due: string
        }[]
    }[]
}
const props = defineProps<Props>();
const page = usePage();
const {selectedYear, selectedMonth, selectedDate, setSelectedDate} = useDateState();

const weekView = computed(()=> {
    const selectedDay = dayjs(`${selectedYear.value}-${selectedMonth.value+1}-${selectedDate.value}`);
    const startOfWeek = selectedDay.startOf('week').add(1, 'day'); // Start on Monday

    return Array.from({length: 3}, (_, i) => startOfWeek.add(i, 'day'));
})
</script>

<style scoped>

</style>