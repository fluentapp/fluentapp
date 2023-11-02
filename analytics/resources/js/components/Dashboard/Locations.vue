<template>
    <div class="col-md-6 mt-2">
        <div class="card card-stat bg-white mt-2">
            <div class="card-header bg-white">
                <span class="text-capitalize fw-bold">
                    {{ locationCategory }}
                </span>
                <ul class="list-inline float-end m-0">
                    <li
                        class="list-inline-item"
                        v-for="(value, key) in locations"
                        :key="key"
                    >
                        <Button
                            :label="value.name"
                            size="small"
                            :class="{
                                'fw-bold text-decoration-underline':
                                    locationCategory == key,
                            }"
                            class="p-0"
                            link
                            @click="setLocationCategory(key)"
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
                                {{ locations[locationCategory].name }}
                            </th>
                            <th width="10%" class="text-end">Visitors</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="location in locationsList">
                            <td class="text-start">
                                <a
                                    class="link-dark link-underline-opacity-0 link-underline-opacity-75-hover"
                                    href="#"
                                    @click.prevent="
                                        addFilter(
                                            locationCategory,
                                            location[locationCategory]
                                        )
                                    "
                                >
                                    <span
                                        :class="
                                            'fi fi-' +
                                            (location.country_code
                                                ? location.country_code.toLowerCase()
                                                : 'xx')
                                        "
                                    ></span>
                                    {{ location[locationCategory] }}
                                </a>
                            </td>
                            <td class="text-end">
                                {{
                                    kFormatter(
                                        location.unique_visitors_per_location
                                    )
                                }}
                            </td>
                        </tr>
                        <tr v-if="locationsList.length === 0">
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
    <LocationsDetails
        :visible="isShowDetails"
        @close-popup="closePopup"
        :location-category="locationCategory"
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
import { locations } from "./../../constants";
import config from "./../../config.json";

const filter = inject("filter");
const domain = inject("domain");
const loading = ref(true);
const isShowDetails = ref(false);
const locationsList = ref([]);
const locationCategory = ref(Object.keys(locations)[0]);
let cancelTokenSource = axios.CancelToken.source();

// Call the function to fetch and update the widgets initially
onMounted(() => {
    updateLocationsWidget();
});
// watch works directly on a ref
watch(filter.value, () => {
    updateLocationsWidget();
});
// Function to fetch data from the server and update the Locations Widget
const updateLocationsWidget = () => {
    // Cancel previous request (if any)
    cancelTokenSource.cancel("Request canceled");
    cancelTokenSource = axios.CancelToken.source();
    loading.value = true;
    axios
        .get(
            prepareQueryString(
                config.baseUrl,
                domain,
                locations[locationCategory.value].url,
                filter.value,
                { limit: 10 }
            ),
            { cancelToken: cancelTokenSource.token }
        )
        .then((response) => {
            loading.value = false;
            locationsList.value = response.data;
        })
        .catch((error) => {
            if (!axios.isCancel(error)) {
                console.log(error);
            }
        });
};

const setLocationCategory = (type) => {
    if (checkValueInKeys(type, locations)) {
        locationCategory.value = type;
        updateLocationsWidget(type);
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
