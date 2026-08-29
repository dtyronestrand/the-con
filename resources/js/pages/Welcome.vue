<template>
    <div
        class="flex-container nativephp-safe-area pr-[var(--inset-right)] pl-[var(--inset-left)]"
    >
        <div
            v-if="page.props.needsReconnect"
            class="border-l-4 p-4"
            style="
                background: var(--panel-secondary-subtle);
                border-color: var(--error);
            "
        >
            <p class="mb-3 text-sm" style="color: var(--ink)">
                Not connected to the sync server — your changes are only saved
                on this device until you reconnect.
            </p>
            <Form
                v-bind="ServerConnectionController.connect.form()"
                :options="{ preserveScroll: true }"
                reset-on-success
                class="flex flex-wrap items-start gap-3"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-1">
                    <Input
                        name="email"
                        type="email"
                        placeholder="Email"
                        autocomplete="username"
                        class="w-56"
                    />
                    <InputError :message="errors.email" />
                </div>
                <div class="grid gap-1">
                    <Input
                        name="password"
                        type="password"
                        placeholder="Password"
                        autocomplete="current-password"
                        class="w-56"
                    />
                    <InputError :message="errors.password" />
                </div>
                <Button :disabled="processing">Reconnect</Button>
            </Form>
        </div>
        <div class="top-bar flex flex-row">
            <Elbow
                classList="left-bottom "
                background="var(--panel-secondary-subtle)"
            />
            <BarWithTitle
                classList="top text-4xl"
                :background="'var(--panel-primary)'"
                >The Conn</BarWithTitle
            ><Bar
                classList="top"
                :background="'var(--panel-secondary-strong)'"
            /><BarEnd
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
                    background="var(--tertiary)"
                    style="color: var(--ink)"
                    :button="true"
                    @buttonPressed="topView = 'weather'"
                    >Weather</Element
                >
                <Element
                    background="var(--tertiary)"
                    style="color: var(--ink)"
                    :button="true"
                    @buttonPressed="topView = 'todo'"
                    >To Do</Element
                >
                <Element
                    background="var(--tertiary)"
                    style="color: var(--ink)"
                    :button="true"
                    @buttonPressed="topView = 'notes'"
                    >Notes</Element
                >

                <Element
                    background="var(--panel-secondary-strong)"
                    :button="false"
                ></Element>
                <Element
                    background="var(--panel-secondary-subtle)"
                    :button="false"
                ></Element>
                <Element
                    background="var(--panel-secondary-strong)"
                    :button="false"
                ></Element>
                <Element
                    background="var(--panel-secondary-subtle)"
                    :button="false"
                ></Element>
            </div>
            <div class="flex h-full w-full flex-col">
                <Planner
                    :tasks="page.props.auth.user.tasks as Task[]"
                    :is-google-connected="page.props.isGoogleConnected"
                    v-if="topView === 'todo'"
                />
                <NotesLog
                    v-else-if="topView === 'notes'"
                    :notes="page.props.notes"
                    @open-todo="topView = 'todo'"
                />
                <div
                    v-else
                    class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                >
                    <WeatherWidget class="col-span-1" />
                </div>
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
            <Elbow
                classList="left-bottom "
                background="var(--panel-secondary-subtle)"
            />
            <Bar classList="top" :background="'var(--panel-primary)'"></Bar
            ><Bar
                classList="top"
                :background="'var(--panel-secondary-strong)'"
            /><BarEnd
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
                <Element
                    background="var(--error)"
                    :button="true"
                    @buttonPressed="factoryReset"
                    >Factory Reset</Element
                >

                <Element
                    @click="logout"
                    background="var(--panel-secondary)"
                    :button="true"
                    >Logout</Element
                >
                <Element
                    background="var(--panel-secondary-strong)"
                    :button="false"
                    :height="2"
                ></Element>

                <Element
                    background="var(--panel-secondary)"
                    :button="false"
                    :height="2"
                ></Element>
            </div>
            <div class="px-12 text-on-surface">
                <ServiceContainer
                    :categories="page.props.categories"
                    :showModal="showModal"
                    @closeModal="closeModal"
                    @serviceAdded="showModal = false"
                />
            </div>
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
import { Form, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

import ServerConnectionController from '@/actions/App/Http/Controllers/ServerConnectionController';
import InputError from '@/components/InputError.vue';
import Bar from '@/components/lcars/Bar.vue';
import BarEnd from '@/components/lcars/BarEnd.vue';
import BarWithTitle from '@/components/lcars/BarWithTitle.vue';
import Elbow from '@/components/lcars/Elbow.vue';
import Element from '@/components/lcars/Element.vue';
import NotesLog from '@/components/notes/NotesLog.vue';
import ServiceContainer from '@/components/ServiceContainer.vue';
import Planner from '@/components/todolist/Planner.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import WeatherWidget from '@/components/WeatherWidget.vue';
import type { AppPageProps, Category, Event, Note, Task } from '@/types';

const page = usePage<
    AppPageProps & {
        categories: Category[];
        isConnected: boolean;
        isGoogleConnected: boolean;
        needsReconnect: boolean;
        events: Event[];
        notes: Note[];
    }
>();
onMounted(() => {
    console.log('App Mounted: Initiating background sync');
    runSync();
    setInterval(runSync, 5 * 60 * 1000);
});
const topView = ref<'todo' | 'weather' | 'notes'>('todo');
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
