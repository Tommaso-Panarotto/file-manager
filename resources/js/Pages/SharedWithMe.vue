<template>
    <AuthenticatedLayout>
        <nav class="flex items-center justify-end p-1 mb-">
            <div>
                <DownloadFilesButton :all="allSelected" :ids="selectedIds" :shared-with-me="true" class="mr-2" />
            </div>
        </nav>
        <div class="flex-1 overflow-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left w-[30px] max-w-[30px] pr-0">
                            <Checkbox @change="onSelectAllChange" v-model:checked="allSelected" />
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Name
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Path
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr @click="$event => toggleFileSelect(file, i, $event)" v-for="(file, i) of allFiles.data"
                        :key="file.id"
                        class="cursor-pointer border-b transition duration-300 ease-in-out hover:bg-blue-100"
                        :class="(selected[file.id] || allSelected) ? 'bg-blue-50' : 'bg-white'">
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-[30px] max-w-[30px] pr-0">
                            <Checkbox @change="$event => onSelectCheckboxChange(file)" v-model="selected[file.id]"
                                :checked="selected[file.id] || allSelected" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex items-center">
                            <FileIcon :file="file" />
                            {{ file.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ file.path }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-if="!allFiles.data.length" class="py-8 text-center text-lg tex-gray-400">
                There is no data in this folder
            </div>
            <div ref="loadMoreIntersect"></div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import DownloadFilesButton from '@/Components/app/DownloadFilesButton.vue';
import FileIcon from '@/Components/app/FileIcon.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { httpGet } from '@/Helper/http-helper';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, onMounted, onUpdated, ref } from 'vue';

const allSelected = ref(false);
const selected = ref({});
const loadMoreIntersect = ref(null)
const firstClick = ref(null);
const first = ref(null);

const props = defineProps({
    files: Object,
    folder: Object,
    ancestors: Object,
})

const allFiles = ref({
    data: props.files.data,
    next: props.files.links.next
})

let selectedIds = computed(() => Object.entries(selected.value).filter(a => a[1]).map(a => a[0]))

function cleanIds() {
    allSelected.value = false;
    selected.value = {}
}

function loadMore() {
    if (allFiles.value.next === null) {
        return
    }

    httpGet(allFiles.value.next)
        .then(res => {
            allFiles.value.data = [...allFiles.value.data, ...res.data]
            allFiles.value.next = res.links.next
        })
}

function onSelectAllChange() {
    allFiles.value.data.forEach(f => {
        selected.value[f.id] = allSelected.value;
    })
}

function toggleFileSelect(file, start, event) {
    let shift = event.shiftKey;
    selected.value[file.id] = !selected.value[file.id];
    onSelectCheckboxChange(file, start, shift);
}

function onSelectCheckboxChange(file, start, shift) {

    if (shift) {
        if (firstClick.value === null) {
            firstClick.value = start;
            first.value = selected.value[file.id]
        }

        let shifter = firstClick.value;
        console.log(selected.value[file.id], start)
        if (shifter < start) {
            if (first.value) {
                for (shifter; shifter <= start; shifter++) {
                    const nextFile = allFiles.value.data[shifter];
                    selected.value[nextFile.id] = true;
                }
            } else {
                for (shifter; shifter <= start; shifter++) {
                    const nextFile = allFiles.value.data[shifter];
                    selected.value[nextFile.id] = false;
                }
            }

        } else {
            if (first.value) {
                for (shifter; shifter >= start; shifter--) {
                    const nextFile = allFiles.value.data[shifter];
                    selected.value[nextFile.id] = true;
                }
            } else {
                for (shifter; shifter >= start; shifter--) {
                    const nextFile = allFiles.value.data[shifter];
                    selected.value[nextFile.id] = false;
                }
            }
        }


    }

    if (!selected.value[file.id]) {
        allSelected.value = false;

    } else {
        let checked = true;
        for (let file of allFiles.value.data) {
            if (!selected.value[file.id]) {
                checked = false;
                break;
            }
        }

        allSelected.value = checked;
    }

    if (firstClick.value != start) {
        firstClick.value = null;
        first.value = null;
    }
}

onUpdated(() => {
    allFiles.value = {
        data: props.files.data,
        next: props.files.links.next,
    }
})


onMounted(() => {
    const observer = new IntersectionObserver((entries) => entries.forEach(entry => entry.isIntersecting && loadMore()), {
        rootMargin: '-250px 0px 0px 0px'
    })

    observer.observe(loadMoreIntersect.value)
})

</script>