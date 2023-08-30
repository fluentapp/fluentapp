<template>
    <div class="row">
        <div class="col-md-12">
            <div class="row mb-2">
                <div class="col-md-3">
                    <h5>My Websites</h5>
                </div>
                <div class="col-md-4 offset-md-5 float-end">
                    <Button
                        label="Add Site"
                        class="float-end"
                        @click="addNewSitePopup"
                    />
                </div>
            </div>
        </div>
        <Loader :active="loading"></Loader>

        <DataView :value="sites" layout="grid" :class="{ 'd-none': loading }">
            <template #grid="slotProps">
                <div class="col-12 sm:col-6 lg:col-12 xl:col-4 p-2">
                    <div
                        class="p-4 border-1 surface-border surface-card border-round"
                    >
                        <div
                            class="flex align-items-center justify-content-between"
                        >
                            <a
                                :href="'/home/' + slotProps.data.fqdn"
                                class="text-xl font-semibold"
                                style="text-decoration: none; color: #000"
                            >
                                <i class="pi pi-link text-xl"></i>
                                {{ slotProps.data.fqdn }}
                            </a>
                            <Button
                                icon="pi pi-wrench"
                                rounded
                                @click="gotoSiteManagment(slotProps.data.fqdn)"
                            ></Button>
                        </div>
                    </div>
                </div>
            </template>
        </DataView>
        <NewSite :visible="showPopup" @close-popup="closePopup" />
    </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import { baseURL } from "./../helpers.js";
import config from "./../config.json";

const sites = ref();
const showPopup = ref(false);
const loading = ref(true);

onMounted(() => {
    fetchSites();
});
// Function to fetch data from the server and update the Sources Widget
const fetchSites = () => {
    loading.value = true;
    axios
        .get(baseURL(config.baseUrl, "sites"))
        .then((response) => {
            loading.value = false;
            sites.value = response.data;
            if (sites.value.length == 0) {
                addNewSitePopup();
            }
        })
        .catch((error) => {
            console.log(error);
        });
};

const addNewSitePopup = () => {
    showPopup.value = true;
};
const closePopup = (refreshSites = false) => {
    showPopup.value = false;
    if (refreshSites) fetchSites();
};
const gotoSiteManagment = (dom) => {
    window.location.href = "/manage-site/" + dom;
};
</script>
