<template>
    <div class="row">
        <!-- Vertical Menu -->
        <div class="col-3">
            <div class="nav flex-column" aria-orientation="vertical">
                <a
                    class="nav-link"
                    href="#"
                    :class="{ active: activeSection === 'snippet-section' }"
                    @click="showSection('snippet-section')"
                >
                    Snippet
                </a>
                <a
                    class="nav-link"
                    href="#"
                    :class="{ active: activeSection === 'edit-domain-section' }"
                    @click="showSection('edit-domain-section')"
                >
                    Edit Domain
                </a>
                <a
                    class="nav-link"
                    href="#"
                    :class="{
                        active: activeSection === 'page-not-found-section',
                    }"
                    @click="showSection('page-not-found-section')"
                >
                    Page Not Found
                </a>
                <a
                    class="nav-link d-none"
                    href="#"
                    :class="{
                        active: activeSection === 'external-links-section',
                    }"
                    @click="showSection('external-links-section')"
                >
                    External Links
                </a>
                <a
                    class="nav-link"
                    href="#"
                    :class="{ active: activeSection === 'delete-section' }"
                    @click="showSection('delete-section')"
                >
                    Delete
                </a>
            </div>
        </div>
        <!-- Content Sections -->
        <div class="col-7">
            <div
                id="snippet-section"
                v-show="activeSection === 'snippet-section'"
                class="card bg-white"
            >
                <div class="card-body">
                    <Snippet :domain="domain" />
                </div>
            </div>
            <div
                id="edit-domain-section"
                v-show="activeSection === 'edit-domain-section'"
                class="card bg-white"
            >
                <div class="card-body">
                    <h3>Edit Domain</h3>
                    <div class="form-group">
                        <label>Domain</label>
                        <div class="p-inputgroup flex-1">
                            <span class="p-inputgroup-addon"> https:// </span>
                            <InputText class="form-control" v-model="domain" />
                        </div>
                        <small class="form-text text-muted">
                            Just the naked domain or subdomain without 'www'
                        </small>
                    </div>
                    <div class="form-group">
                        <label>Timezone</label>
                        <Dropdown
                            v-model="timezone"
                            :options="timezones"
                            filter
                            autoFilterFocus
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
            <div
                id="page-not-found-section"
                v-show="activeSection === 'page-not-found-section'"
                class="card bg-white"
            >
                <div class="card-body">
                    <h3>404 error pages tracking</h3>
                    <div class="form-group mt-5">
                        <div class="form-switch p-0">
                            <label class="form-check-label" for="chk-not-found"
                                >Enable 404 in the dashboard</label
                            >
                            <input
                                class="form-check-input custom-switch mx-5"
                                type="checkbox"
                                role="switch"
                                id="chk-not-found"
                                v-model="pageNotFoundEnabled"
                            />
                        </div>
                    </div>

                    <div class="form-group mt-4" v-if="pageNotFoundEnabled">
                        <label>Page Not Found Titles</label>
                        <Chips
                            class="mx-1 d-block"
                            id="page-not-found-titles"
                            v-model="pageNotFoundTitles"
                            separator=","
                        />
                        <small class="form-text text-muted">
                            Enter the 404 page titles to track separated by
                            commas.
                        </small>
                    </div>
                    <div class="form-group mt-4">
                        <Button
                            label="Save Changes"
                            :loading="loading"
                            @click="savePageNotFoundSettings"
                            icon="pi pi-check"
                        />
                    </div>
                </div>
            </div>

            <div
                id="external-links-section"
                v-show="activeSection === 'external-links-section'"
                class="card bg-white"
            >
                <div class="card-body">
                    <h3>External Links tracking</h3>
                    <div class="form-group mt-5">
                        <div class="form-switch p-0">
                            <label
                                class="form-check-label"
                                for="chk-external-link"
                                >Enable External Links in the dashboard</label
                            >
                            <input
                                class="form-check-input custom-switch mx-5"
                                type="checkbox"
                                role="switch"
                                id="chk-external-link"
                                v-model="externalLinksEnabled"
                            />
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <Button
                            label="Save Changes"
                            :loading="loading"
                            @click="saveExternalLinksSettings"
                            icon="pi pi-check"
                        />
                    </div>
                </div>
            </div>
            <div
                id="delete-section"
                v-show="activeSection === 'delete-section'"
                class="card bg-white"
            >
                <div class="card-body">
                    <h3>Delete Site</h3>
                    <small class="form-text text-muted">
                        Permanently remove all stats and the site configuration
                        too
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
const activeSection = ref("snippet-section"); // Active section state

const domain = ref(props.domain);
const timezone = ref(props.timezone);
const loading = ref(false);
const isValidForm = computed(() => {
    return domain === "" || typeof timezone === "undefined" ? false : true;
});
const externalLinksEnabled = ref(false);
const pageNotFoundEnabled = ref(false);
const pageNotFoundTitles = ref([]);
onMounted(() => {
    loadTimezones();
    loadSettings();
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
                    severity: "success",
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
                severity: "success",
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
const loadSettings = () => {
    axios
        .get(
            prepareQueryString(config.baseUrl + "sites/settings/", domain.value)
        )
        .then((response) => {
            pageNotFoundTitles.value = response.data.page_not_found_titles;
            pageNotFoundEnabled.value = response.data.page_not_found_enabled;
            externalLinksEnabled.value =
                response.data.external_tracking_enabled;
        })
        .catch((error) => {
            console.error(error);
        });
};
const savePageNotFoundSettings = () => {
    if (pageNotFoundEnabled.value && pageNotFoundTitles.value.length === 0) {
        toast.add({
            severity: "info",
            detail: "Oops! It seems like you forgot to add a title for the Page Not Found message. Please fill it in to continue. ",
            life: 3500,
        });
        return;
    }
    loading.value = true;
    axios
        .put(
            prepareQueryString(
                config.baseUrl + "sites/settings/",
                domain.value,
                "update-page-not-found-settings"
            ),
            {
                page_not_found_enabled: pageNotFoundEnabled.value,
                page_not_found_titles: pageNotFoundTitles.value,
            }
        )
        .then((response) => {
            loading.value = false;
            toast.add({
                severity: "success",
                detail: response.data.message,
                life: 1500,
            });
        })
        .catch((error) => {
            loading.value = false;
            toast.add({
                severity: "error",
                summary: "Error Message",
                detail: "Failed to update page not found titles",
                life: 1500,
            });
            console.error(error);
        });
};

const saveExternalLinksSettings = () => {
    loading.value = true;
    axios
        .put(
            prepareQueryString(
                config.baseUrl + "sites/settings/",
                domain.value,
                "update-external-links-settings"
            ),
            {
                external_links_enabled: externalLinksEnabled.value,
            }
        )
        .then((response) => {
            loading.value = false;
            toast.add({
                severity: "success",
                detail: response.data.message,
                life: 1500,
            });
        })
        .catch((error) => {
            loading.value = false;
            toast.add({
                severity: "error",
                summary: "Error Message",
                detail: "Failed to update page not found titles",
                life: 1500,
            });
            console.error(error);
        });
};

const showSection = (sectionId) => {
    activeSection.value = sectionId;
};
</script>

<style scoped>
.custom-switch {
    transform: scale(2);
}
.nav-link.active {
    background-color: #007bff; /* Add your preferred active link styling */
    color: #fff; /* Add your preferred active link styling */
}
</style>
