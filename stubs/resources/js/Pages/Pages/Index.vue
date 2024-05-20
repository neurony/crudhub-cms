<template>
    <PageHeader :title="pageTitle" subtitle="Manage your pages">
        <ButtonAdd :url="route('admin.pages.create', { 'parent': parent.id })" />
    </PageHeader>

    <PageContent>
        <div class="flex flex-col xl:flex-row items-start">
            <Tree @leaf-clicked="this.parent = $event.parent" />

            <div class="w-full xl:w-3/4 overflow-hidden rounded-md shadow ring-1 ring-gray-600/5 ring-opacity-5">
                <Filter />

                <Table :items="items.data" :bulk-delete="true" @delete-multiple="bulkDeleteRecords">
                    <template #head>
                        <TableTh sort-by="name" :sticky-start="true">Name</TableTh>
                        <TableTh sort-by="type">Type</TableTh>
                        <TableTh sort-by="active">Active</TableTh>
                        <TableTh :sticky-end="true"></TableTh>
                    </template>

                    <template #row="{element, index}">
                        <TableTd :sticky-start="true">
                            <div class="font-medium text-gray-900">{{ element.name }}</div>
                            <div class="text-indigo-500 underline">
                                <a :href="pageSlug(element)" target="_blank">{{ pageSlug(element) }}</a>
                            </div>
                        </TableTd>
                        <TableTd>
                            <Badge type="info">{{ pageType(element) }}</Badge>
                        </TableTd>
                        <TableTd>
                            <InputToggle v-model="element.active" @toggled="updateActive($event, element.id)"/>
                        </TableTd>
                        <TableTd :sticky-end="true">
                            <div class="flex items-center justify-end gap-3">
                                <ButtonEdit :url="route('admin.pages.edit', element.id)" />
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

import Badge from "crudhub/Components/Badge.vue";
import ButtonAdd from "crudhub/Components/ButtonAdd.vue";
import ButtonDelete from "crudhub/Components/ButtonDelete.vue";
import ButtonEdit from "crudhub/Components/ButtonEdit.vue";
import DialogConfirm from "crudhub/Components/DialogConfirm.vue";
import Filter from "./Filter.vue";
import InputToggle from "crudhub/Components/InputToggle.vue";
import PageContent from "crudhub/Components/PageContent.vue";
import PageHeader from "crudhub/Components/PageHeader.vue";
import Pagination from "crudhub/Components/Pagination.vue";
import Table from "crudhub/Components/Table.vue";
import TableTd from "crudhub/Components/TableTd.vue";
import TableTh from "crudhub/Components/TableTh.vue";
import Tree from "./Tree.vue";

export default {
    props: {
        query: Object,
        items: Object,
    },
    components: {
        Badge,
        ButtonAdd,
        ButtonDelete,
        ButtonEdit,
        DialogConfirm,
        Filter,
        InputToggle,
        PageContent,
        PageHeader,
        Pagination,
        Table,
        TableTd,
        TableTh,
        Tree,
    },
    data() {
        return {
            deletion: {
                id: null,
                confirm: false,
            },
            parent: {
                name: null,
                id: null,
            },
        }
    },
    methods: {
        pageSlug(page) {
            if (page.slug === '/') {
                return '/';
            }

            return `/${page.slug}`;
        },
        pageType(page) {
            let item = this.$page.props.options.types.find(obj => obj.value === page.type);

            return item ? item.label : 'N/A';
        },
        updateActive(value, id) {
            router.patch(route('admin.pages.partial_update', id), {
                active: value,
            }, {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            });
        },
        deleteRecord() {
            router.delete(route('admin.pages.destroy', this.deletion.id), {
                preserveState: true,
                preserveScroll: true,
                onFinish: () => {
                    this.deletion.confirm = false;
                },
            });
        },
        bulkDeleteRecords(ids) {
            router.post(route('admin.pages.bulk_destroy'), {
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
    computed: {
        pageTitle() {
            let title = 'Pages';

            if (this.parent?.name && this.parent?.name !== 'Pages') {
                title += ` / ${this.parent.name}`;
            }

            return title;
        },
    }
}
</script>
