<template>
    <div v-if="props.active" class="loader-wrapper">
        <div class="loader">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
</template>
<script setup>
const props = defineProps(["active"]);
</script>
<style scoped>
.loader-wrapper {
    text-align: center;
}

.loader {
    display: inline-block;
    position: relative;
    width: 80px;
    height: 80px;
}
.loader div {
    box-sizing: border-box;
    display: block;
    position: absolute;
    width: 64px;
    height: 64px;
    margin: 8px;
    border: 5px solid #316fff;
    border-radius: 50%;
    animation: loader 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    border-color: #316fff transparent transparent transparent;
}
.loader div:nth-child(1) {
    animation-delay: -0.45s;
}
.loader div:nth-child(2) {
    animation-delay: -0.3s;
}
.loader div:nth-child(3) {
    animation-delay: -0.15s;
}
@keyframes loader {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
