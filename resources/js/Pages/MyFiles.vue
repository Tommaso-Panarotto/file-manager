<template>
    <AuthenticatedLayout>
        <nav class="flex items-center justify-between p-1 mb-">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li v-for="ans of ancestors.data" :key="ans.id" class="inline-flex items-center">
                    <Link v-if="!ans.parent_id" :href="route('myFiles')"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 gap-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    MyFiles
                    </Link>
                    <div v-else class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                        <Link :href="route('myFiles', { folder: ans.path })"
                            class="ml-1 text-sm font-medium text-gray-600 hover:text-blue-600">
                        {{ ans.name }}
                        </Link>
                    </div>
                </li>
            </ol>
            <div class="flex">
                <label class="flex items-center mr-3">
                    <Checkbox @change="showOnlyFavourites" v-model:checked="onlyFavourites" class="mr-2" />
                    Only Favourites
                </label>
                <DownloadFilesButton :all="allSelected" :ids="selectedIds" class="mr-2" />
                <DeleteFilesButton :delete-all="allSelected" :delete-ids="selectedIds" @clean="cleanIds" />
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

                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Name
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Owner
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Last Modified
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Size
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr @dblclick="openFolder(file)" @click="$event => toggleFileSelect(file, i, $event)"
                        v-for="(file, i) of allFiles.data" :key="file.id"
                        class="cursor-pointer border-b transition duration-300 ease-in-out hover:bg-blue-100"
                        :class="(selected[file.id] || allSelected) ? 'bg-blue-50' : 'bg-white'">
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-[30px] max-w-[30px] pr-0">
                            <Checkbox @change="$event => onSelectCheckboxChange(file)" v-model="selected[file.id]"
                                :checked="selected[file.id] || allSelected" />
                        </td>
                        <td class="px-6 py-4 max-w-[40px] text-sm font-medium text-gray-900 text-yellow-500">
                            <div @click.stop.prevent="addRemoveFavourite(file)">
                                <svg v-if="!file.is_favourite" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-6">
                                    <path fill-rule="evenodd"
                                        d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </td>
                        <td class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex items-center">
                            <FileIcon :file="file" />
                            {{ file.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ file.owner }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ file.updated_at }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ file.size }}
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
import DeleteFilesButton from '@/Components/app/DeleteFilesButton.vue';
import DownloadFilesButton from '@/Components/app/DownloadFilesButton.vue';
import FileIcon from '@/Components/app/FileIcon.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { httpGet, httpPost } from '@/Helper/http-helper';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, onUpdated, ref } from 'vue';
import { showSuccessNotification } from '@/event-bus';

const allSelected = ref(false);
const onlyFavourites = ref(false);
const selected = ref({});
const loadMoreIntersect = ref(null)
const firstClick = ref(null);
const first = ref(null);

const props = defineProps({
    files: Object,
    folder: Object,
    ancestors: Object,
})

const page = usePage();

const allFiles = ref({
    data: props.files.data,
    next: props.files.links.next
})

let params = null;

let selectedIds = computed(() => Object.entries(selected.value).filter(a => a[1]).map(a => a[0]))

function cleanIds() {
    allSelected.value = false;
    selected.value = {}
}

function openFolder(file) {

    if (!file.is_folder) {
        return
    }

    router.visit(route('myFiles', { folder: file.path }))
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

function addRemoveFavourite(file) {
    httpPost(route('file.addToFavourites'), { id: file.id })
        .then(() => {
            file.is_favourite = !file.is_favourite;
            if (file.is_favourite) {
                showSuccessNotification('File added to favourites');
            } else {
                showSuccessNotification('File removed from favourites');
            }
        })
        .catch(async (er) => {
            console.log(er.error.message);
        })
}

function showOnlyFavourites() {

    onlyFavourites.value ? params.set('favourites', 1) : params.delete('favourites');

    router.get(window.location.pathname + '?' + params.toString());
}

onUpdated(() => {
    allFiles.value = {
        data: props.files.data,
        next: props.files.links.next,
    }
})


onMounted(() => {
    params = new URLSearchParams(window.location.search);
    onlyFavourites.value = params.get('favourites') === '1';

    const observer = new IntersectionObserver((entries) => entries.forEach(entry => entry.isIntersecting && loadMore()), {
        rootMargin: '-250px 0px 0px 0px'
    })

    observer.observe(loadMoreIntersect.value)
})

</script>