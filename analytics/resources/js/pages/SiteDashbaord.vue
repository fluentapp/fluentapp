<template>
    <div class="row">
        <GlobalFilters @update-filter="updateFilter" />
        <Visitors />
        <Sources />
        <Pages />
        <Locations />
        <Devices />
        <NotFound v-if="props.withNotFound" />
        <ExternalLinks v-if="props.withExternal" />
    </div>
</template>
<script setup>
import { ref, provide } from "vue";

const props = defineProps(["domain", "withNotFound", "withExternal"]);
const filter = ref({
    period: "past_30_days",
    metric: "unique_visitors",
    filters: JSON.stringify([]),
    counter: 1,
});
provide("filter", filter);
provide("domain", props.domain);

const updateFilter = (newFilterType) => {
    filter.value.period = newFilterType;
};
</script>
