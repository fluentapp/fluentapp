<template>
    <div class="col-md-6 mt-2">
        <div class="card card-stat bg-white mt-2">
            <div class="card-header bg-white">
                <span class="text-capitalize fw-bold"> External Links </span>
            </div>
            <div class="card-body">
                <Loader :active="loading" style="margin-top: 25%"></Loader>
                <table width="100%" :class="{ 'd-none': loading }">
                    <thead>
                        <tr>
                            <th width="90%" class="text-start">Links </th>
                            <th width="10%" class="text-end">Clicks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="externalLink in externalLinkList">
                            <td class="text-start">
                                <a
                                    class="link-dark link-underline-opacity-0"
                                >
                                    {{ externalLink.link }}
                                </a>
                            </td>
                            <td class="text-end">
                                {{ kFormatter(externalLink.clicks_per_link) }}
                            </td>
                        </tr>
                        <tr v-if="externalLinkList.length === 0">
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
    <ExternalLinksDetails :visible="isShowDetails" @close-popup="closePopup" />
</template>
<script setup>
import { ref, inject, watch, onMounted } from "vue";
import { kFormatter, prepareQueryString } from "./../../helpers";
import config from "./../../config.json";

const filter = inject("filter");
const domain = inject("domain");
const loading = ref(true);
const isShowDetails = ref(false);
const externalLinkList = ref([]);
let cancelTokenSource = axios.CancelToken.source();

// Call the function to fetch and update the widgets initially
onMounted(() => {
    updateExternalLinkWidget();
});
// watch works directly on a ref
watch(filter.value, () => {
    updateExternalLinkWidget();
});
// Function to fetch data from the server and update the  Not Found Widget
const updateExternalLinkWidget = () => {
    // Cancel previous request (if any)
    cancelTokenSource.cancel("Request canceled");
    cancelTokenSource = axios.CancelToken.source();
    loading.value = true;
    axios
        .get(
            prepareQueryString(
                config.baseUrl,
                domain,
                "external-links",
                filter.value,
                { limit: 10 }
            ),
            { cancelToken: cancelTokenSource.token }
        )
        .then((response) => {
            loading.value = false;
            externalLinkList.value = response.data;
        })
        .catch((error) => {
            if (!axios.isCancel(error)) {
                console.log(error);
            }
        });
};

const showDetailsPopup = () => {
    isShowDetails.value = true;
};
const closePopup = () => {
    isShowDetails.value = false;
};
</script>
