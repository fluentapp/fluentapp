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
                    <div><h4>Page Not Found</h4></div>
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
            <div :class="{ 'd-none': loading }">
                <DataTable
                    v-if="notFoundList.length > 0"
                    :value="notFoundList"
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
import { ref, inject, watch } from "vue";
import { prepareQueryString } from "./../../helpers.js";
import config from "./../../config.json";

const props = defineProps(["visible"]);
const emit = defineEmits(["closePopup"]);
const filter = inject("filter");
const domain = inject("domain");
const loading = ref(true);
const notFoundList = ref([]);

const columns = [
    { field: "pages", header: "Pages" },
    { field: "error_per_page", header: "Visitors" },
];
watch(props, () => {
    if (props.visible) fetchSourcesDetails();
});
const fetchSourcesDetails = () => {
    loading.value = true;
    axios
        .get(
            prepareQueryString(
                config.baseUrl,
                domain,
                "not-found",
                filter.value,
                { with_details: 1, limit: 100 }
            )
        )
        .then((response) => {
            loading.value = false;
            console.log(response.data);
            notFoundList.value = response.data;
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
