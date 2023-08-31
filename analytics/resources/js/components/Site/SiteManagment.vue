<template>
    <div class="col-8 offset-2">
        <div class="card bg-white mb-5">
            <div class="card-body">
                <Snippet :domain="domain" />
            </div>
        </div>
        <div class="card bg-white my-5">
            <div class="card-body">
                <h3>Edit Domain</h3>
                <div class="form-group">
                    <label for="exampleInputEmail1">Domain</label>
                    <div class="p-inputgroup flex-1">
                        <span class="p-inputgroup-addon"> https:// </span>
                        <InputText class="form-control" v-model="domain" />
                    </div>
                    <small class="form-text text-muted">
                        Just the naked domain or subdomain without 'www'
                    </small>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Timezone</label>
                    <Dropdown
                        v-model="timezone"
                        :options="timezones"
                        filter
                        optionLabel="timezoneFormat"
                        placeholder="Select a Timezone"
                        class="w-full"
                    />
                </div>
                <div class="form-group mt-5">
                    <Button
                        label="Edit Site"
                        :disabled="!isValidForm"
                        :loading="loading"
                        @click="editSite"
                        icon="pi pi-check"
                    />
                </div>
            </div>
        </div>
        <div class="card bg-white my-5">
            <div class="card-body">
                <h3>Delete Site</h3>
                <small class="form-text text-muted">
                    Permanently remove all stats and the site configuration too
                </small>
                <div class="form-group mt-5">
                    <Button
                        @click="confirmDeleteSite($event)"
                        icon="pi pi-trash"
                        label="Delete Site"
                        severity="danger"
                    ></Button>
                </div>
            </div>
        </div>
    </div>
    <ConfirmPopup></ConfirmPopup>
    <Toast />
</template>
<script setup>
import { ref, computed, onMounted } from "vue";
import { baseURL, prepareQueryString } from "./../../helpers.js";
import config from "./../../config.json";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

const props = defineProps(["domain", "timezone"]);
const confirm = useConfirm();
const toast = useToast();

const timezones = ref([]);

const domain = ref(props.domain);
const timezone = ref(props.timezone);
const loading = ref(false);
const isValidForm = computed(() => {
    return domain === "" || typeof timezone === "undefined" ? false : true;
});

onMounted(() => {
    loadTimezones();
});

const loadTimezones = () => {
    axios
        .get(baseURL(config.baseUrl, "timezones"))
        .then((response) => {
            timezones.value = response.data;
            const matchingTimezone = timezones.value.find(
                (tz) => tz.timezone === props.timezone
            );
            if (matchingTimezone) {
                timezone.value = matchingTimezone;
            }
        })
        .catch((error) => {
            console.log(error);
        });
};
const editSite = () => {
    loading.value = true;
    if (isValidForm) {
        axios
            .put(prepareQueryString(config.baseUrl, "sites", props.domain), {
                domain_name: domain.value,
                timezone: timezone.value.timezone,
            })
            .then((response) => {
                loading.value = false;
                toast.add({
                    severity: "info",
                    summary: "Info",
                    detail: "Site updated successfully",
                    life: 1500,
                });
                setTimeout(redirectToMainPage, 1500);
            })
            .catch((error) => {
                loading.value = false;
                toast.add({
                    severity: "error",
                    summary: "Error Message",
                    detail: error?.response?.data.message,
                    life: 1500,
                });
                console.log(error);
            });
    }
};
const confirmDeleteSite = (event) => {
    confirm.require({
        target: event.currentTarget,
        message: "Do you want to delete this site?",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        accept: () => {
            deleteSite();
        },
        reject: () => {},
    });
};
const deleteSite = () => {
    axios
        .delete(prepareQueryString(config.baseUrl, "sites", props.domain))
        .then((response) => {
            loading.value = false;
            toast.add({
                severity: "info",
                summary: "Info",
                detail: "Site deleted successfully",
                life: 1500,
            });
            setTimeout(redirectToMainPage, 1500);
        })
        .catch((error) => {
            loading.value = false;
            toast.add({
                severity: "error",
                summary: "Error Message",
                detail: error?.response?.data.message,
                life: 1500,
            });
            console.log(error);
        });
};
const redirectToMainPage = () => {
    window.location.href = "/manage-sites/";
};
</script>
