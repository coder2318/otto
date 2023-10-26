<template>
    <PanelItem :index="index" :field="field">
        <template #value>
            <ol class="book-cover-params">
                <li v-for="item in field.value"><b>{{ item.name }}</b> (<i>{{ item.key }}</i>: {{ item.type }})</li>
            </ol>
        </template>
    </PanelItem>
</template>

<script>
export default {
    props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
}
</script>
