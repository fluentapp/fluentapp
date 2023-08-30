<template>
    <div class="col-md-12">
        <div class="row mb-2">
            <div class="d-flex flex-row flex-wrap">
                <div class="p-2">
                    <h5>
                        Current Visitors
                        <span
                            role="button"
                            class="badge rounded-pill bg-success"
                            v-tooltip.top="
                                'Last Updated ' + lastUpdatedCounter + 's ago'
                            "
                        >
                            {{ currentVisitors }}
                        </span>
                    </h5>
                </div>
                <div class="mr-auto p-2">
                    <!-- <Chip
                        class="pl-0 pr-3 m-1 bg-white"
                        v-for="(value, key) in JSON.parse(filter.filters)"
                        :key="key"
                        removable
                    >
                        <span class="bg-primary px-2 py-1 text-sm">{{
                            value.key.toUpperCase()
                        }}</span>
                        <span class="ml-2 text-sm">{{
                            value.value.join(" | ")
                        }}</span>
                    </Chip> -->
                </div>
                <div>
                    <div class="formgrid grid">
                        <div class="field col">
                            <Button
                                icon="pi pi-search"
                                severity="primary"
                                aria-label="Search"
                                size="small"
                                label="Filter"
                                outlined
                                :badge="
                                    JSON.parse(filter.filters).length === 0
                                        ? ''
                                        : JSON.parse(
                                              filter.filters
                                          ).length.toString()
                                "
                                badgeClass="p-badge-info"
                                @click="showFilterPopup"
                            />
                        </div>
                        <div
                            class="field col"
                            v-if="
                                selectedFilter === 'custom' ||
                                selectedFilter === 'today'
                            "
                        >
                            <div class="d-inline-flex align-items-center">
                                <Button
                                    icon="pi pi-chevron-left"
                                    severity="secondary"
                                    outlined
                                    aria-label="prev"
                                    @click="goToPreviousDay"
                                    size="small"
                                    class="mx-1"
                                />
                                <Button
                                    icon="pi pi-chevron-right"
                                    severity="secondary"
                                    outlined
                                    aria-label="next"
                                    @click="goToNextDay"
                                    size="small"
                                />
                            </div>
                        </div>
                        <div class="field col">
                            <Dropdown
                                v-model="selectedFilter"
                                :options="dateFilters"
                                optionLabel="name"
                                optionValue="type"
                                placeholder="Select a Filter "
                                class="w-full float-end"
                                size="small"
                                @change="handleFilterDateChange"
                            />
                        </div>
                        <div
                            v-if="selectedFilter === 'custom' && showCalendar"
                            class="field col"
                        >
                            <Calendar
                                v-model="customDate"
                                class="custom-date-calendar"
                                inline
                                @date-select="handleCustomDateSelect"
                                size="small"
                            />
                        </div>
                        <div
                            v-if="selectedFilter === 'custom' && !showCalendar"
                            class="field"
                        >
                            <Button
                                :label="customDate"
                                size="small"
                                @click="showCalendar = true"
                                text
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Filters :visible="showFilters" @close-popup="closePopup" />
</template>

<script setup>
import { ref, inject, defineEmits, onMounted } from "vue";
import moment from "moment";

import { prepareQueryString } from "./../../helpers.js";
import config from "./../../config.json";
const emit = defineEmits(["updateFilter"]);
const filter = inject("filter", {});
const domain = inject("domain");

const showFilters = ref(false);
const selectedFilter = ref(filter.value.period);
const currentVisitors = ref(0);
const lastUpdatedCounter = ref(0);
const customDate = ref(new Date());
const showCalendar = ref(false);

const dateFilters = ref([
    { name: "Today", type: "today" },
    { name: "Real Time", type: "real_time" },
    { name: "Last 7 days", type: "past_7_days" },
    { name: "Last 30 days", type: "past_30_days" },
    { name: "Custom Date", type: "custom" },
]);

const handleFilterDateChange = (event) => {
    if (event.value === "custom") {
        showCalendar.value = true;
    } else {
        showCalendar.value = false;
        filter.value.period = event.value;
    }
};

const handleCustomDateSelect = (event) => {
    filter.value.period = "custom";
    customDate.value = moment(event).format("YYYY-MM-DD");
    filter.value.date = moment(event).format("YYYY-MM-DD");
    showCalendar.value = false;
};
// Call the function to fetch and update the chart initially
onMounted(() => {
    fetchRealTimeData();
    setInterval(fetchRealTimeData, 30 * 1000); // 30  sec interval
    setInterval(lastUpdatedTimer, 1 * 1000); // 1 sec interval
});

// Function to fetch data from the server and update the chart
const fetchRealTimeData = () => {
    if (filter.value.period == "real_time") {
        filter.value.counter = filter.value.counter++; //to trigger the watch in the other componenets to refresh the widgets
    }
    lastUpdatedCounter.value = 0;
    axios
        .get(prepareQueryString(config.baseUrl, domain, "connected-visitors"))
        .then((response) => {
            currentVisitors.value = response.data?.count;
        })
        .catch((error) => {
            if (!axios.isCancel(error)) {
                console.log(error);
            }
        });
};
const goToPreviousDay = () => {
    selectedFilter.value = "custom";
    customDate.value = moment(customDate.value)
        .clone()
        .subtract(1, "day")
        .format("YYYY-MM-DD");
    handleCustomDateSelect(customDate.value);
};

const goToNextDay = () => {
    selectedFilter.value = "custom";
    customDate.value = moment(customDate.value)
        .clone()
        .add(1, "day")
        .format("YYYY-MM-DD");
    handleCustomDateSelect(customDate.value);
};

// Function to fetch data from the server and update the chart
const lastUpdatedTimer = () => {
    lastUpdatedCounter.value++;
};
// Function to fetch data from the server and update the chart
const showFilterPopup = () => {
    showFilters.value = true;
};
const closePopup = () => {
    showFilters.value = false;
};
</script>
<style>
.custom-date-calendar {
    position: absolute;
    z-index: 2;
    right: 0;
}
.p-dropdown-items-wrapper {
    max-height: 250px !important;
}
.button-container {
    display: inline-block;
    margin-right: 10px; /* Add some spacing between the buttons */
}
</style>
