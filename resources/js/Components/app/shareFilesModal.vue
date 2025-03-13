<template>
    <Modal :show="modelValue" @show="onShow" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Share Files
            </h2>
            <div class="mt-6">
                <InputLabel for="folderName" value="Folder Name" class="sr-only" />
                <TextInput type="text" ref="folderNameInput" id="folderName" v-model="form.emails"
                    class="mt-1 block w-full" placeholder="Enter Email Addresses" @keyup.enter="share" />

            </div>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                <PrimaryButton class="ml-3" :class="{ 'opacity-25': form.processing }" @click="share"
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

const form = useForm({
    emails: [],
    file_ids: [],
})

const folderNameInput = ref(null)

const { modelValue } = defineProps({
    modelValue: Boolean
})

const page = usePage()

const emit = defineEmits(['update:modelValue'])

function share() {
    form.parent_id = page.props.folder.data.id
    form.post(route('folder.create'), {
        preventScroll: true,
        onSuccess: () => {
            closeModal()
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