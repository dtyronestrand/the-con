<template>
    <div class="mb-4 ml-8 flex flex-row items-center justify-between">
        <Button
            @click="
                () =>
                    setSelectedDate(
                        selectedYear,
                        selectedMonth,
                        selectedDate - 1,
                    )
            "
            background="var(--anakiwa)"
            classList="left-round text-black text-4xl text-center size-8"
            >-</Button
        >
        <Button
            @click="
                () =>
                    setSelectedDate(
                        selectedYear,
                        selectedMonth,
                        selectedDate + 1,
                    )
            "
            background="var(--periwinkle)"
            classList="right-round justify-end text-black text-4xl text-center size-8"
            >+</Button
        >
    </div>
    <div
        class="week-container mr-8 ml-4 w-full px-[2rem] md:grid md:grid-cols-3 md:gap-4"
    >
        <div
            v-for="(day, index) in weekView"
            :key="day.format('YYYY-MM-DD')"
            :class="`day-${index}`"
            class="col-span-1"
        >
            <BarWithTitle :classList="'text-2xl'" background="var(--indigo)">
                <p>
                    {{ day.format('dddd')
                    }}<span class="pl-4">{{ day.format('D') }}</span>
                </p>
            </BarWithTitle>
            <div v-if="props.tasks && props.tasks.length > 0">
                <span v-for="task in props.tasks" :key="task.id">
                    <span
                        v-if="
                            dayjs(task.due_date).format('YYYY-MM-DD') ===
                            day.format('YYYY-MM-DD')
                        "
                    >
                        <Task :task="task" class="h-[1rem] w-full" />
                    </span>
                </span>
            </div>
            <TaskInput
                class="decorated w-full"
                :disabled="false"
                :due_date="day.format('YYYY-MM-DD')"
            />
            <span
                v-for="i in Math.max(
                    0,
                    8 -
                        (props.tasks?.filter(
                            (task) =>
                                task.due_date &&
                                dayjs(task.due_date).format('YYYY-MM-DD') ===
                                    day.format('YYYY-MM-DD'),
                        ).length ?? 0) -
                        1,
                )"
                :key="`empty-${day.format('YYYY-MM-DD')}-${i}`"
            >
                <TaskInput
                    class="w-full"
                    :disabled="true"
                    :due_date="day.format('YYYY-MM-DD')"
                />
            </span>
        </div>
        <div class="">
            <BarWithTitle classList="text-2xl" background="var(--indigo)"
                >Someday</BarWithTitle
            >
            <div v-for="task in somedayTasks" :key="task.id">
                <Task :task="task" class="h-[1rem] w-full" />
            </div>
            <TaskInput
                class="decorated w-full"
                :disabled="false"
                :due_date="null"
            />
            <span
                v-for="i in Math.max(0, 8 - somedayTasks.length - 1)"
                :key="`someday-empty-${i}`"
            >
                <TaskInput class="w-full" :disabled="true" :due_date="null" />
            </span>
        </div>
    </div>
</template>

<script setup lang="ts">
import dayjs from 'dayjs';
import { computed } from 'vue';

import type { Task as TaskType } from '@/types';

import { useDateState } from '../../composables/useDateState';
import BarWithTitle from '../lcars/BarWithTitle.vue';
import Button from '../lcars/Button.vue';

import Task from './tasks/Task.vue';
import TaskInput from './tasks/TaskInput.vue';

interface Props {
    tasks?: TaskType[];
}
const props = defineProps<Props>();
const { selectedYear, selectedMonth, selectedDate, setSelectedDate } =
    useDateState();
const somedayTasks = computed(
    () => props.tasks?.filter((task) => !task.due_date) ?? [],
);
const weekView = computed(() => {
    const selectedDay = dayjs(
        `${selectedYear.value}-${selectedMonth.value + 1}-${selectedDate.value}`,
    );

    return Array.from({ length: 3 }, (_, i) => selectedDay.add(i, 'day'));
});
</script>

<style scoped></style>
