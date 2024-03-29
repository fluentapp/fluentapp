<template>
    <div class="col-md-6 mt-2">
        <div class="card card-stat bg-white mt-2">
            <div class="card-header bg-white">
                <span class="text-capitalize fw-bold">
                    {{ sources[sourceCategory].name }}
                </span>
                <Dropdown
                    v-model="sourceCategory"
                    :options="Object.values(sources)"
                    optionLabel="name"
                    :placeholder="sources[sourceCategory].name"
                    class="w-full md:w-14rem float-end p-0 m-0 sources-dropdown"
                    @change="setSourceCategory"
                >
                </Dropdown>
            </div>
            <div class="card-body">
                <Loader :active="loading" style="margin-top: 25%"></Loader>
                <table width="100%" :class="{ 'd-none': loading }">
                    <thead>
                        <tr>
                            <th width="90%" class="text-start">
                                {{ sources[sourceCategory].name }}
                            </th>
                            <th width="10%" class="text-end">Visitors</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="source in sourcesList">
                            <td class="text-start">
                                <a
                                    class="link-dark link-underline-opacity-0 link-underline-opacity-75-hover"
                                    href="#"
                                    @click.prevent="
                                        addFilter(
                                            sourceCategory,
                                            source[sourceCategory]
                                        )
                                    "
                                >
                                    {{
                                        source[sourceCategory] ??
                                        "Direct / None"
                                    }}
                                </a>
                            </td>
                            <td class="text-end">
                                {{
                                    kFormatter(
                                        source.unique_visitors_per_source
                                    )
                                }}
                            </td>
                        </tr>
                        <tr v-if="sourcesList.length === 0">
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
    <SourcesDetails :visible="isShowDetails" @close-popup="closePopup" />
</template>
<script setup>
import { ref, inject, watch, onMounted } from "vue";
import {
    kFormatter,
    checkValueInKeys,
    prepareQueryString,
    appendToFilters,
} from "./../../helpers";
import { sources } from "./../../constants";
import config from "./../../config.json";

const filter = inject("filter");
const domain = inject("domain");
const loading = ref(true);
const isShowDetails = ref(false);
const sourcesList = ref([]);
const sourceCategory = ref(Object.keys(sources)[0]);
let cancelTokenSource = axios.CancelToken.source();

// Call the function to fetch and update the widgets initially
onMounted(() => {
    updateSourcesWidget();
});
// watch works directly on a ref
watch(filter.value, () => {
    updateSourcesWidget();
});
// Function to fetch data from the server and update the Sources Widget
const updateSourcesWidget = () => {
    // Cancel previous request (if any)
    cancelTokenSource.cancel("Request canceled");
    cancelTokenSource = axios.CancelToken.source();
    loading.value = true;
    axios
        .get(
            prepareQueryString(
                config.baseUrl,
                domain,
                sources[sourceCategory.value].url,
                filter.value,
                { limit: 10 }
            ),
            { cancelToken: cancelTokenSource.token }
        )
        .then((response) => {
            loading.value = false;
            sourcesList.value = response.data;
        })
        .catch((error) => {
            if (!axios.isCancel(error)) {
                console.log(error);
            }
        });
};

const setSourceCategory = (event) => {
    if (checkValueInKeys(event.value.field, sources)) {
        sourceCategory.value = event.value.field;
        updateSourcesWidget(event.value.field);
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
<style>
.sources-dropdown {
    border: 0;
}
.sources-dropdown .p-inputtext {
    padding: 0;
    text-align: end;
}
.sources-dropdown .p-dropdown-label.p-placeholder {
    color: #327bff;
    text-decoration: underline;
    font-weight: bold;
    font-size: 0.875rem;
}
.p-dropdown:not(.p-disabled).p-focus {
    box-shadow: 0;
    border: 0;
}
</style>
