<template>
    <PageHeader title="Create admin" :subtitle="newRecord">
        <ButtonSaveContinue @click="storeRecord(true)" />
        <ButtonSave @click="storeRecord()" />
    </PageHeader>

    <PageContent>
        <Form :form="form" :errors="errors" />
    </PageContent>
</template>

<script>
import {
    useForm,
} from '@inertiajs/vue3';

import ButtonLogout from "crudhub/Components/ButtonLogout.vue";
import ButtonSave from "crudhub/Components/ButtonSave.vue";
import ButtonSaveContinue from "crudhub/Components/ButtonSaveContinue.vue";
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
        ButtonSaveContinue,
        Form,
        PageContent,
        PageHeader,
    },
    data() {
        return {
            form: useForm({
                save_continue: false,
                name: null,
                code: null,
                default: 0,
                active: 0,
            }),
        }
    },
    methods: {
        storeRecord(saveContinue) {
            this.form.save_continue = saveContinue;

            this.form.post(route('admin.languages.store'));
        },
    },
    computed: {
        newRecord() {
            return this.form.name ?? 'Undefined';
        },
    }
}
</script>
