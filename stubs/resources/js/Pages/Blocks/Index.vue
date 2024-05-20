<template>
    <PageHeader title="Languages" subtitle="Manage your languages">
        <ButtonAdd :url="route('admin.languages.create')" />
    </PageHeader>

    <PageContent>
        <div class="overflow-hidden rounded-md shadow ring-1 ring-gray-600/5 ring-opacity-5">
            <Filter />

            <Table :items="items.data" :bulk-delete="true" @delete-multiple="bulkDeleteRecords">
                <template #head>
                    <TableTh sort-by="name" :sticky-start="true">Language</TableTh>
                    <TableTh sort-by="default">Default</TableTh>
                    <TableTh sort-by="active">Active</TableTh>
                    <TableTh :sticky-end="true"></TableTh>
                </template>

                <template #row="{element, index}">
                    <TableTd :sticky-start="true">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-full">
                                <LanguageImage :locale="element.code" class="h-9 w-9" />
                            </div>

                            <div class="ml-4">
                                <div class="font-medium text-gray-900">{{ element.name }}</div>
                                <div class="text-gray-500">{{ element.code.toUpperCase() }}</div>
                            </div>
                        </div>
                    </TableTd>
                    <TableTd>
                        <InputToggle v-model="element.default" @toggled="updateDefault($event, element.id)"/>
                    </TableTd>
                    <TableTd>
                        <InputToggle v-model="element.active" @toggled="updateActive($event, element.id)"/>
                    </TableTd>
                    <TableTd :sticky-end="true">
                        <div class="flex items-center justify-end gap-3">
                            <ButtonEdit :url="route('admin.languages.edit', element.id)" />
                            <ButtonDelete @click.prevent="confirmDeleteRecord(element.id)" />
                        </div>
                    </TableTd>
                </template>
            </Table>

            <Pagination
                v-if="items.data.length && items.meta"
                :per-page="items.meta.per_page"
                :total-records="items.meta.total"
                :from-record="items.meta.from"
                :to-record="items.meta.to"
                :page-links="items.meta.links"
            />
        </div>
    </PageContent>

    <DialogConfirm :is-opened="deletion.confirm" @confirmed="deleteRecord" @declined="declineDeleteRecord">
        <template #title>
            Delete record?
        </template>
        <template #description>
            Are you sure you want to delete this record?
            <br />
            This action cannot be undone.
        </template>
        <template #confirmbutton>
            Delete
        </template>
    </DialogConfirm>
</template>

<script>
import {
    router,
} from '@inertiajs/vue3';

import AvatarIcon from "crudhub/Components/AvatarIcon.vue";
import Badge from "crudhub/Components/Badge.vue";
import ButtonAdd from "crudhub/Components/ButtonAdd.vue";
import ButtonDelete from "crudhub/Components/ButtonDelete.vue";
import ButtonEdit from "crudhub/Components/ButtonEdit.vue";
import DialogConfirm from "crudhub/Components/DialogConfirm.vue";
import Filter from "./Filter.vue";
import InputToggle from "crudhub/Components/InputToggle.vue";
import LanguageImage from "crudhub-lang/Components/LanguageImage.vue";
import PageContent from "crudhub/Components/PageContent.vue";
import PageHeader from "crudhub/Components/PageHeader.vue";
import Pagination from "crudhub/Components/Pagination.vue";
import Table from "crudhub/Components/Table.vue";
import TableTd from "crudhub/Components/TableTd.vue";
import TableTh from "crudhub/Components/TableTh.vue";

export default {
    props: {
        query: Object,
        items: Object,
    },
    components: {
        AvatarIcon,
        Badge,
        ButtonAdd,
        ButtonDelete,
        ButtonEdit,
        DialogConfirm,
        Filter,
        InputToggle,
        LanguageImage,
        PageContent,
        PageHeader,
        Pagination,
        Table,
        TableTd,
        TableTh,
    },
    data() {
        return {
            deletion: {
                id: null,
                confirm: false,
            },
        }
    },
    methods: {
        updateDefault(value, id) {
            router.patch(route('admin.languages.partial_update', id), {
                default: value,
            }, {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            });
        },
        updateActive(value, id) {
            router.patch(route('admin.languages.partial_update', id), {
                active: value,
            }, {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            });
        },
        deleteRecord() {
            router.delete(route('admin.languages.destroy', this.deletion.id), {
                preserveState: true,
                preserveScroll: true,
                onFinish: () => {
                    this.deletion.confirm = false;
                },
            });
        },
        bulkDeleteRecords(ids) {
            router.post(route('admin.languages.bulk_destroy'), {
                ids: ids,
            }, {
                preserveState: true,
                preserveScroll: true,
            });
        },
        confirmDeleteRecord(id) {
            this.deletion.id = id;
            this.deletion.confirm = true;
        },
        declineDeleteRecord() {
            this.deletion.id = null;
            this.deletion.confirm = false;
        },
    },
}
</script>
