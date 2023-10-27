<template>
    <PanelItem :index="index" :field="field">
        <template #value>
            <ul style="list-style-type: disc">
                <li v-for="value in field.value" :key="value">
                    {{ value }}
                </li>
            </ul>
        </template>
    </PanelItem>
</template>

<script>
export default {
  props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
}
</script>
