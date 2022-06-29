<template>
    <PanelItem :field="field" />
</template>

<script>
export default {
    props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
};
</script>
