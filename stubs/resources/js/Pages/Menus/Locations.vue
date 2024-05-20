<template>
    <PageHeader title="Menus" subtitle="Select a menu location" />

    <PageContent>
        <LocationButtons :locations="locations" class="mb-8" />

        <div class=" bg-indigo-50 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <InformationCircleIcon class="h-5 w-5 text-indigo-600" />
                </div>
                <div class="ml-3">
                    <p class="text-sm text-indigo-700">
                        Click on a menu location above to see the menu items in that location
                    </p>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script>
import {
    InformationCircleIcon
} from "@heroicons/vue/24/solid";

import PageContent from "crudhub/Components/PageContent.vue";
import PageHeader from "crudhub/Components/PageHeader.vue";
import LocationButtons from "./Locations/Buttons.vue";

export default {
    components: {
        LocationButtons,
        InformationCircleIcon,
        PageContent,
        PageHeader,
    },
    data() {
        return {
            locations: this.$page?.props?.options?.locations ?? [],
        }
    },
}
</script>