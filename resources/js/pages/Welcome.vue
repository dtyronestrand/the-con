<template>
    <div class="grid-container">
    <div class="divider3">
    <div class="flex">
           <Elbow classList="left-bottom " background="var(--anakiwa)"/>
           <BarWithTitle classList="top" :background="'var(--indigo)'">The Con</BarWithTitle><Bar classList="top" :background="'var(--blue)'"/><BarEnd classList="right decorated top" :background="'var(--indigo)'"/>
</div>
    </div>
    <div class="header">
        <Element background="var(--indigo)" :button="false" :height="2"/>
       <Element background="var(--periwinkle)" :button="false"/>
       <Element background="var(--blue)" :button="false"/>
         <Element background="var(--anakiwa)" :button="false"/>
     
    </div>
    <div class="divider1">
        <Elbow classList="left-top" background="var(--indigo)" />
        <Bar :background="'var(--blue)'"/><BarEnd classList="right decorated" :background="'var(--blue)'"/>
    </div>
    <div class="content1">
    <CalendarWidget :isConnected="page.props.isConnected" :events="page.props.events"/>
    <WeatherWidget />
    </div>
    <div class="divider2">
    <Elbow classList="left-bottom" background="var(--anakiwa)" />
    <Bar class="top"  background="var(--indigo)"/><BarEnd classList="right decorated top"  background="var(--indigo)"/>
    </div>
    <div class="body">
        <Element @buttonPressed="showModal=true" background="var(--periwinkle)" :button="true">Add Service</Element>
        <Element background="var(--indigo)" :button="false">Welcome</Element>
        <Element background="var(--blue)" :button="false" :height="2"></Element>
        <Element background="var(--anakiwa)" :button="false"></Element>
        <Element background="var(--periwinkle)" :button="false" :height="2"></Element>
    </div>
    <div class="content2">
       <ServiceContainer :categories="page.props.categories" :edit="showModal" @serviceAdded="edit = false"/>
    </div>
    <div class="foot">
    <Elbow classList="left-top" background="var(--periwinkle)"/>
    <Bar :background="'var(--anakiwa)'"/><BarEnd classList="right decorated" :background="'var(--anakiwa)'"/>
    </div>
    </div>
</template>

<script setup lang="ts">
    import Bar from '@/components/lcars/Bar.vue';
import BarEnd from '@/components/lcars/BarEnd.vue';
import BarWithTitle from '@/components/lcars/BarWithTitle.vue';
    import Button from '@/components/lcars/Button.vue';
import Elbow from '@/components/lcars/Elbow.vue';
import Element from '@/components/lcars/Element.vue';
import CalendarWidget from '@/components/CalendarWidget.vue';
import WeatherWidget from '@/components/WeatherWidget.vue';
import ServiceContainer from '@/components/ServiceContainer.vue';
import {usePage} from '@inertiajs/vue3';
import {ref} from 'vue';
import type { Category, AppPageProps,Event } from '@/types';
const page = usePage<AppPageProps & {
    categories: Category[];
    isConnected: boolean;
    events: Event[];
}>();

const edit = ref(false);
const showModal = ref(false);



</script>

<style scoped>

</style>
