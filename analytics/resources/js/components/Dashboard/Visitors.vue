<template>
    <div class="col-md-12">
        <div class="card bg-white" style="height: 32rem">
            <div class="card-body">
                <MainStat></MainStat>
                <Loader :active="loading" style="margin-top: 15%"></Loader>
                <canvas
                    :class="{ 'd-none': loading }"
                    ref="chartVisitors"
                    width="400"
                    height="130"
                ></canvas>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, inject, watch, onMounted } from "vue";
import { prepareQueryString } from "./../../helpers.js";
import config from "./../../config.json";

import Chart from "chart.js/auto";
const chartVisitors = ref(null);
const loading = ref(true);
const filter = inject("filter");
const domain = inject("domain");
let chartInstance = null;
const documentStyle = getComputedStyle(document.documentElement);
let cancelTokenSource = axios.CancelToken.source();

const chartData = ref({
    labels: [],
    datasets: [
        {
            label: "Visitors",
            data: [],
            backgroundColor: documentStyle.getPropertyValue("--blue-100"),
            borderColor: documentStyle.getPropertyValue("--blue-500"),
            fill: true,
        },
    ],
});
const chartOptions = ref({
    elements: {
        point: {
            radius: 1.5,
        },
    },
    animation: true,
    plugins: {
        legend: {
            display: false,
        },
    },
    scales: {
        x: {
            grid: {
                display: false,
            },
        },
        y: {
            beginAtZero: true,
            ticks: {
                stepSize: 1,
            },
        },
    },
});

// Call the function to fetch and update the chart initially
onMounted(() => {
    fetchAndUpdateChart();
});
// watch works directly on a ref
watch(filter.value, () => {
    if (filter.value.period === "real_time") {
        chartOptions.value.animation = false;
    }
    fetchAndUpdateChart();
});

// Function to fetch data from the server and update the chart
const fetchAndUpdateChart = () => {
    // Cancel previous request (if any)
    cancelTokenSource.cancel("Request canceled");
    cancelTokenSource = axios.CancelToken.source();
    loading.value = true;
    axios
        .get(
            prepareQueryString(
                config.baseUrl,
                domain,
                "visitors",
                filter.value
            ),
            { cancelToken: cancelTokenSource.token }
        )
        .then((response) => {
            chartData.value.datasets[0].data = response.data.plot;
            chartData.value.labels = response.data.labels;
            destroyAndRedrawChart();
        })
        .catch((error) => {
            if (!axios.isCancel(error)) {
                console.log(error);
            }
        });
};

// Function to destroy the existing chart instance and redraw with new data
const destroyAndRedrawChart = () => {
    loading.value = false;

    if (chartInstance) {
        chartInstance.destroy();
    }
    const ctx = chartVisitors.value.getContext("2d");
    chartInstance = new Chart(ctx, {
        type: "line",
        data: chartData.value,
        options: chartOptions.value,
    });
};
</script>

<style scoped>
.card-body {
    min-height: 20rem;
}
</style>
