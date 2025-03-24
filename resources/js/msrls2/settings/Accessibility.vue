<template>
    <link href="https://fonts.cdnfonts.com/css/open-dyslexic" rel="stylesheet">
    <link rel="stylesheet"
        href="https://img1.digitallocker.gov.in/ux4g/UX4G-CDN-accessibility/css/accesibility-style-v2.1.css">

    <div class="uwaw uw-light-theme gradient-head uwaw-initial paid_widget" id="uw-main" v-show="isModalOpen">
        <div class="relative second-panel" style="background-color: #348b62;">
            <h3>Accessibility options</h3>
            <div class="uwaw-close" onclick="closeMain()"></div>
        </div>
        <div class="uwaw-body">
            <div class="h-scroll">
                <div class="uwaw-features">
                    <div class="uwaw-features__item reset-feature" id="featureItem_sp">
                        <button id="speak" class="uwaw-features__item__i"
                            data-uw-reader-content="Enable the UserWay screen reader"
                            aria-label="Enable the UserWay screen reader" aria-pressed="false">
                            <span class="uwaw-features__item__icon">
                                <span class="ux4g-icon icon-speaker"> </span>
                            </span>
                            <span class="uwaw-features__item__name">Text To Speech</span>
                            <span class="tick-active uwaw-features__item__enabled reset-tick" id="tickIcon_sp"
                                style="display: none">
                            </span>
                        </button>
                    </div>
                    <div class="uwaw-features__item reset-feature" id="featureItem">
                        <button id="btn-s9" class="uwaw-features__item__i"
                            data-uw-reader-content="Enable the UserWay screen reader"
                            aria-label="Enable the UserWay screen reader" aria-pressed="false">
                            <span class="uwaw-features__item__icon">
                                <span class="ux4g-icon icon-bigger-text"> </span> </span><span
                                class="uwaw-features__item__name">Bigger Text</span>
                            <div class="uwaw-features__item__steps reset-steps" id="featureSteps">
                                <!-- Steps span tags will be dynamically added here -->
                            </div>
                            <span class="tick-active uwaw-features__item__enabled reset-tick" id="tickIcon"
                                style="display: none">
                            </span>
                        </button>
                    </div>
                    <div class="uwaw-features__item reset-feature" id="featureItem-st">
                        <button id="btn-small-text" class="uwaw-features__item__i"
                            data-uw-reader-content="Enable the UserWay screen reader"
                            aria-label="Enable the UserWay screen reader" aria-pressed="false">
                            <span class="uwaw-features__item__icon">
                                <span class="ux4g-icon icon-small-text"> </span> </span><span
                                class="uwaw-features__item__name">Small Text</span>
                            <div class="uwaw-features__item__steps reset-steps" id="featureSteps-st">
                                <!-- Steps span tags will be dynamically added here -->
                            </div>
                            <span class="tick-active uwaw-features__item__enabled reset-tick" id="tickIcon-st"
                                style="display: none">
                            </span>
                        </button>
                    </div>
                    <div class="uwaw-features__item reset-feature" id="featureItem-lh">
                        <button id="btn-s12" class="uwaw-features__item__i"
                            data-uw-reader-content="Enable the UserWay screen reader"
                            aria-label="Enable the UserWay screen reader" aria-pressed="false">
                            <span class="uwaw-features__item__icon">
                                <span class="ux4g-icon icon-line-hight"> </span>
                            </span>
                            <span class="uwaw-features__item__name">Line Height</span>
                            <div class="uwaw-features__item__steps reset-steps" id="featureSteps-lh">
                                <!-- Steps span tags will be dynamically added here -->
                            </div>
                            <span class="tick-active uwaw-features__item__enabled reset-tick" id="tickIcon-lh"
                                style="display: none">
                            </span>
                        </button>
                    </div>
                    <div class="uwaw-features__item reset-feature" id="featureItem-ht">
                        <button id="btn-s10" onclick="highlightLinks()" class="uwaw-features__item__i"
                            data-uw-reader-content="Enable the UserWay screen reader"
                            aria-label="Enable the UserWay screen reader" aria-pressed="false">
                            <span class="uwaw-features__item__icon">
                                <span class="ux4g-icon icon-highlight-links"> </span>
                            </span>
                            <span class="uwaw-features__item__name">Highlight Links</span>
                            <span class="tick-active uwaw-features__item__enabled reset-tick" id="tickIcon-ht"
                                style="display: none">
                            </span>
                        </button>
                    </div>
                    <div class="uwaw-features__item reset-feature" id="featureItem-ts">
                        <button id="btn-s13" onclick="increaseAndReset()" class="uwaw-features__item__i"
                            data-uw-reader-content="Enable the UserWay screen reader"
                            aria-label="Enable the UserWay screen reader" aria-pressed="false">
                            <span class="uwaw-features__item__icon">
                                <span class="ux4g-icon icon-text-spacing"> </span>
                            </span>
                            <span class="uwaw-features__item__name">Text Spacing</span>
                            <div class="uwaw-features__item__steps reset-steps" id="featureSteps-ts">
                                <!-- Steps span tags will be dynamically added here -->
                            </div>
                            <span class="tick-active uwaw-features__item__enabled reset-tick" id="tickIcon-ts"
                                style="display: none">
                            </span>
                        </button>
                    </div>
                    <div class="uwaw-features__item reset-feature" id="featureItem-df">
                        <button id="btn-df" onclick="toggleFontFeature()" class="uwaw-features__item__i"
                            data-uw-reader-content="Enable the UserWay screen reader"
                            aria-label="Enable the UserWay screen reader" aria-pressed="false">
                            <span class="uwaw-features__item__icon">
                                <span class="ux4g-icon icon-dyslexia-font"> </span>
                            </span>
                            <span class="uwaw-features__item__name">Dyslexia Friendly</span>
                            <span class="tick-active uwaw-features__item__enabled reset-tick" id="tickIcon-df"
                                style="display: none">
                            </span>
                        </button>
                    </div>
                    <div class="uwaw-features__item reset-feature" id="featureItem-hi">
                        <button id="btn-s11" onclick="toggleImages()" class="uwaw-features__item__i"
                            data-uw-reader-content="Enable the UserWay screen reader"
                            aria-label="Enable the UserWay screen reader" aria-pressed="false">
                            <span class="uwaw-features__item__icon">
                                <span class="ux4g-icon icon-hide-images"> </span>
                            </span>
                            <span class="uwaw-features__item__name">Hide Images</span>
                            <span class="tick-active uwaw-features__item__enabled reset-tick" id="tickIcon-hi"
                                style="display: none">
                            </span>
                        </button>
                    </div>
                    <div class="uwaw-features__item reset-feature" id="featureItem-Cursor">
                        <button id="btn-cursor" onclick="toggleCursorFeature()" class="uwaw-features__item__i"
                            data-uw-reader-content="Enable the UserWay screen reader"
                            aria-label="Enable the UserWay screen reader" aria-pressed="false">
                            <span class="uwaw-features__item__icon">
                                <span class="ux4g-icon icon-cursor"> </span>
                            </span>
                            <span class="uwaw-features__item__name">Cursor</span>
                            <span class="tick-active uwaw-features__item__enabled reset-tick" id="tickIcon-cursor"
                                style="display: none">
                            </span>
                        </button>
                    </div>
                    <div class="uwaw-features__item reset-feature" id="featureItem-ht-dark">
                        <button id="dark-btn" class="uwaw-features__item__i"
                            data-uw-reader-content="Enable the UserWay screen reader"
                            aria-label="Enable the UserWay screen reader" aria-pressed="false">
                            <span class="uwaw-features__item__name">
                                <span class="light_dark_icon">
                                    <input type="checkbox" class="light_mode uwaw-featugres__item__i" id="checkbox" />
                                    <label for="checkbox" class="checkbox-label">
                                        <i class="fas fa-moon-stars">
                                            <span class="ux4g-icon icon-moon"></span>
                                        </i>
                                        <i class="fas fa-sun">
                                            <span class="ux4g-icon icon-sun"></span>
                                        </i>
                                        <span class="ball"></span>
                                    </label>
                                </span>
                                <span class="uwaw-features__item__name">Light-Dark</span>
                            </span>
                            <span class="tick-active uwaw-features__item__enabled reset-tick" id="tickIcon-ht-dark"
                                style="display: none; pointer-events: none">
                            </span>
                        </button>
                    </div>
                    <!-- Invert Colors Widget -->
                    <div class="uwaw-features__item reset-feature" id="featureItem-ic">
                        <button id="btn-invert" class="uwaw-features__item__i"
                            data-uw-reader-content="Enable the UserWay screen reader"
                            aria-label="Enable the UserWay screen reader" aria-pressed="false">
                            <span class="uwaw-features__item__icon">
                                <span class="ux4g-icon icon-invert"> </span>
                            </span>
                            <span class="uwaw-features__item__name">Invert Colors</span>
                            <span class="tick-active uwaw-features__item__enabled reset-tick" id="tickIcon-ic"
                                style="display: none">
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Reset Button -->
        </div>
        <div class="reset-panel">
            <!-- copyright accessibility bar -->
            <div class="copyrights-accessibility">
                <button class="btn-reset-all" id="reset-all" onclick="resetAll()">
                    <span class="reset-icon"> </span>
                    <span class="reset-btn-text">Reset All Settings</span>
                </button>
                <!-- <a href="https://www.ux4g.gov.in" target="_blank" class="copyright-text" contenteditable="false"
                    style="cursor: pointer;">
                    <span class="uwaw-features__item__name ux4g-copy ux4g-copyright">Created by</span>
                    <img src="https://www.ux4g.gov.in/assets/img/logo/ux4g-logo.svg" alt="logo" loading="lazy">
                </a> -->
            </div>
        </div>
    </div>
    <i id="uw-widget-custom-trigger" class="settings-icon fas fa-cog fa-5x position-relative"
        aria-label="Accessibility Widget" data-uw-trigger="true" aria-haspopup="dialog"
        style="background-color:#014E4E; color:#fff; padding: 10px; border-radius: 5px; cursor: pointer;"
        @click.stop="toggleModal">
    </i>

</template>
<script setup>
import { onMounted, onUnmounted, ref } from "vue";
const isModalOpen = ref(false);

const toggleModal = () => {
    isModalOpen.value = !isModalOpen.value;
};

const closeModal = (event) => {
    const modal = document.getElementById("uw-main");
    const trigger = document.getElementById("uw-widget-custom-trigger");

    // Check if the click is outside the modal and trigger button
    if (modal && !modal.contains(event.target) && trigger !== event.target) {
        isModalOpen.value = false;
    }
};

onMounted(() => {
    // Load external script dynamically
    const script = document.createElement("script");
    script.src = "https://img1.digitallocker.gov.in/ux4g/UX4G-CDN-accessibility/js/weights-v1.js";
    script.async = true;
    document.addEventListener("mouseover", closeModal);
    script.onload = () => {
        console.log("External script loaded successfully.");

        // Once script is loaded, add event listener
        const button = document.getElementById("uw-widget-custom-trigger");
        if (button) {
            button.addEventListener("click", function () {
                if (typeof openMain === "function") {
                    openMain(); // Call function after script is loaded
                } else {
                    console.error("openMain function is not available.");
                }
            });
        } else {
            console.warn("Element with ID 'uw-widget-custom-trigger' not found.");
        }
    };

    script.onerror = () => {
        console.error("Failed to load external script.");
    };

    document.body.appendChild(script);
});
onUnmounted(() => {
    document.removeEventListener("mouseover", closeModal);
});
</script>
