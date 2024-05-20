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
                <InputSelect
                    v-model="form.location"
                    v-model:error="errors.location"
                    :options="$page.props?.options?.locations ?? []"
                    name="location"
                    label="Location"
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
            <div v-if="form.type === 'url'" class="sm:col-span-3">
                <InputText
                    v-model="form.url"
                    v-model:error="errors.url"
                    name="url"
                    label="URL"
                />
            </div>
            <div v-if="form.type === 'route'" class="sm:col-span-3">
                <InputSelect
                    v-model="form.route"
                    v-model:error="errors.route"
                    :options="$page.props.options.routes"
                    name="route"
                    label="Route"
                />
            </div>
            <div v-if="form.type && form.type !== 'url' && form.type !== 'route'" class="sm:col-span-3">
                <InputSelect
                    v-model="form.menuable_id"
                    v-model:error="errors.menuable_id"
                    :options="options.menuables"
                    name="menuable_id"
                    label="Record"
                />
            </div>
        </FormSection>
        <FormSection title="Secondary info" col-span="1">
            <div class="sm:col-span-6">
                <InputSelect
                    v-model="form.active"
                    v-model:error="errors.active"
                    :options="{1: 'Yes', 0: 'No'}"
                    name="active"
                    label="Is active?"
                />
            </div>
            <div class="sm:col-span-6">
                <InputSelect
                    v-model="form.meta_data.new_window"
                    v-model:error="errors[`meta_data.new_window`]"
                    :options="{1: 'Yes', 0: 'No'}"
                    name="active"
                    label="Open in new window?"
                />
            </div>
        </FormSection>
    </Form>
</template>

<script>
import axios from "axios";

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
            options: {
                menuables: [],
            },
        }
    },
    methods: {
        async getMenuableOptions(type) {
            const result = await axios.get(route('admin.menus.menuables', {
                type: type
            }));

            return result.data;
        }
    },
    watch: {
        'form.type': async function (newValue, oldValue) {
            if (newValue !== 'url' && newValue !== 'route') {
                this.options.menuables = await this.getMenuableOptions(newValue);
            }
        },
    },
}
</script>
