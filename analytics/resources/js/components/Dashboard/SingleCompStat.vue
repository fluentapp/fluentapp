<template>
    <div
        class="px-4 pt-1 border-end mb-1"
        role="button"
        v-tooltip.top="kFormatter(props.value) + ' ' + props.name.toUpperCase()"
        @click="updateMetricValue(props.metric)"
        :class="{ 'active-metric1': props.metric === filter.metric }"
    >
        <p class="fw-bold m-0 text-uppercase fs-6 metric-title">
            {{ props.name }}
        </p>
        <Skeleton v-if="props.loading" width="4rem" class="my-3"></Skeleton>

        <div v-else class="d-flex justify-content-between">
            <div class="py-2">
                <span class="fw-bold fs-4">{{ applyFormat(props.value) }}</span>
            </div>
            <div class="py-2 d-none">
                <span class="fs-6"
                    ><i class="pi pi-arrow-down" style="font-size: 0.7rem"></i>
                    {{ props.comparisonChange }} %
                </span>
            </div>
        </div>
    </div>
</template>
<script setup>
import { inject } from "vue";
import {
    kFormatter,
    secondsToMinutesSeconds,
    numberToPercentage,
} from "./../../helpers";
const filter = inject("filter", {});

const props = defineProps([
    "metric",
    "name",
    "value",
    "type",
    "comparisonValue",
    "comparisonChange",
    "loading",
]);

const applyFormat = (value) => {
    switch (props.type) {
        case "number":
            return kFormatter(value);
        case "percentage":
            return numberToPercentage(value);
        case "time":
            return secondsToMinutesSeconds(value);
        default:
            return value;
    }
};

const updateMetricValue = (metric) => {
    // filter.value.metric = metric;
};
</script>
<style>
.active-metric {
    background-color: #4370ff0a;
}
.active-metric .metric-title {
    color: #327bff;
}
</style>
