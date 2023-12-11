<template>
    <div class="row">
        <div ref="globalFiltersRef" :class="{ fixed: isFixed }">
            <GlobalFilters @update-filter="updateFilter" />
        </div>
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
import { ref, provide, onMounted, onUnmounted } from "vue";

const props = defineProps(["domain", "withNotFound", "withExternal"]);
const filter = ref({
    period: "past_30_days",
    metric: "unique_visitors",
    filters: JSON.stringify([]),
    counter: 1,
});
provide("filter", filter);
provide("domain", props.domain);
const globalFiltersRef = ref(null);
const isFixed = ref(false);

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});
const handleScroll = () => {
    if (globalFiltersRef.value) {
        isFixed.value = window.scrollY > globalFiltersRef.value.offsetTop;
    }
};

const updateFilter = (newFilterType) => {
    filter.value.period = newFilterType;
};
</script>
<style scoped>
.fixed {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 99;
    padding-left: 7%;
    padding-right: 7%;
    background: white;
    padding-top: 1rem;
    border-bottom: 1px solid #cecece;
}
</style>
