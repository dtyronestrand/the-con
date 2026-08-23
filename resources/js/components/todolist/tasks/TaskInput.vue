<template>
    <input
        :class="props.class"
        v-model="taskName"
        :disabled="props.disabled"
        type="text"
        @blur="handleBlur"
        class="m-0 h-[1rem] p-0 text-justify text-on-surface"
    />
</template>

<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface Props {
    taskName?: string;
    class?: string;
    disabled?: boolean;
    due_date?: string | null;
    done?: boolean;
}
const props = defineProps<Props>();
const page = usePage();
const taskName = ref(props.taskName || '');
const user = ref(page.props.auth.user);
watch(
    () => [taskName.value, props.due_date, props.done] as const,
    ([name, due_date, done]) => {
        console.log('Component values:', {
            taskName: name,
            due_date,
            done,
        });
    },
    { immediate: true },
);

const handleBlur = () => {
    if (taskName.value.trim()) {
        router.post(
            '/tasks/store',
            {
                name: taskName.value,
                due_date: props.due_date,
                done: props.done,
                user_id: user.value.id,
            },
            {
                onSuccess: () => {
                    taskName.value = '';
                },
            },
        );
    }
};
</script>

<style scoped>
input:focus {
    border-color: var(--tertiary);
    box-shadow: 0 0 0 3px color-mix(in oklab, var(--tertiary) 40%, transparent);
    outline-color: var(--tertiary);
}
.decorated {
    border-left: 0.2rem solid var(--panel-primary);
    padding-right: 0.5rem;
    padding-left: 0.5rem;
    border-right: 0.2rem solid var(--panel-primary);
    caret-color: var(--on-surface);
}
</style>
