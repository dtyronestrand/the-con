<template>
<div class="flex flex-row justify-evenly w-full">
<WeatherWidget />
<CalendarWidget :isConnected="isConnected" :events="events"/>
</div>
</template>

<script setup lang="ts">
import WeatherWidget from '@/components/WeatherWidget.vue';
import CalendarWidget from '@/components/CalendarWidget.vue';
import type { Event } from '@/types';

interface Props {
    isConnected: boolean,
    events: Event[]
}

defineProps<Props>();


</script>

<style scoped>

</style>