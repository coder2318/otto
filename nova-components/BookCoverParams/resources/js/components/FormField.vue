<template>
    <DefaultField :field="field" :errors="errors" :show-help-text="showHelpText" :fullWidth="true">
        <template #field>
            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="p-2 text-left">Field Name</th>
                        <th class="p-2 text-left">Field Key</th>
                        <th class="p-2 text-left">Type</th>
                        <th class="p-2 text-left">Default Value</th>
                        <th class="p-2 text-left">Group</th>
                        <th class="p-2 text-left">Actions</th>
                    </tr>
                </thead>
                <draggable v-model="value" tag="tbody" style="list-style-type: disc" class="w-full">
                    <template #item="{ element, index }">
                        <tr class="form-group-fields" style="cursor: grab">
                            <td>
                                <input
                                    :id="`${field.attribute}[${index}][name]`"
                                    type="text"
                                    class="form-input form-input-bordered form-control"
                                    :class="errorClasses"
                                    placeholder="Field Name"
                                    v-model="element.name"
                                    required
                                />
                            </td>
                            <td>
                                <input
                                    :id="`${field.attribute}[${index}][key]`"
                                    type="text"
                                    class="form-input form-input-bordered form-control"
                                    :class="errorClasses"
                                    placeholder="Field Key"
                                    v-model="element.key"
                                    required
                                />
                            </td>
                            <td>
                                <select
                                    :id="`${field.attribute}[${index}][type]`"
                                    class="w-full block form-control form-select form-select-bordered"
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
                            </td>
                            <td>
                                <input
                                    v-if="
                                        element.type === 'text' || element.type === 'number' || element.type === 'color'
                                    "
                                    :id="`${field.attribute}[${index}][defaultValue]`"
                                    :type="element.type"
                                    class="w-full form-input form-input-bordered form-control"
                                    :class="errorClasses"
                                    placeholder="Default Value"
                                    v-model="element.defaultValue"
                                />
                                <select
                                    v-if="element.type === 'font'"
                                    :id="`${field.attribute}[${index}][defaultValue]`"
                                    class="w-full block form-control form-select form-select-bordered"
                                    :class="errorClasses"
                                    v-model="element.defaultValue"
                                >
                                    <option v-for="font in fonts" :key="font.value" :value="font.value">
                                        {{ font.name }}
                                    </option>
                                </select>
                            </td>
                            <td>
                                <input
                                    :id="`${field.attribute}[${index}][group]`"
                                    type="text"
                                    class="form-input form-input-bordered form-control"
                                    :class="errorClasses"
                                    placeholder="Field Group"
                                    v-model="element.group"
                                />
                            </td>
                            <td>
                                <button type="button" class="delete-btn" @click.prevent="removeField(index)">X</button>
                            </td>
                        </tr>
                    </template>
                </draggable>
            </table>

            <button
                type="button"
                class="bg-primary-500 hover:bg-primary-400 ring-primary-200 bg-primary-500 hover:bg-primary-400 relative inline-flex h-9 cursor-pointer items-center justify-center rounded px-3 text-sm font-bold text-white shadow focus:outline-none focus:ring dark:text-gray-900 dark:ring-gray-600"
                @click.prevent="addField"
            >
                + Add Field
            </button>
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

    computed: {
        fonts() {
            return this.field.fonts
        },
    },

    methods: {
        setInitialValue() {
            this.value = this.arrayVal(this.field.value).map((field) => {
                if (field.type === 'font' && !field.defaultValue && this.fonts.length > 0) {
                    field.defaultValue = this.fonts[0].value
                }
                if (field.type === 'color' && !field.defaultValue) {
                    field.defaultValue = '#000000'
                }

                return field
            })
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
                defaultValue: null,
            })
        },
        removeField(index) {
            this.value.splice(index, 1)
        },
    },
}
</script>
