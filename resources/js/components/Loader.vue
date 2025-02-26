<!-- Loader.vue -->
<template>
  <div id="preloader">
    <div class="spinner-logo"></div>
    <div class="spinner"></div>
  </div>
</template>

<style scoped>
/* Site Preloader */
.preloader-close {
  position: fixed;
  z-index: 99;
  font-size: 26px;
  background: var(--ed-color-common-white);
  color: #00446E;
  width: 60px;
  height: 60px;
  border: #00446E;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  border-radius: 50%;
  cursor: pointer;
  right: 50px;
  top: 50px;
  -webkit-transition: all 0.3s ease-in-out;
  -moz-transition: all 0.3s ease-in-out;
  -ms-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}

.preloader-close:hover {
  background-color:#00446E;
  color: var(--ed-color-common-white);
}

#preloader {
  position: fixed;
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
  background-color: var(--ed-color-common-white);
  z-index: 999;
  display: flex;
  align-items: center;
  justify-content: center;
}

#preloader .spinner-logo {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.spinner {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 142px;
  height: 142px;
  border-radius: 50%;
  background: radial-gradient(farthest-side, #00446E 94%, rgba(0, 0, 0, 0)) top/15.5px 15.5px no-repeat, conic-gradient(rgba(0, 0, 0, 0) 30%, #00446E);
  -webkit-mask: radial-gradient(farthest-side, rgba(0, 0, 0, 0) calc(100% - 15.5px), #000 0);
  animation: spinner-c7wet2 1.2s infinite linear;
}

@keyframes spinner-c7wet2 {
  100% {
    transform: rotate(1turn);
  }
}
</style>