<template>
    <Modal :show="modelValue" @show="onShow" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Create New Folder
            </h2>
            <div class="mt-6">
                <InputLabel for="folderName" value="Folder Name" class="sr-only" />
                <TextInput type="text" ref="folderNameInput" id="folderName" v-model="form.name"
                    class="mt-1 block w-full"
                    :class="form.errors.name ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''"
                    placeholder="Folder Name" @keyup.enter="createFolder" />
                <InputError :message="form.errors.name" class="mt-2" />

            </div>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                <PrimaryButton class="ml-3" :class="{ 'opacity-25': form.processing }" @click="createFolder"
                    :disable="form.processing">Submit</PrimaryButton>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import InputLabel from '../InputLabel.vue';
import Modal from '../Modal.vue';
import TextInput from '../TextInput.vue';
import InputError from '../InputError.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import SecondaryButton from '../SecondaryButton.vue';
import PrimaryButton from '../PrimaryButton.vue';
import { nextTick, ref } from 'vue';
import { showSuccessNotification } from '@/event-bus';

const form = useForm({
    name: '',
    parent_id: null,
})

const folderNameInput = ref(null)

const { modelValue } = defineProps({
    modelValue: Boolean
})

const page = usePage()

const emit = defineEmits(['update:modelValue'])

function createFolder() {
    form.parent_id = page.props.folder.data.id
    let name = form.name.trim();
    form.post(route('folder.create'), {
        preventScroll: true,
        onSuccess: () => {
            closeModal()
            showSuccessNotification(`The Folder ${name} has been created successfully`)
            form.reset();
        },

        onError: () => folderNameInput.value.focus()
    })
}

function closeModal() {
    emit('update:modelValue')
    form.clearErrors();
    form.reset();
}

function onShow() {
    nextTick(() => folderNameInput.value.focus())
}

</script>