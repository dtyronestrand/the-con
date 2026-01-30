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
        <Element background="var(--periwinkle)" :button="false"/>
    </div>
    <div class="divider1">
        <Elbow classList="left-top" background="var(--indigo)" />
        <Bar :background="'var(--blue)'"/><BarEnd classList="right decorated" :background="'var(--blue)'"/>
    </div>
    <div class="content1">
    <div>
    <Button :classList="['round']" background="var(--cosmic)" :button="true">Configure</Button>
    <Button :classList="['round']" background="var(--anakiwa )" :button="true"></Button>
    </div>
    <WeatherWidget />
    <CalendarWidget :events="page.props.events"/>
    </div>
    <div class="divider2">
    <Elbow classList="left-bottom" background="var(--anakiwa)" />
    <Bar class="top"  background="var(--indigo)"/><BarEnd classList="right decorated top"  background="var(--indigo)"/>
    </div>
    <div class="body">
        <Element @buttonPressed="edit = true" background="var(--periwinkle)" :button="true">Edit</Element>
        <Element background="var(--indigo)" :button="false">Welcome</Element>
        <Element background="var(--blue)" :button="false" height="2"></Element>
    </div>
    <div class="content2">
       
    </div>
    </div>
</template>

<script setup lang="ts">
    import Bar from '@/components/lcars/Bar.vue';
import BarEnd from '@/components/lcars/BarEnd.vue';
import BarWithTitle from '@/components/lcars/BarWithTitle.vue';
import Bracket from '@/components/lcars/Bracket.vue';
    import Button from '@/components/lcars/Button.vue';
import Elbow from '@/components/lcars/Elbow.vue';
import Element from '@/components/lcars/Element.vue';
import CalendarWidget from '@/components/CalendarWidget.vue';
import WeatherWidget from '@/components/WeatherWidget.vue';
import {usePage, router, useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import type { Services, AppPageProps } from '@/types';
const page = usePage<AppPageProps & {
    services: Services[];
}>();

const edit = ref(false);
const showModal = ref(false);
const form = useForm({
    name: null,
    url: null,
});

const addService = () => {
    if (!form.name || !form.url) {
        return;
    }
    router.post('/services', {
    name: (form.name as string),
    url: (form.url as string),
    })
    showModal.value = false;
};
</script>

<style scoped>
.backdrop {
    background-color: rgba(from var(--anakiwa) r g b / 0.3);
}

.service-modal{
   
}
#add-service{
    border-radius:1.5rem;
}
input {
    background-color: rgba(from var(--indigo) R G B/0.4);
    color:white;
}
</style>
