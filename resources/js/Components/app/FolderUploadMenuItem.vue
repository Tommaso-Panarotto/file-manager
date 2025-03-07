<template>
    <MenuItem v-slot="{ active }">
    <a href="#" class="text-gray-700 block px-4 py-2 text-sm relative">
        Upload Folder
        <input multiple @change="onChange" type="file"
            class="cursor-pointer absolute left-0 top-0 bottom-0 right-0 opacity-0" directory webkitdirectory>
    </a>
    </MenuItem>
</template> 

<script setup>
import { emitter, FILE_UPLOAD_STARTED } from '@/event-bus';
import { MenuItem } from '@headlessui/vue';

function onChange(ev){
    emitter.emit(FILE_UPLOAD_STARTED, ev.target.files)
}
</script>