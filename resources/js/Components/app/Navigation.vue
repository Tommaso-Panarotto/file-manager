<template>
    <nav class="min-w-[200px]">
        <div>
            <Link :href="route('myFiles')" class="h-[80px] px-3 flex items-center gap-3">
            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
            LaraStorage
            </Link>
        </div>
        <div class="px-3">
            <CreateNewDropdown />

            <div class="py-3">
                <NavLink :href="route('myFiles')" :active="$page.props.route_name === 'myFiles'">My Files</NavLink>
                <NavLink :href="route('file.sharedWithMe')" :active="$page.props.route_name === 'file.sharedWithMe'">
                    Shared with
                    Me</NavLink>
                <NavLink :href="route('file.sharedByMe')" :active="$page.props.route_name === 'file.sharedByMe'">Shared
                    by
                    Me
                </NavLink>
                <NavLink :href="route('trash')" :active="$page.props.route_name === 'trash'">Trash</NavLink>

            </div>
        </div>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import CreateNewDropdown from './CreateNewDropdown.vue';
import ApplicationLogo from '../ApplicationLogo.vue';
import NavLink from '../NavLink.vue';

</script>