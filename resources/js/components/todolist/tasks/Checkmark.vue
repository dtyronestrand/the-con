<template>
    <label class="relative inline-flex items-center">
        <input
            type="checkbox"
            :checked="isChecked"
            :id="props.fieldId"
            @input="updateDone"
            aria-label="Toggle task completion"
            class="peer sr-only"
        />
        <span
            class="peer-focus-visible:ring-2 peer-focus-visible:ring-(--indigo) peer-focus-visible:ring-offset-1 peer-focus-visible:ring-offset-black"
        ></span>
    </label>
</template>

<script setup lang="ts">
import { ref } from 'vue';

interface Props {
    fieldId?: string;
    checked: boolean;
}

const props = defineProps<Props>();
const isChecked = ref(props.checked);
const emit = defineEmits(['updateChecked']);
function updateDone() {
    isChecked.value = !isChecked.value;
    emit('updateChecked', isChecked.value);
}
</script>

<style scoped>
:root {
    span {
        width: 15px;
        height: 15px;
        border: 1px solid #cccccc;
        display: inline-block;
        border-radius: 50%;
        transition: all linear 0.3s;
        position: relative;
    }
    span:after {
        content: '';
        position: absolute;
        top: -1px;
        left: 4px;
        border-bottom: 2px solid #ffffff;
        border-right: 2px solid #ffffff;
        height: 9px;
        width: 4px;
        transform: rotate(45deg);
        visibility: hidden;
    }
    input:checked ~ span {
        background: #cccccc;
    }
    input:checked ~ span:after {
        visibility: visible;
    }
}
</style>
