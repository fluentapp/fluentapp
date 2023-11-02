<template>
    <div class="col-md-6 mt-2">
        <div class="card card-stat bg-white mt-2">
            <div class="card-header bg-white">
                <span class="text-capitalize fw-bold">
                    {{ devices[deviceCategory].name }}
                </span>
                <ul class="list-inline float-end m-0">
                    <li
                        class="list-inline-item"
                        v-for="(value, key) in devices"
                        :key="key"
                    >
                        <Button
                            :label="value.name"
                            size="small"
                            :class="{
                                'fw-bold text-decoration-underline':
                                    deviceCategory == key,
                            }"
                            class="p-0"
                            link
                            @click="setDeviceCategory(key)"
                        />
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <Loader :active="loading" style="margin-top: 25%"></Loader>
                <table width="100%" :class="{ 'd-none': loading }">
                    <thead>
                        <tr>
                            <th width="90%" class="text-start text-capitalize">
                                {{ devices[deviceCategory].name }}
                            </th>
                            <th width="10%" class="text-end">Visitors</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="device in devicesList">
                            <td class="text-start">
                                <a
                                    class="link-dark link-underline-opacity-0 link-underline-opacity-75-hover text-capitalize"
                                    href="#"
                                    @click.prevent="
                                        addFilter(
                                            deviceCategory,
                                            device[deviceCategory]
                                        )
                                    "
                                >
                                    <span
                                        v-if="deviceCategory == 'sizes'"
                                        :class="{
                                            'mx-1': true,
                                            pi: true,
                                            'pi-mobile':
                                                device[deviceCategory] ===
                                                'mobile',
                                            'pi-desktop':
                                                device[deviceCategory] ===
                                                'desktop',
                                            'pi-tablet':
                                                device[deviceCategory] ===
                                                'tablet',
                                        }"
                                    ></span>
                                    {{ device[deviceCategory] }}
                                </a>
                            </td>
                            <td class="text-end">
                                {{
                                    kFormatter(
                                        device.unique_visitors_per_device
                                    )
                                }}
                            </td>
                        </tr>
                        <tr v-if="devicesList.length === 0">
                            <td colspan="2" class="pt-2">No Data found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-center">
                <Button
                    label="More Details"
                    icon="pi pi-window-maximize"
                    @click="showDetailsPopup"
                    text
                />
            </div>
        </div>
    </div>
    <DevicesDetails
        :visible="isShowDetails"
        @close-popup="closePopup"
        :device-category="deviceCategory"
    />
</template>
<script setup>
import { ref, inject, watch, onMounted } from "vue";
import {
    kFormatter,
    checkValueInKeys,
    prepareQueryString,
    appendToFilters,
} from "./../../helpers";
import config from "./../../config.json";

import { devices } from "./../../constants";

const filter = inject("filter");
const domain = inject("domain");
const loading = ref(true);
const isShowDetails = ref(false);
const devicesList = ref([]);
const deviceCategory = ref(Object.keys(devices)[0]);
let cancelTokenSource = axios.CancelToken.source();

// Call the function to fetch and update the widgets initially
onMounted(() => {
    updateDevicesWidget();
});
// watch works directly on a ref
watch(filter.value, () => {
    updateDevicesWidget();
});
// Function to fetch data from the server and update the Devices Widget
const updateDevicesWidget = () => {
    // Cancel previous request (if any)
    cancelTokenSource.cancel("Request canceled");
    cancelTokenSource = axios.CancelToken.source();
    loading.value = true;
    axios
        .get(
            prepareQueryString(
                config.baseUrl,
                domain,
                devices[deviceCategory.value].url,
                filter.value,
                { limit: 10 }
            ),
            { cancelToken: cancelTokenSource.token }
        )
        .then((response) => {
            loading.value = false;
            devicesList.value = response.data;
        })
        .catch((error) => {
            if (!axios.isCancel(error)) {
                console.log(error);
            }
        });
};

const setDeviceCategory = (type) => {
    if (checkValueInKeys(type, devices)) {
        deviceCategory.value = type;
        updateDevicesWidget(type);
    }
};
const addFilter = (filterCategory, filterValue) => {
    const filtersObject = JSON.parse(filter.value.filters);
    filter.value.filters = JSON.stringify(
        appendToFilters(filtersObject, filterCategory, filterValue, true)
    );
};
const showDetailsPopup = () => {
    isShowDetails.value = true;
};
const closePopup = () => {
    isShowDetails.value = false;
};
</script>
