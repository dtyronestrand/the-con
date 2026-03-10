<template>
  <div v-if="task" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50" @click="closeModal">
    <div class="p-6 bg-black border border-(--blue) rounded-xl rounded-lg max-w-md w-full h-auto text-white mx-4" @click.stop>
      <form @submit.prevent="saveForm" class="space-y-4">
        <input v-if="taskToUpdate" v-model="taskToUpdate.name" type="text" class="decorated w-full" placeholder="Task Name" />
        <div v-if="taskToUpdate">
          <div class="flex justify-between items-center mb-2">
            <p>Subtasks</p>
            <button @click.prevent="addSubtask" class="btn btn-sm btn-success">+</button>
          </div>
          <div v-for="(subtask, index) in taskToUpdate.sub_tasks" :key="index" class="flex items-center mb-2 group">
            <input v-model="subtask.name" type="text" :class="{ 'line-through': subtask.done }" class="input border-none w-full mr-2 " placeholder="Subtask Name" />
            <input class="opacity-0 group-hover:opacity-100 mr-2" :checked="subtask.done"  @change="subtask.done = !subtask.done" type="checkbox"  />
            <button @click.prevent="removeSubtask(index)" class="btn btn-sm btn-error">-</button>
          </div>
        </div>
        
        <div v-if="taskToUpdate">
          <p class="mb-2">Notes</p>
          <QuillEditor 
            theme="snow" 
            v-model:content="taskToUpdate.notes" 
            contentType="html"
            class="mb-2" 
          />
        </div>
        
        <div class="flex gap-2">
          <Button type="submit" background="var(--indigo)" classList="left-round ">Save</Button>
          <Button @click="closeModal" type="button" background="var(--red-alert)" classList="right-round">Cancel</Button>
          <Trash2 @click="deleteTask"/>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import {Trash2} from 'lucide-vue-next';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import type { Task } from '@/types';
import Button from '../../lcars/Button.vue';
import { router } from '@inertiajs/vue3';
interface Props {
  task?: Task | null;
}

const props = defineProps<Props>();
const emit = defineEmits(['close', 'updateTask']);
const taskToUpdate = ref(props.task ? { ...props.task } : null);

const addSubtask = () => {
  if (taskToUpdate.value) {
    (taskToUpdate.value.sub_tasks ??= []).push({ name: '', done: false, id: Date.now(), due_date: null });
  }
};

const removeSubtask = (index: number) => {
  taskToUpdate.value?.sub_tasks?.splice(index, 1);
};

const saveForm = () => {
  if (taskToUpdate.value) {
    console.log('Saving task with notes:', taskToUpdate.value.notes);
    emit('updateTask', taskToUpdate.value);
    closeModal();
  }
};
const deleteTask = () =>{
  router.delete(`/tasks/${taskToUpdate.value?.id}`, {
    onSuccess: () => {
      closeModal();
    }
  })
}

const closeModal = () => {
  emit('close');
};
</script>

<style scoped>
input:focus {
    border-color: var(--indigo);
    box-shadow: 0 0 0 3px hsla(var(--indigo) H S L, 0.8);
    outline-color: var(--indigo) ;
}
.decorated {
    border-left: 0.2rem solid var(--indigo);
    padding-right: 0.5rem;
    padding-left: 0.5rem;
    border-right: 0.2rem solid var(--indigo);
    caret-color: #fff;
}
</style>