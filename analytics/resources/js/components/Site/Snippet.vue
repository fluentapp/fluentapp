<template>
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
            }}" data-domain="{{ props.domain }}" src="{{
                baseURL(config.baseUrl)
            }}fluentanalytics.js'"&gt;&lt;/script&gt;
        </p>
    </div>
</template>
<script setup>
const props = defineProps(["domain"]);
import { baseURL } from "./../../helpers.js";
import config from "./../../config.json";

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
