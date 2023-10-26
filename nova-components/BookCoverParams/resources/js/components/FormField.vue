<template>
    <DefaultField
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
        :full-width-content="fullWidthContent"
    >
        <template #field>
            <div v-for="item, index in value" class="form-group-fields">
                <input
                    :id="`${field.attribute}[${index}][name]`"
                    type="text"
                    class="form-control form-input form-input-bordered"
                    :class="errorClasses"
                    placeholder="Field Name"
                    v-model="item.name"
                    required
                />
                <input
                    :id="`${field.attribute}[${index}][key]`"
                    type="text"
                    class="form-control form-input form-input-bordered"
                    :class="errorClasses"
                    placeholder="Field Key"
                    v-model="item.key"
                    required
                />
                <select :id="`${field.attribute}[${index}][type]`"
                    class="form-control form-select form-select-bordered"
                    :class="errorClasses"
                    v-model="item.type"
                    required
                >
                    <option value="text">Text</option>
                    <option value="color">Color</option>
                    <option value="image">Image</option>
                </select>
                <button type="button" class="delete-btn" @click.prevent="removeField(index)">X</button>
            </div>

            <button type="button" class="shadow relative bg-primary-500 hover:bg-primary-400 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900" @click.prevent="addField">+ Add Field</button>
        </template>
    </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    methods: {
        setInitialValue() {
            this.value = this.arrayVal(this.field.value)
        },
        fill(formData) {
            formData.append(this.fieldAttribute, JSON.stringify(this.value))
        },
        arrayVal(value) {
            if (value) {
                return Array.isArray(value) ? value : Object.values(value)
            }

            return []
        },
        addField() {
            this.value.push({
                name: '',
                key: '',
                type: 'text',
            })
        },
        removeField(index) {
            this.value.splice(index, 1)
        }
    },
}
</script>
