<template>
    <Filter @apply="applyFilters" @clear="clearFilters">
        <div class="sm:col-span-3">
            <InputSelect
                v-model="form.type"
                name="type"
                label="Type"
                placeholder="Any"
                :options="$page.props.options.types"
            ></InputSelect>
        </div>
        <div class="sm:col-span-3">
            <InputSelect
                v-model="form.active"
                name="active"
                label="Active"
                placeholder="Any"
                :options="{1: 'Yes', 0: 'No'}"
            ></InputSelect>
        </div>

        <div class="sm:col-span-3">
            <InputDate
                v-model="form.start_date"
                name="start_date"
                label="Start date"
                format="F j, Y"
            ></InputDate>
        </div>

        <div class="sm:col-span-3">
            <InputDate
                v-model="form.end_date"
                name="end_date"
                label="End date"
                format="F j, Y"
            ></InputDate>
        </div>
    </Filter>
</template>

<script>
import {
    router,
    useForm,
} from '@inertiajs/vue3';

import Filter from "crudhub/Components/Filter.vue";

import InputDate from "crudhub/Components/InputDate.vue";
import InputSelect from "crudhub/Components/InputSelect.vue";

export default {
    components: {
        Filter,
        InputDate,
        InputSelect,
    },
    data() {
        return {
            form: useForm({
                location: this.$page.props.query.location ?? null,
                keyword: this.$page.props.query.keyword ?? null,
                sort_by: this.$page.props.query.sort_by ?? null,
                sort_dir: this.$page.props.query.sort_dir ?? null,
                type: this.$page.props.query.type ?? null,
                active: this.$page.props.query.active ?? null,
                start_date: this.$page.props.query.start_date ?? null,
                end_date: this.$page.props.query.end_date ?? null,
            }),
        }
    },
    methods: {
        applyFilters() {
            this.form.keyword = this.$page.props.query.keyword ?? null;
            this.form.sort_by = this.$page.props.query.sort_by ?? null;
            this.form.sort_dir = this.$page.props.query.sort_dir ?? null;

            this.form.get(route('admin.menus.index'));
        },
        clearFilters() {
            router.get(route('admin.menus.index', {
                location: this.$page.props.query.location ?? null,
            }));
        },
    },
}
</script>
