<template>
    <DefaultField
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
        :full-width-content="fullWidthContent"
    >
        <template #field>
            <draggable v-model="value" tag="ul" style="list-style-type: disc;">
                <template #item="{ element, index }">
                    <li class="form-group-fields" style="cursor:grab;">
                        <input
                            :id="`${field.attribute}[${index}][name]`"
                            type="text"
                            class="form-control form-input form-input-bordered"
                            :class="errorClasses"
                            placeholder="Field Name"
                            v-model="element.name"
                            required
                        />
                        <input
                            :id="`${field.attribute}[${index}][key]`"
                            type="text"
                            class="form-control form-input form-input-bordered"
                            :class="errorClasses"
                            placeholder="Field Key"
                            v-model="element.key"
                            required
                        />
                        <select :id="`${field.attribute}[${index}][type]`"
                            class="form-control form-select form-select-bordered"
                            :class="errorClasses"
                            v-model="element.type"
                            required
                        >
                            <option value="text">Text</option>
                            <option value="color">Color</option>
                            <option value="image">Image</option>
                            <option value="font">Font</option>
                            <option value="number">Number</option>
                        </select>
                        <input
                            :id="`${field.attribute}[${index}][group]`"
                            type="text"
                            class="form-control form-input form-input-bordered"
                            :class="errorClasses"
                            placeholder="Field Group"
                            v-model="element.group"
                        />
                        <button type="button" class="delete-btn" @click.prevent="removeField(index)">X</button>
                    </li>
                </template>
            </draggable>

            <button type="button" class="shadow relative bg-primary-500 hover:bg-primary-400 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900" @click.prevent="addField">+ Add Field</button>
        </template>
    </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import Draggable from 'vuedraggable'

export default {
    components: { Draggable },

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
                group: null,
            })
        },
        removeField(index) {
            this.value.splice(index, 1)
        }
    },
}
</script>
