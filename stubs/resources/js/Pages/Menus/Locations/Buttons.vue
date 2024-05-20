<template>
    <div :class="{'opacity-50 disable-clicks': request.ongoing}" class="col-span-6 flex flex-wrap gap-4">
        <div class="flex w-full">
            <Link v-for="location in locations" :href="route('admin.menus.index', {location: location.value})" :disabled="request.ongoing" type="button" :class="location.value === selected ? selectedButtonClass : defaultButtonClass">
                {{ location.label }}
            </Link>
        </div>
    </div>
</template>

<script>
import {
    Link,
} from '@inertiajs/vue3';

import {
    request,
} from "crudhub/constants.js";

export default {
    props: {
        locations: {
            type: Array,
            required: true,
        },
        selected: {
            type: String,
            required: false,
            default: null,
        }
    },
    components: {
        Link,
    },
    data() {
        return {
            request: request,
        }
    },
    computed: {
        selectedButtonClass() {
            return "flex-grow button-margin transition-all inline-flex items-center justify-center rounded-md bg-indigo-50 px-4 py-2.5 text-sm font-semibold text-indigo-800 shadow-sm ring-1 ring-inset ring-indigo-600 cursor-pointer";
        },
        defaultButtonClass() {
            return "flex-grow button-margin transition-all inline-flex items-center justify-center rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 cursor-pointer";
        },
    },
}
</script>

<style scoped>
.disable-clicks * {
    pointer-events: none;
}

.button-margin:not(:last-child) {
    margin-right: 1.5rem;
}
</style>
