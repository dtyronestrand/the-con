<template>
  <div v-if="task" class="fixed inset-0 bg-base-300 bg-opacity-10 flex items-center justify-center z-50" @click="closeModal">
    <div class="bg-base-100 p-6 rounded-lg max-w-md w-full mx-4" @click.stop>
      <form @submit.prevent="saveForm" class="space-y-4">
        <input v-if="taskToUpdate" v-model="taskToUpdate.name" type="text" class="input input-bordered w-full" placeholder="Task Name" />
        
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
          <button type="submit" class="btn btn-success flex-1">Save</button>
          <button @click="closeModal" type="button" class="btn btn-error">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';

interface Props {
  task?: {
    name: string;
    notes: string;
    sub_tasks: { name: string; done: boolean }[];
    done: boolean;
    id: number;
    date: string;
  } | null;
}

const props = defineProps<Props>();
const emit = defineEmits(['close', 'updateTask']);
const taskToUpdate = ref(props.task ? { ...props.task } : null);

const addSubtask = () => {
  if (taskToUpdate.value) {
    taskToUpdate.value.sub_tasks.push({ name: '', done: false });
  }
};

const removeSubtask = (index: number) => {
  if (taskToUpdate.value) {
    taskToUpdate.value.sub_tasks.splice(index, 1);
  }
};

const saveForm = () => {
  if (taskToUpdate.value) {
    console.log('Saving task with notes:', taskToUpdate.value.notes);
    emit('updateTask', taskToUpdate.value);
    closeModal();
  }
};

const closeModal = () => {
  emit('close');
};
</script>

<style scoped>

</style>