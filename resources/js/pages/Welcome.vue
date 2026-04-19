<template>
    <div
        class="flex-container nativephp-safe-area pr-[var(--inset-right)] pl-[var(--inset-left)]"
    >
        <div class="top-bar flex flex-row">
            <Elbow classList="left-bottom " background="var(--anakiwa)" />
            <BarWithTitle classList="top text-4xl" :background="'var(--indigo)'"
                >The Con</BarWithTitle
            ><Bar classList="top" :background="'var(--blue)'" /><BarEnd
                classList="right decorated top"
                :background="'var(--indigo)'"
            />
        </div>
        <div class="top-content flex flex-row">
            <div class="flex flex-col">
                <Element
                    background="var(--indigo)"
                    :button="false"
                    :height="2"
                ></Element>
                <Element
                    background="var(--blue)"
                    :button="true"
                    @buttonPressed="topView = 'weather'"
                    >Weather</Element
                >
                <Element
                    background="var(--anakiwa)"
                    :button="true"
                    @buttonPressed="topView = 'todo'"
                    >To Do</Element
                >

                <Element background="var(--blue)" :button="false"></Element>
                <Element background="var(--anakiwa)" :button="false"></Element>
            </div>
            <div class="flex h-full w-full flex-col">
                <Planner
                    :tasks="page.props.auth.user.tasks as Task[]"
                    :is-google-connected="page.props.isGoogleConnected"
                    v-if="topView === 'todo'"
                />
                <div
                    v-else
                    class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                >
                    <WeatherWidget class="col-span-1" />
                    <div class="col-span-1 md:col-span-1 lg:col-span-2">
                        <StickyNoteWidget :notes="page.props.stickyNotes" />
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-bar flex flex-row">
            <Elbow classList="left-top" background="var(--periwinkle)" />
            <Bar :background="'var(--anakiwa)'" /><BarEnd
                classList="right decorated"
                :background="'var(--anakiwa)'"
            />
        </div>
        <div class="section-topper flex flex-row">
            <Elbow classList="left-bottom " background="var(--anakiwa)" />
            <Bar classList="top" :background="'var(--indigo)'"></Bar
            ><Bar classList="top" :background="'var(--blue)'" /><BarEnd
                classList="right decorated top"
                :background="'var(--indigo)'"
            />
        </div>
        <div class="bottom-content flex flex-row">
            <div class="flex flex-col">
                <Element
                    background="var(--indigo)"
                    :button="true"
                    @buttonPressed="showModal = true"
                    >Add Service</Element
                >
                <Element
                    background="var(--red-alert)"
                    :button="true"
                    @buttonPressed="factoryReset"
                    >Factory Reset</Element
                >

                <Element
                    @click="logout"
                    background="var(--periwinkle)"
                    :button="true"
                    >Logout</Element
                >
                <Element
                    background="var(--blue)"
                    :button="false"
                    :height="2"
                ></Element>

                <Element
                    background="var(--periwinkle)"
                    :button="false"
                    :height="2"
                ></Element>
            </div>
            <div class="px-12 text-white">
                <ServiceContainer
                    :categories="page.props.categories"
                    :showModal="showModal"
                    @closeModal="closeModal"
                    @serviceAdded="showModal = false"
                />
            </div>
        </div>
        <div class="closer flex flex-row">
            <Elbow classList="left-top" background="var(--periwinkle)" />
            <Bar :background="'var(--anakiwa)'" /><BarEnd
                classList="right decorated"
                :background="'var(--anakiwa)'"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

import Bar from '@/components/lcars/Bar.vue';
import BarEnd from '@/components/lcars/BarEnd.vue';
import BarWithTitle from '@/components/lcars/BarWithTitle.vue';
import Elbow from '@/components/lcars/Elbow.vue';
import Element from '@/components/lcars/Element.vue';
import ServiceContainer from '@/components/ServiceContainer.vue';
import StickyNoteWidget from '@/components/StickyNoteWidget.vue';
import Planner from '@/components/todolist/Planner.vue';
import WeatherWidget from '@/components/WeatherWidget.vue';
import type { AppPageProps, Category, Event, Task } from '@/types';

const page = usePage<
    AppPageProps & {
        categories: Category[];
        isConnected: boolean;
        isGoogleConnected: boolean;
        events: Event[];
        stickyNotes: any[];
    }
>();
onMounted(() => {
    console.log('App Mounted: Initiating background sync');
    runSync();
    setInterval(runSync, 5 * 60 * 1000);
});
const topView = ref<'todo' | 'weather'>('todo');
const runSync = async () => {
    try {
        await axios.post('/trigger-sync');
        console.log('sync triggered successfully');
    } catch (error) {
        console.error('Error triggering sync:', error);
    }
};
const logout = () => {
    router.post('/logout');
};
const factoryReset = () => {
    if (
        confirm(
            'Are you sure you want to factory reset? This will erase all your data.',
        )
    ) {
        router.post('/settings/factory-reset');
    }
};
const showModal = ref(false);
const closeModal = () => {
    showModal.value = false;
};
</script>

<style scoped>
.flex-container {
    display: flex;
    flex-direction: column;
    min-height: 100dvh; /* Dynamic viewport height for mobile */
}
</style>
