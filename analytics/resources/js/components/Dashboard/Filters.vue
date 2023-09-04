<template>
    <Dialog
        :visible="props.visible"
        :style="{ width: '40vw' }"
        :breakpoints="{ '960px': '75vw', '641px': '90vw' }"
        position="top"
        :modal="true"
        :draggable="false"
        :closable="false"
    >
        <template #header>
            <div class="container">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>Filter by</h4>
                    </div>
                    <div>
                        <Button
                            icon="pi pi-times"
                            class="p-button-rounded p-button-text"
                            @click="closePopup"
                            link
                            size="small"
                        />
                    </div>
                </div>
            </div>
        </template>

        <TabView>
            <TabPanel
                v-for="(tab, index) in tabs"
                :key="index"
                :header="tab.header"
            >
                <div v-for="(value, key) in tab.items" :key="key">
                    <h5 class="m-0">{{ value.name }}</h5>
                    <div class="row g-3 align-items-center mx-5 mb-4 mt-2">
                        <div class="col-auto">
                            <Dropdown
                                v-model="selectedOperators[tab.type][key]"
                                :options="operatorItems"
                                optionLabel="name"
                                optionValue="code"
                                placeholder="is"
                                class="w-full md:w-7rem"
                            />
                            <MultiSelect
                                class="w-15rem mt-md-1"
                                display="chip"
                                v-model="selectedItems[tab.type][key]"
                                :options="optionItems[tab.type][key]"
                                :optionLabel="value.field"
                                :optionValue="value.field"
                                :filterPlaceholder="'Search' + value.name"
                                :placeholder="'Select ' + value.name"
                                @click="loadOptions(tab.type, key)"
                                @change="handleSelection(tab.type, key)"
                                :loading="loadingStates[`${tab.type}_${key}`]"
                                filter
                                autoFilterFocus
                            >
                            </MultiSelect>
                        </div>
                    </div>
                </div>
            </TabPanel>
        </TabView>
        <template #footer>
            <Button
                label="Apply Filter"
                @click="applyFilter"
                icon="pi pi-check"
            />
            <Button
                v-if="JSON.parse(filter.filters).length > 0"
                label="Remove Filters"
                @click="removeFilters"
                icon="pi pi-trash"
                severity="danger"
            />
        </template>
    </Dialog>
</template>

<script setup>
import { ref, inject, watch } from "vue";
import { sources, pages, devices, locations } from "./../../constants";
import { prepareQueryString } from "./../../helpers.js";
import config from "./../../config.json";

const domain = inject("domain");
const filter = inject("filter");
const props = defineProps(["visible"]);
const emit = defineEmits(["closePopup"]);
const loadingStates = ref({});

const tabs = [
    { header: "Source", items: sources, type: "sources" },
    { header: "Page", items: pages, type: "pages" },
    { header: "Location", items: locations, type: "locations" },
    { header: "Device", items: devices, type: "devices" },
];
const operatorItems = ref([
    { name: "is", code: "is" },
    { name: "is not", code: "is_not" },
]);
const selectedItems = ref({
    pages: Array.from({ length: pages.length }, () => []),
    sources: Array.from({ length: sources.length }, () => []),
    devices: Array.from({ length: devices.length }, () => []),
    locations: Array.from({ length: locations.length }, () => []),
});

const optionItems = ref({
    pages: Array.from({ length: pages.length }, () => []),
    sources: Array.from({ length: sources.length }, () => []),
    devices: Array.from({ length: devices.length }, () => []),
    locations: Array.from({ length: locations.length }, () => []),
});
const selectedOperators = ref({
    pages: Array.from({ length: pages.length }, () => "is"),
    sources: Array.from({ length: sources.length }, () => "is"),
    devices: Array.from({ length: devices.length }, () => "is"),
    locations: Array.from({ length: locations.length }, () => "is"),
});
const loadOptions = (type, index) => {
    loadingStates.value[`${type}_${index}`] = true;

    const item =
        type === "pages"
            ? pages[index]
            : type === "sources"
            ? sources[index]
            : type === "devices"
            ? devices[index]
            : locations[index];

    axios
        .get(
            prepareQueryString(config.baseUrl, domain, item.url, {
                period: filter.value.period,
            })

            //date_filter=" +
            //     filter.value.period
            // + "&filters=" +  // if we apply the filter to the options, the list is getting short
        )
        .then((response) => {
            optionItems.value[type][index] = response.data;
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            loadingStates.value[`${type}_${index}`] = false;
        });
};

watch(props, () => {
    if (props.visible) renderFilters();
});

const handleSelection = (type, index) => {
    // console.log(selectedItems.value[type][index]);
};
const applyFilter = () => {
    const selectedData = formatSelectedItems();
    const jsonData = JSON.stringify(selectedData);
    filter.value.filters = jsonData;
    closePopup();
};
const formatSelectedItems = () => {
    const result = [];
    for (const tab of tabs) {
        const selectedValues = selectedItems.value[tab.type];
        const selectedOper = selectedOperators.value[tab.type];
        for (const key in selectedValues) {
            if (selectedValues[key].length > 0)
                result.push({
                    key,
                    value: selectedValues[key],
                    operator: selectedOper[key],
                });
        }
    }
    return result;
};
const removeFilters = () => {
    for (const tab of tabs) {
        selectedItems.value[tab.type] = Array.from(
            { length: tab.items.length },
            () => []
        );
    }
    for (const type in optionItems.value) {
        optionItems.value[type] = Array.from(
            { length: optionItems.value[type].length },
            () => []
        );
    }
    filter.value.filters = JSON.stringify([]);
    closePopup();
};

const renderFilters = () => {
    const parsedFilterObject = JSON.parse(filter.value.filters);
    for (const tab of tabs) {
        const tabItems = tab.items;
        for (const key in tabItems) {
            const foundObject = parsedFilterObject.find(
                (obj) => obj.key === key
            );
            if (foundObject) {
                const valueForKey = foundObject.value;
                optionItems.value[tab.type][key] = valueForKey.map(
                    (valueForKey) => ({ [key]: valueForKey })
                );
                selectedItems.value[tab.type][key] = valueForKey;
            }
        }
    }
};
const closePopup = () => {
    emit("closePopup");
};
</script>
