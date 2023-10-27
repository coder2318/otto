<template>
    <DefaultField :field="field" :errors="errors" :show-help-text="showHelpText" :full-width-content="fullWidthContent">
        <template #field>
            <div v-for=",index in value" class="form-group-fields flex w-full">
                <input
                    type="text"
                    class="form-control form-input form-input-bordered flex-1"
                    :class="errorClasses"
                    placeholder="Enter your field"
                    v-model="value[index]"
                    required
                />
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
            this.value.push('')
        },
        removeField(index) {
            this.value.splice(index, 1)
        }
    },
}
</script>
