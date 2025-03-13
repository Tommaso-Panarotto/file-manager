<template>
    <Modal :show="props.modelValue" @show="onShow" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Share Files
            </h2>
            <div class="mt-6">
                <InputLabel for="shareEmail" value="Enter Email addresses" class="sr-only" />
                <TextInput type="text" ref="emailInput" id="shareEmail" v-model="form.email"
                    :class="form.errors.email ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''"
                    class="mt-1 block w-full" placeholder="Enter Email Addresses" @keyup.enter="share" />
                <InputError :message="form.errors.email" class="mt-2" />
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
import { useForm, usePage } from '@inertiajs/vue3';
import SecondaryButton from '../SecondaryButton.vue';
import PrimaryButton from '../PrimaryButton.vue';
import { nextTick, ref } from 'vue';
import { showSuccessNotification } from '@/event-bus';
import InputError from '../InputError.vue';

const form = useForm({
    email: null,
    all: false,
    parent_id: null,
    ids: []
})

const emailInput = ref(null)

const props = defineProps({
    modelValue: Boolean,
    allSelected: Boolean,
    selectedIds: Array
})

const page = usePage()

const emit = defineEmits(['update:modelValue'])

function share() {
    form.parent_id = page.props.folder.data.id;
    let email = form.email;
    if (props.allSelected) {
        form.all = true;
        form.ids = [];
    } else {
        form.ids = props.selectedIds;
    }
    form.post(route('file.share'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal()
            form.reset();
            showSuccessNotification(`Selected files will be shared to "${email}" if the email exist in the system`)
        },

        onError: () => emailInput.value.focus()
    })
}

function closeModal() {
    emit('update:modelValue')
    form.clearErrors();
    form.reset();
}

function onShow() {
    nextTick(() => emailInput.value.focus())
}

</script>