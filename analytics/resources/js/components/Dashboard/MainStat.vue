<template>
    <div class="d-flex flex-row flex-wrap">
        <SingleCompStat
            v-for="(value, key) in metrics"
            :key="key"
            :metric="key"
            :name="value.name"
            :value="mainStat[key]"
            :type="value.type"
            :loading="loading"
            comparison-value="20739"
            comparison-change="1"
        />
    </div>
</template>
<script setup>
import { ref, inject, watch, onMounted } from "vue";
import { metrics } from "./../../constants";
import { prepareQueryString } from "./../../helpers.js";
import config from "./../../config.json";

const filter = inject("filter");
const domain = inject("domain");
const loading = ref(true);
const mainStat = ref({
    unique_visitors: 0,
    total_visits: 0,
    total_pageviews: 0,
    views_per_visit: 0,
    bounce_rate: 0,
    visit_duration: 0,
});
let cancelTokenSource = axios.CancelToken.source();

// Call the function to update the widgets initially
onMounted(() => {
    updateStats();
});
// watch works directly on a ref
watch(filter.value, () => {
    updateStats();
});
// Function to fetch data from the server and update the to stat
const updateStats = () => {
    // Cancel previous request (if any)
    cancelTokenSource.cancel("Request canceled");
    cancelTokenSource = axios.CancelToken.source();
    loading.value = true;
    axios
        .get(
            prepareQueryString(
                config.baseUrl,
                domain,
                "main-stats",
                filter.value
            ),
            { cancelToken: cancelTokenSource.token }
        )
        .then((response) => {
            loading.value = false;
            mainStat.value.unique_visitors =
                response.data.total_unique_visitors;
            mainStat.value.total_pageviews = response.data.total_page_views;
            mainStat.value.total_visits = response.data.total_visits_count;
            mainStat.value.bounce_rate = response.data.bounce_rate;
            mainStat.value.views_per_visit =
                response.data.average_views_per_visit;
            mainStat.value.visit_duration = response.data.visit_duration;
        })
        .catch((error) => {
            if (!axios.isCancel(error)) {
                console.log(error);
            }
        });
};
</script>
