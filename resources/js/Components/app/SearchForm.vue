<template>
    <div class="w-[600px] h-[80px] flex items-center">
        <TextInput @keyup.enter.prevent="onSearch" type="text" class="block w-full mr-2" v-model="search" autocomplete
            placeholder="Search for files and folder" />
    </div>
</template>

<script setup>
import { router} from '@inertiajs/vue3';
import TextInput from '../TextInput.vue';
import { onMounted, ref } from 'vue';
import { emitter, ON_SEARCH } from '@/event-bus';

let params = '';

const search = ref('')

function onSearch() {
    params.set('search', search.value);
    router.get(window.location.pathname + '?' + params.toString());

    emitter.emit(ON_SEARCH, search.value)
}

onMounted(() => {
    params = new URLSearchParams(window.location.search);
    search.value = params.get('search');
})

</script>