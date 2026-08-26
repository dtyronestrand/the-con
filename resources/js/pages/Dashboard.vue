<template>
    <div class="flex-container h-full">
        <div class="top-bar flex flex-row">
            <Elbow classList="left-bottom " background="var(--panel-secondary-subtle)" />
            <BarWithTitle classList="top" :background="'var(--panel-primary)'"
                >The Conn</BarWithTitle
            ><Bar classList="top" :background="'var(--panel-secondary-strong)'" /><BarEnd
                classList="right decorated top"
                :background="'var(--panel-primary)'"
            />
        </div>
        <div class="top-content flex flex-row">
            <div class="flex flex-col">
                <Element
                    background="var(--panel-primary)"
                    :button="false"
                    :height="2"
                ></Element>
                <Element
                    background="var(--panel-secondary)"
                    :button="false"
                ></Element>
                <Element background="var(--panel-secondary-strong)" :button="false"></Element>
                <Element background="var(--panel-secondary-subtle)" :button="false"></Element>

                <Element background="var(--panel-secondary-strong)" :button="false"></Element>
                <Element background="var(--panel-secondary-subtle)" :button="false"></Element>
            </div>
            <div class="flex w-full flex-row justify-evenly">
                <WeatherWidget />
                <CalendarWidget
                    :isConnected="page.props.isConnected"
                    :events="page.props.events"
                />
            </div>
        </div>
        <div class="bottom-bar flex flex-row">
            <Elbow classList="left-top" background="var(--panel-secondary)" />
            <Bar :background="'var(--panel-secondary-subtle)'" /><BarEnd
                classList="right decorated"
                :background="'var(--panel-secondary-subtle)'"
            />
        </div>
        <div class="section-topper flex flex-row">
            <Elbow classList="left-bottom " background="var(--panel-secondary-subtle)" />
            <Bar classList="top" :background="'var(--panel-primary)'"></Bar
            ><Bar classList="top" :background="'var(--panel-secondary-strong)'" /><BarEnd
                classList="right decorated top"
                :background="'var(--panel-primary)'"
            />
        </div>
        <div class="bottom-content flex flex-row">
            <div class="flex flex-col">
                <Element
                    background="var(--tertiary)"
                    style="color: var(--ink)"
                    :button="true"
                    @buttonPressed="showModal = true"
                    >Add Service</Element
                >
                <Element background="var(--panel-secondary-strong)" :button="false"></Element>
                <Element
                    background="var(--panel-secondary)"
                    :button="false"
                ></Element>
                <Element background="var(--panel-secondary)" :button="false" />
                <Element
                    background="var(--panel-secondary-strong)"
                    :button="false"
                    :height="2"
                ></Element>
                <Element background="var(--panel-secondary-subtle)" :button="false"></Element>
                <Element
                    background="var(--panel-secondary)"
                    :button="false"
                    :height="2"
                ></Element>
            </div>
            <ServiceContainer
                :categories="page.props.categories"
                :showModal="showModal"
                @closeModal="showModal = false"
                @serviceAdded="showModal = false"
            />
        </div>
        <div class="closer flex flex-row">
            <Elbow classList="left-top" background="var(--panel-secondary)" />
            <Bar :background="'var(--panel-secondary-subtle)'" /><BarEnd
                classList="right decorated"
                :background="'var(--panel-secondary-subtle)'"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

import CalendarWidget from '@/components/CalendarWidget.vue';
import Bar from '@/components/lcars/Bar.vue';
import BarEnd from '@/components/lcars/BarEnd.vue';
import BarWithTitle from '@/components/lcars/BarWithTitle.vue';
import Elbow from '@/components/lcars/Elbow.vue';
import Element from '@/components/lcars/Element.vue';
import ServiceContainer from '@/components/ServiceContainer.vue';
import WeatherWidget from '@/components/WeatherWidget.vue';
import type { AppPageProps, Category, Event } from '@/types';

const page = usePage<
    AppPageProps & {
        categories: Category[];
        isConnected: boolean;
        events: Event[];
    }
>();
const showModal = ref(false);
</script>

<style scoped></style>
