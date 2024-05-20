<template>
    <PageHeader title="Edit menu" :subtitle="`Last updated on ${lastUpdatedDate}`">
        <ButtonSaveStay @click="updateRecord(true)" />
        <ButtonSave @click="updateRecord()" />
    </PageHeader>

    <PageContent>
        <Form :form="form" :errors="errors" />
    </PageContent>
</template>

<script>
import moment from "moment";

import {
    useForm,
} from '@inertiajs/vue3';

import ButtonLogout from "crudhub/Components/ButtonLogout.vue";
import ButtonSave from "crudhub/Components/ButtonSave.vue";
import ButtonSaveStay from "crudhub/Components/ButtonSaveStay.vue";
import Form from "./Form.vue";
import PageContent from "crudhub/Components/PageContent.vue";
import PageHeader from "crudhub/Components/PageHeader.vue";

export default {
    props: {
        auth: Object,
        item: Object,
        errors: Object
    },
    components: {
        ButtonLogout,
        ButtonSave,
        ButtonSaveStay,
        Form,
        PageContent,
        PageHeader,
    },
    data() {
        return {
            form: useForm({
                save_stay: false,
                parent_id: this.$page?.props?.parent?.data?.id ?? null,
                menuable_id: this.item.data?.menuable_id ?? null,
                menuable_type: this.item.data?.menuable_type ?? null,
                name: this.item.data?.name ?? null,
                type: this.item.data?.type ?? null,
                location: this.item.data?.location ?? null,
                url: this.item.data?.url ?? null,
                route: this.item.data?.route ?? null,
                active: this.item.data?.active ?? 0,
                meta_data: this.item.data?.meta_data ?? {},
            }),
        }
    },
    methods: {
        updateRecord(saveStay = false) {
            this.form.save_stay = saveStay;

            this.form.put(route('admin.menus.update', {
                menu: this.item.data.id,
            }));
        },
    },
    computed: {
        lastUpdatedDate() {
            return moment(this.item.data.updated_at).format('DD MMMM, YYYY [at] HH:mm');
        },
    }
}
</script>
