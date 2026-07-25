<template>
    <label>
        <input
            type="checkbox"
            :checked="isChecked"
            :id="props.fieldId"
            @input="updateDone"
        />
        <span></span>
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
    label {
        position: relative;
    }
    span {
        width: 15px;
        height: 15px;
        border: 1px solid #cccccc;
        display: inline-block;
        border-radius: 50%;
        transition: all linear 0.3s;
    }
    span:after {
        content: '';
        position: absolute;
        top: -1px;
        left: 6px;
        border-bottom: 2px solid #ffffff;
        border-right: 2px solid #ffffff;
        height: 9px;
        width: 4px;
        transform: rotate(45deg);
        visibility: hidden;
    }
    input {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
        opacity: 0;
    }
    input:focus-visible ~ span {
        box-shadow: 0 0 0 2px var(--indigo);
        outline: 2px solid transparent;
        outline-offset: 2px;
    }
    input:checked ~ span {
        background: #cccccc;
    }
    input:checked ~ span:after {
        visibility: visible;
    }
}
</style>
