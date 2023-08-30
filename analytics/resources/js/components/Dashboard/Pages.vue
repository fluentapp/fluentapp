<template>
    <div class="col-md-6 mt-2">
        <div class="card card-stat bg-white mt-2">
            <div class="card-header bg-white">
                <span class="text-capitalize fw-bold">
                    {{ pages[pageCategory].name }}
                </span>
                <ul class="list-inline float-end m-0">
                    <li
                        class="list-inline-item"
                        v-for="(value, key) in pages"
                        :key="key"
                    >
                        <Button
                            :label="value.name"
                            size="small"
                            :class="{
                                'fw-bold text-decoration-underline':
                                    pageCategory == key,
                            }"
                            class="p-0"
                            link
                            @click="setPageCategory(key)"
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
                                {{ pages[pageCategory].name }}
                            </th>
                            <th width="10%" class="text-end">Visitors</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="page in pagesList">
                            <td class="text-start">
                                <a
                                    class="link-dark link-underline-opacity-0 link-underline-opacity-75-hover"
                                    href="#"
                                    @click.prevent="
                                        addFilter(
                                            pageCategory,
                                            page[pageCategory]
                                        )
                                    "
                                >
                                    {{ page[pageCategory] }}
                                </a>
                            </td>
                            <td class="text-end">
                                {{ kFormatter(page.unique_visitors_per_page) }}
                            </td>
                        </tr>
                        <tr v-if="pagesList.length === 0">
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
    <PagesDetails
        :visible="isShowDetails"
        @close-popup="closePopup"
        :page-category="pageCategory"
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
import { pages } from "./../../constants";
import config from "./../../config.json";

const filter = inject("filter");
const domain = inject("domain");
const loading = ref(true);
const isShowDetails = ref(false);
const pagesList = ref([]);
const pageCategory = ref(Object.keys(pages)[0]);
let cancelTokenSource = axios.CancelToken.source();

// Call the function to fetch and update the widgets initially
onMounted(() => {
    updatePagesWidget();
});
// watch works directly on a ref
watch(filter.value, () => {
    updatePagesWidget();
});
// Function to fetch data from the server and update the Pages Widget
const updatePagesWidget = () => {
    // Cancel previous request (if any)
    cancelTokenSource.cancel("Request canceled");
    cancelTokenSource = axios.CancelToken.source();
    loading.value = true;
    axios
        .get(
            prepareQueryString(
                config.baseUrl,
                domain,
                pages[pageCategory.value].url,
                filter.value,
                { limit: 10 }
            ),
            { cancelToken: cancelTokenSource.token }
        )
        .then((response) => {
            loading.value = false;
            console.log(response.data);
            pagesList.value = response.data;
        })
        .catch((error) => {
            if (!axios.isCancel(error)) {
                console.log(error);
            }
        });
};

const setPageCategory = (type) => {
    if (checkValueInKeys(type, pages)) {
        pageCategory.value = type;
        updatePagesWidget(type);
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
