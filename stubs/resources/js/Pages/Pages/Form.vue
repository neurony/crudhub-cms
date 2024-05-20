<template>
    <Form columns="3">
        <FormSection title="Primary info" col-span="2">
            <div class="sm:col-span-3">
                <InputText
                    v-model="form.name"
                    v-model:error="errors.name"
                    name="name"
                    label="Name"
                />
            </div>
            <div class="sm:col-span-3">
                <InputText
                    v-model="form.identifier"
                    v-model:error="errors.identifier"
                    name="identifier"
                    label="Identifier"
                />
            </div>
            <div class="sm:col-span-3">
                <InputSelect
                    v-model="form.type"
                    v-model:error="errors.type"
                    :options="$page.props.options.types"
                    name="type"
                    label="Type"
                />
            </div>
            <div class="sm:col-span-3">
                <InputSelect
                    v-model="form.active"
                    v-model:error="errors.active"
                    :options="{1: 'Yes', 0: 'No'}"
                    name="active"
                    label="Is active?"
                />
            </div>

            <div class="sm:col-span-6 relative">
                <FormDivider title="Page slug" type="muted" class="-mt-4 -mb-2" />

                <div v-if="parent?.data?.slug" class="col-span-6 bg-white absolute top-1 -right-2 px-2 cursor-default">
                    <button type="button" @click.prevent="prefixSlug" class="rounded bg-white px-2.5 py-1.5 text-sm font-medium text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        Prefix with parent slug
                    </button>
                </div>
            </div>

            <div v-if="parent?.data?.slug" class="sm:col-span-3">
                <label class="block text-sm font-medium leading-6 text-gray-600">
                    Parent slug
                </label>
                <div class="px-4 py-3 relative mt-2 rounded-md shadow-xs bg-gray-50 ring-1 ring-inset ring-gray-200 text-gray-600 text-xs">
                    <pre class="whitespace-pre-wrap" v-html="parent?.data?.slug || '[NO SLUG]'"></pre>
                </div>
            </div>
            <div :class="parent?.data?.slug ? 'sm:col-span-3' : 'sm:col-span-6'">
                <InputText
                    v-model="form.slug"
                    v-model:error="errors.slug"
                    name="slug"
                    label="Slug"
                />
            </div>
        </FormSection>

        <FormSection title="Sample image" col-span="1">
            <div class="sm:col-span-6">
                <InputMedia
                    v-model="form.images"
                    collection-name="images"
                    :accepted-mime-types="['image/*']"
                    :multiple-files="false"
                />
            </div>
        </FormSection>

        <FormSection title="Content info" col-span="3">
            <div class="sm:col-span-6">
                <InputText
                    v-model="form.meta_data.title"
                    v-model:error="errors[`meta_data.title`]"
                    name="title"
                    label="Title"
                />
            </div>
            <div class="sm:col-span-6">
                <InputText
                    v-model="form.meta_data.subtitle"
                    v-model:error="errors[`meta_data.subtitle`]"
                    name="subtitle"
                    label="Subtitle"
                />
            </div>
            <div class="sm:col-span-6">
                <InputEditor
                    v-model="form.meta_data.content"
                    v-model:error="errors[`meta_data.content`]"
                    name="content"
                    label="Content"
                />
            </div>
        </FormSection>
    </Form>
</template>

<script>
import Form from "crudhub/Components/Form.vue";
import FormDivider from "crudhub/Components/FormDivider.vue";
import FormSection from "crudhub/Components/FormSection.vue";
import InputMedia from "crudhub/Components/InputMedia.vue";
import InputSelect from "crudhub/Components/InputSelect.vue";
import InputText from "crudhub/Components/InputText.vue";
import InputEditor from "crudhub-cms/Components/InputEditor.vue";

export default {
    props: {
        form: {
            type: Object,
            required: true,
        },
        errors: {
            type: Object,
            required: false,
            default: {},
        }
    },
    components: {
        Form,
        FormDivider,
        FormSection,
        InputEditor,
        InputMedia,
        InputSelect,
        InputText,
    },
    data() {
        return {
            parent: this.$page.props.parent,
        }
    },
    methods: {
        textToSlug(text) {
            this.form.slug = text
                .toString()
                .toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .trim();
        },
        prefixSlug() {
            let slug = `${this.parent.data.slug}`;

            if (this.form.slug) {
                slug += `/${this.form.slug}`;
            }

            this.form.slug = _.trim(slug.replace(/\/+/g, '/'), '/');
        },
    },
    watch: {
        'form.name': function (newValue, oldValue) {
            this.textToSlug(newValue);
        },
    },
}
</script>
