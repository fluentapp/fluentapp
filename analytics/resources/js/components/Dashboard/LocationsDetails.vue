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
                        <h4>{{ locations[props.locationCategory].name }}</h4>
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
        <div style="min-height: 70vh">
            <Loader :active="loading" style="margin-top: 25%"></Loader>
            <div class="table-drilldown" :class="{ 'd-none': loading }">
                <DataTable
                    v-if="locationsList.length > 0"
                    :value="locationsList"
                    class="p-datatable-sm"
                >
                    <Column
                        v-for="col of columns"
                        :key="col.field"
                        :field="col.field"
                        :header="col.header"
                    ></Column>
                </DataTable>
                <span v-else>No Data found</span>
            </div>
        </div>
    </Dialog>
</template>
<script setup>
import { ref, inject, watch, computed } from "vue";
import { locations } from "./../../constants";
import { prepareQueryString } from "./../../helpers.js";
import config from "./../../config.json";

const props = defineProps(["visible", "locationCategory"]);
const emit = defineEmits(["closePopup"]);
const filter = inject("filter");
const domain = inject("domain");
const loading = ref(true);
const locationsList = ref([]);
const columns = computed(() => {
    return [
        {
            field: locations[props.locationCategory].field,
            header: locations[props.locationCategory].name.toUpperCase(),
        },
        { field: "unique_visitors_per_location", header: "Visitors" },
    ];
});

watch(props, () => {
    if (props.visible) fetchLocationsDetails();
});
const fetchLocationsDetails = () => {
    loading.value = true;
    axios
        .get(
            prepareQueryString(
                config.baseUrl,
                domain,
                locations[props.locationCategory].url,
                filter.value,
                { with_details: 1, limit: 100 }
            )
        )
        .then((response) => {
            loading.value = false;
            console.log(response.data);
            locationsList.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        });
};

const closePopup = (event) => {
    emit("closePopup");
};
</script>
<style scoped>
.p-dialog-header {
    padding-bottom: 0.3rem;
}
</style>
