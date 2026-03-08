<template>
    <input :class="props.class" v-model="taskName" :disabled="props.disabled" :due="props.due" type="text" @blur="handleBlur" class="h-[1rem] p-0 m-0 text-white text-justify ">

    </input>
</template>

<script setup lang="ts">
    import {ref, watch} from 'vue';
    import {router, usePage} from "@inertiajs/vue3";
    
    interface Props {
        taskName?: string;
        class?: string;
        disabled?: boolean;
        due?: string;
        done?: boolean;
    }
    const props = defineProps<Props>();
    const page = usePage();
    const taskName = ref(props.taskName || '');
    const user = ref(page.props.auth.user);
    watch(
        ()=> [taskName.value, props.due, props.done] as const,
        ([name, due, done]) => {
            console.log('Component values:', {
                taskName: name,
                due,
                done
            });
        },
        { immediate: true }
    );

    const showModal = ref(false);

    const handleBlur = () => {
        if(taskName.value.trim()) {
            router.post('/tasks/store', {
                name: taskName.value,
                due_date: props.due,
                done: props.done,
                user_id: user.value.id
            }, {
                onSuccess: () => {
                    taskName.value = '';
                }
            })
        }
    }
</script>

<style scoped>

.decorated {
    border-left: 0.2rem solid var(--indigo);
    padding-right: 0.5trem;
    padding-left: 0.5rem;
    border-right: 0.2rem solid var(--indigo);
    caret-color: #fff;
}
</style>