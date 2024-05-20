<template>
    <div>
        <label v-if="label" :for="`input-text-${name}`" class="block text-sm font-medium leading-6 text-gray-900">
            {{ label }}
        </label>

        <div class="relative mt-2 rounded-md shadow-sm">
            <Editor
                @change="handleInputChanged"
                :initial-value="modelValue"
                :api-key="editorLicense"
                :init="editorOptions"
            />
        </div>

        <p v-if="errorMessage" class="mt-2 text-sm text-red-600" :id="`${name}-error`">
            {{ errorMessage }}
        </p>
    </div>
</template>

<script>
import {
    ExclamationCircleIcon
} from '@heroicons/vue/24/solid';

import Editor from '@tinymce/tinymce-vue';

export default {
    emits: [
        'update:modelValue',
        'inputChanged',
    ],
    props: {
        modelValue: {
            type: String|Number,
            required: false,
            default: null,
        },
        name: {
            type: String,
            required: true,
        },
        error: {
            type: String,
            required: false,
            default: null,
        },
        label: {
            type: String,
            required: false,
        },
        height: {
            type: Number,
            required: false,
            default: 500,
        },
        plugins: {
            type: String,
            required: false,
            default: 'anchor autolink charmap image link lists searchreplace table wordcount checklist casechange export formatpainter permanentpen powerpaste advtable advcode editimage media inlinecss',
        },
        toolbar: {
            type: String,
            required: false,
            default: 'undo redo | styles fontsize | bold italic underline | link image table | align checklist numlist bullist indent outdent | charmap removeformat | help',
        },
        styles: {
            type: Array,
            required: false,
            default: [
                {
                    title: 'Headings',
                    items: [
                        { title: 'Heading 1', format: 'h1' },
                        { title: 'Heading 2', format: 'h2' },
                        { title: 'Heading 3', format: 'h3' },
                        { title: 'Heading 4', format: 'h4' },
                        { title: 'Heading 5', format: 'h5' },
                        { title: 'Heading 6', format: 'h6' },
                    ],
                },
                {
                    title: 'Inline',
                    items: [
                        { title: 'Bold', format: 'bold' },
                        { title: 'Italic', format: 'italic' },
                        { title: 'Underline', format: 'underline' },
                        { title: 'Strikethrough', format: 'strikethrough' },
                        { title: 'Superscript', format: 'superscript' },
                        { title: 'Subscript', format: 'subscript' },
                        { title: 'Code', format: 'code' },
                    ],
                },
                {
                    title: 'Blocks',
                    items: [
                        { title: 'Paragraph', format: 'p' },
                        { title: 'Blockquote', format: 'blockquote' },
                        { title: 'Div', format: 'div' },
                        { title: 'Pre', format: 'pre' },
                    ],
                },
                {
                    title: 'Align',
                    items: [
                        { title: 'Left', format: 'alignleft' },
                        { title: 'Center', format: 'aligncenter' },
                        { title: 'Right', format: 'alignright' },
                        { title: 'Justify', format: 'alignjustify' },
                    ],
                },
            ]
        },
    },
    components: {
        ExclamationCircleIcon,
        Editor,
    },
    data() {
        return {
            errorCleared: false,
            editorLicense: import.meta.env.VITE_TINYMCE_LICENSE,
            editorOptions: {
                height: this.height,
                plugins: this.plugins,
                toolbar: this.toolbar,
                style_formats: this.styles,
            },
        }
    },
    methods: {
        handleInputChanged(event, editor) {
            this.$emit('update:modelValue', editor.getContent())
            this.$emit('inputChanged');
        },
    },
    computed: {
        errorMessage() {
            return this.errorCleared ? null : this.error;
        },
    }
}
</script>
