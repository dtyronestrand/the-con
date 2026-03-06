<template>
    
      <Weekday/>
      <Someday/>
   
</template>

<script setup lang="ts">
import {useDateState} from '../../composables/useDateState';
import dayjs from 'dayjs';
import {computed, watchEffect} from 'vue';


const { selectedYear, selectedMonth, selectedDate, setSelectedDate } = useDateState();

const weekView = computed(() => {
    const selectedDay = dayjs(`${selectedYear.value}-${selectedMonth.value + 1}-${selectedDate.value}`);
    const startOfWeek = selectedDay.startOf('week');
    
    return Array.from({ length: 7 }, (_, i) => startOfWeek.add(i, 'day'));
});


</script>

<style scoped>

</style>