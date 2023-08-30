<template>
    <Dialog
        :visible="props.visible"
        :style="{ width: '40vw' }"
        :breakpoints="{ '960px': '75vw', '641px': '90vw' }"
        position="top"
        :modal="true"
        :draggable="false"
        :closable="false"
        @show="prepareNewSite"
    >
        <template #header>
            <div class="container">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>New Site</h4>
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
        <div v-if="!isSaved" class="container">
            <div class="form-group">
                <label for="exampleInputEmail1">Domain</label>
                <div class="p-inputgroup flex-1">
                    <span class="p-inputgroup-addon"> https:// </span>
                    <InputText class="form-control" v-model="domain" />
                </div>
                <small id="emailHelp" class="form-text text-muted">
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
        </div>
        <div v-else class="container">
            <h3>Add JavaScript snippet</h3>
            Paste this snippet in the head of your website.

            <div class="my-5 card p-3 text-sm">
                <a
                    class="position-absolute end-0 pr-2"
                    @click="copyContent"
                    href="#"
                    v-tooltip.top="'Copy'"
                >
                    <span class="pi pi-copy text-end"></span>
                </a>
                <p id="script-to-copy">
                    &lt;script id="fluentanalytics-js" defer data-api="{{
                        config.apiUrl
                    }}" data-domain="{{ domain }}" src="{{
                        baseURL(config.baseUrl)
                    }}fluentanalytics.js'"&gt;&lt;/script&gt;
                </p>
            </div>
        </div>
        <template #footer>
            <Button
                v-if="!isSaved"
                label="Add Site"
                :disabled="!isValidForm"
                :loading="loading"
                @click="saveSite"
                icon="pi pi-check"
            />
            <Button
                v-else
                label="Go to dashboard"
                @click="goToDashboard"
                class="w-full"
            />
        </template>
    </Dialog>
    <Toast />
</template>
<script setup>
import { ref, computed } from "vue";
const props = defineProps(["visible"]);
const emit = defineEmits(["closePopup"]);
import { useToast } from "primevue/usetoast";
import { baseURL } from "./../../helpers.js";
import config from "./../../config.json";

const toast = useToast();
const loading = ref(false);
const isSaved = ref(false);
const domain = ref("");
const timezone = ref();
const timezones = ref([]);

const closePopup = () => {
    emit("closePopup", isSaved.value);
};
const isValidForm = computed(() => {
    return domain.value === "" || typeof timezone.value === "undefined"
        ? false
        : true;
});

const saveSite = () => {
    loading.value = true;
    if (isValidForm) {
        axios
            .post(baseURL(config.baseUrl) + "sites", {
                domain_name: domain.value,
                timezone: timezone.value.timezone,
            })
            .then((response) => {
                loading.value = false;
                isSaved.value = true;
                toast.add({
                    severity: "info",
                    summary: "Info",
                    detail: "Site added successfully",
                    life: 3000,
                });
            })
            .catch((error) => {
                loading.value = false;
                toast.add({
                    severity: "error",
                    summary: "Error Message",
                    detail: error?.response?.data.message,
                    life: 3000,
                });
                console.log(error);
            });
    }
};
const prepareNewSite = () => {
    domain.value = "";
    loading.value = false;
    isSaved.value = false;
    axios
        .get(baseURL(config.baseUrl) + "timezones")
        .then((response) => {
            timezones.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        });
};
const goToDashboard = () => {
    window.location.href = "/home/" + domain.value;
};
const copyContent = () => {
    const source = document.getElementById("script-to-copy");
    const range = document.createRange();
    range.selectNode(source);

    const selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);

    try {
        document.execCommand("copy");
    } catch (err) {
        console.error("Unable to copy HTML: ", err);
    }
};
</script>
<style>
.p-dialog-header {
    padding-bottom: 0.3rem;
}
#script-to-copy {
    font-size: 0.8rem;
}
</style>
