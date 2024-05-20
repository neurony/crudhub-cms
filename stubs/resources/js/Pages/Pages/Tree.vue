<template>
    <div class="w-full xl:w-1/4 xl:mr-8 mb-8 xl:mb-0 py-4 px-6 bg-white overflow-hidden rounded-md shadow ring-1 ring-gray-600/5 ring-opacity-5">
        <Draggable
            v-model="tree"
            @after-drop="rebuildTree"
            :default-open="true"
            :text-key="'name'"
            :tree-line="true"
            :indent="20"
            class="mtl-tree"
        >
            <template #default="{ node, stat }">
                <OpenIcon
                    v-if="stat.children.length"
                    @click.native="stat.open = !stat.open"
                    :open="stat.open"
                />
                <span
                    @click.prevent="loadTreeLeaves(stat)"
                    :class="[node.name === 'Pages' ? 'font-semibold' : 'text-gray-600']"
                    class="mtl-ml cursor-pointer"
                >
                    {{ node.name }}
                </span>
            </template>
        </Draggable>

        <div class="bg-indigo-50 px-4 py-2.5 rounded-md col-span-6 mt-6">
            <div class="text-xs text-indigo-900 mb-4">
                Clicking on an arrow icon expands the tree, displaying that node's children.
            </div>
            <div class="text-xs text-indigo-900 mb-4">
                Clicking on a tree node itself displays its children records on the right.
            </div>
            <div class="text-xs text-indigo-900 mb-4">
                Creating a new page after clicking on a tree node, will create a child of that page you clicked on from the tree.
            </div>
            <div class="text-xs text-indigo-900">
                Having issues?
                <button @click.prevent="fixTree" type="button" class="font-medium underline inline">
                    Fix the tree
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {
    router,
    useForm,
} from '@inertiajs/vue3';

import {
    Draggable,
    OpenIcon,
} from '@he-tree/vue';

import '@he-tree/vue/style/default.css';
import '@he-tree/vue/style/material-design.css';

export default {
    emits: [
        'leaf-clicked',
    ],
    props: {
        query: Object,
        items: Object,
    },
    components: {
        Draggable,
        OpenIcon,
    },
    data() {
        return {
            tree: [
                {
                    id: null,
                    name: 'Pages',
                    children: this.$page.props.options.tree,
                },
            ],
        }
    },
    methods: {
        fixTree() {
            router.post(route('admin.pages.tree.fix'), {}, {
                preserveState: true,
                preserveScroll: true,
            });
        },
        rebuildTree() {
            let form = useForm({
                tree: this.tree[0].children
            });

            form.post(route('admin.pages.tree.rebuild'), {
                preserveState: true,
                preserveScroll: true,
            });
        },
        loadTreeLeaves(stat) {
            this.$emit('leaf-clicked', {
                parent: stat.data
            });

            router.get(route('admin.pages.index'), {
                ...this.query,
                parent: stat.data.id,
            }, {
                preserveState: true,
                preserveScroll: true,
            });
        },
    },
}
</script>
