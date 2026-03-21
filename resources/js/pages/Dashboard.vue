<template>
    <div class="flex-container h-full">
        <div class="top-bar flex flex-row">
            <Elbow classList="left-bottom " background="var(--anakiwa)" />
            <BarWithTitle classList="top" :background="'var(--indigo)'"
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
                    background="var(--periwinkle)"
                    :button="false"
                ></Element>
                <Element background="var(--blue)" :button="false"></Element>
                <Element background="var(--anakiwa)" :button="false"></Element>

                <Element background="var(--blue)" :button="false"></Element>
                <Element background="var(--anakiwa)" :button="false"></Element>
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
                    @buttonPreed="showModal = true"
                    >Add Service</Element
                >
                <Element background="var(--blue)" :button="false"></Element>
                <Element
                    background="var(--periwinkle)"
                    :button="false"
                ></Element>
                <Element background="var(--periwinkle)" :button="false" />
                <Element
                    background="var(--blue)"
                    :button="false"
                    :height="2"
                ></Element>
                <Element background="var(--anakiwa)" :button="false"></Element>
                <Element
                    background="var(--periwinkle)"
                    :button="false"
                    :height="2"
                ></Element>
            </div>
            <ServiceContainer
                :categories="page.props.categories"
                :edit="showModal"
                @serviceAdded="edit = false"
            />
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
import { usePage } from '@inertiajs/vue3';

import CalendarWidget from '@/components/CalendarWidget.vue';
import Bar from '@/components/lcars/Bar.vue';
import BarEnd from '@/components/lcars/BarEnd.vue';
import BarWithTitle from '@/components/lcars/BarWithTitle.vue';
import Elbow from '@/components/lcars/Elbow.vue';
import Element from '@/components/lcars/Element.vue';
import WeatherWidget from '@/components/WeatherWidget.vue';
import type { AppPageProps, Category, Event } from '@/types';
const page = usePage<
    AppPageProps & {
        categories: Category[];
        isConnected: boolean;
        events: Event[];
    }
>();
</script>

<style scoped></style>
