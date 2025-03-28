<template>
    <button @click="onClick"
        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-4 mr-2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
        </svg>

        Restore
    </button>
    <ConfirmationDialog :show="showConfirmationDialog" message="Are you sure you want to restore selected files?"
        @cancel="onCancel" @confirm="onConfirm" />
</template>

<script setup>
import { ref } from 'vue';
import ConfirmationDialog from './ConfirmationDialog.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { showErrorDialog, showSuccessNotification } from '@/event-bus';

const page = usePage();
const form = useForm({
    all: null,
    ids: [],
    parent_id: null
});

const showConfirmationDialog = ref(false);

const props = defineProps({
    allSelected: {
        type: Boolean,
        required: false,
        default: false
    },
    selectedIds: {
        type: Array,
        required: false
    }
})

const emit = defineEmits(['restore', 'clean'])

function onClick() {
    if (!props.allSelected && !props.selectedIds.length) {
        showErrorDialog('Please select files to restore');
        return
    }
    showConfirmationDialog.value = true;
}

function onCancel() {
    showConfirmationDialog.value = false;
}


function onConfirm() {
    if (props.allSelected) {
        form.all = true;
    } else {
        form.ids = props.selectedIds;
    }

    form.post(route('file.restore'), {
        onSuccess: () => {
            showConfirmationDialog.value = false;
            showSuccessNotification('Selected files have been restored');
            emit('clean');
        }
    })
}
</script>