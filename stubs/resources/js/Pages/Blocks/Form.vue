<template>
    <Form columns="2">
        <FormSection title="Primary info" col-span="1">
            <div class="sm:col-span-6">
                <InputText
                    v-model="form.name"
                    v-model:error="errors.name"
                    name="name"
                    label="Name"
                />
            </div>
            <div class="sm:col-span-6">
                <InputText
                    v-model="form.code"
                    v-model:error="errors.code"
                    name="code"
                    label="Code"
                />
            </div>
        </FormSection>

        <FormSection title="Secondary info" col-span="1">
            <div class="sm:col-span-6">
                <InputSelect
                    v-model="form.default"
                    v-model:error="errors.default"
                    :options="{1: 'Yes', 0: 'No'}"
                    name="default"
                    label="Is default?"
                />
            </div>
            <div class="sm:col-span-6">
                <InputSelect
                    v-model="form.active"
                    v-model:error="errors.active"
                    :options="{1: 'Yes', 0: 'No'}"
                    name="active"
                    label="Is active?"
                />
            </div>
        </FormSection>
    </Form>
</template>

<script>
import Form from "crudhub/Components/Form.vue";
import FormDivider from "crudhub/Components/FormDivider.vue";
import FormSection from "crudhub/Components/FormSection.vue";
import InputSelect from "crudhub/Components/InputSelect.vue";
import InputText from "crudhub/Components/InputText.vue";

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
        InputSelect,
        InputText,
    },
}
</script>
