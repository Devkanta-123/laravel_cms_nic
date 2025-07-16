<template>
  <Loader v-if="isLoading" />

  <head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  </head>
  <div class="app-wrapper">
    <header class="transparent-header">
      <div class="tg-header__top">
        <div class="container custom-container">
          <div class="row">
            <div class="col-lg-6">
              <ul class="tg-header__top-info left-side list-wrap">
                <li>Government Of India</li>
                <li><i class="fa-solid fa-arrow-down"></i><a href="#" @click="scrollToBottom">Skip to main content</a>
                </li>
                <!-- <li><i class="fa-solid fa-eye-slash"></i><a href="#">Screen Reader Access</a></li> -->
              </ul>
            </div>
            <div class="col-lg-6">
              <ul class="tg-header__top-right list-wrap">
                <li>
                  <select v-model="language" @change="handleLanguageChanged" id="language-selector">
                    <option v-for="lang in activatelanguageData" :key="lang.language_id" :value="lang.language_name">
                      {{ lang.language_name }}
                    </option>
                  </select>
                </li>
                <li class="header-btn">
                  <Accessibility></Accessibility>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div id="sticky-header" class="tg-header__area tg-header__area-five">
        <div class="row">
          <div class="col-12">
            <div class="tgmenu__wrap"></div>

            <!-- Mobile Menu -->
            <nav class="tgmenu__nav">
              <!-- Navbar Toggle Button for Mobile -->

              <!-- Hide logo when mobile view is active -->
              <!-- <div v-if="!isMobileView">
                <a href="page/1">
                  <img :src="getLogoUrl()" alt="Logo" class="logo-img" style="margin-left: auto;" /> </a>
              </div> -->

              <!-- Menu Wrapper -->
              <div :class="['collapse', 'navbar-collapse', 'tgmenu__navbar-wrap', 'tgmenu__main-menu']">
                <ul class="navigation">
                  <li v-for="item in sortedMenuItems" :key="item.id" :class="{
                    'menu-item': !item.submenus || !item.submenus.length,
                    'menu-item-has-children': item.submenus && item.submenus.length,
                  }">
                    <!-- <router-link :to="{ name: 'Page', params: { id: item.id } }"
                      :style="{ fontSize: menuFontSize + 'px' }">
                      {{ getMenuItemName(item) }}
                    </router-link> -->
                    <router-link :to="{ name: 'Page', params: { id: item.id }, query: { page_name: item.menu_name } }"
                      :style="{ fontSize: menuFontSize + 'px' }">
                      {{ getMenuItemName(item) }}
                    </router-link>


                    <!-- Submenus -->
                    <ul v-if="item.submenus && item.submenus.length" class="sub-menu">
                      <li v-for="subItem in sortedSubmenus(item)" :key="subItem.id">
                        <router-link
                          :to="{ name: 'Page', params: { id: subItem.id }, query: { page_name: subItem.menu_name } }"
                          :style="{ fontSize: menuFontSize + 'px' }">
                          {{ getMenuItemName(subItem) }}
                        </router-link>

                        <!-- Nested Submenus -->
                        <ul v-if="subItem.submenus && subItem.submenus.length" class="sub-menu">
                          <li v-for="subSubItem in sortedSubmenus(subItem)" :key="subSubItem.id">
                            <router-link
                              :to="{ name: 'Page', params: { id: subSubItem.id }, query: { page_name: subItem.menu_name } }"
                              :style="{ fontSize: menuFontSize + 'px' }">
                              {{ getMenuItemName(subSubItem) }}
                            </router-link>

                            <!-- Further Nested Submenus -->
                            <ul v-if="subSubItem.submenus && subSubItem.submenus.length" class="sub-menu">
                              <li v-for="subSubSubItem in sortedSubmenus(subSubItem)" :key="subSubSubItem.id">
                                <router-link
                                  :to="{ name: 'Page', params: { id: subSubSubItem.id }, query: { page_name: subItem.menu_name } }"
                                  :style="{ fontSize: menuFontSize + 'px' }">
                                  {{ getMenuItemName(subSubSubItem) }}
                                </router-link>
                              </li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>
              <div class="mobile-nav-toggler" @click="toggleMobileMenu">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" fill="none">
                  <path
                    d="M0 2C0 0.895431 0.895431 0 2 0C3.10457 0 4 0.895431 4 2C4 3.10457 3.10457 4 2 4C0.895431 4 0 3.10457 0 2Z"
                    fill="currentcolor" />
                  <path
                    d="M0 9C0 7.89543 0.895431 7 2 7C3.10457 7 4 7.89543 4 9C4 10.1046 3.10457 11 2 11C0.895431 11 0 10.1046 0 9Z"
                    fill="currentcolor" />
                  <path
                    d="M0 16C0 14.8954 0.895431 14 2 14C3.10457 14 4 14.8954 4 16C4 17.1046 3.10457 18 2 18C0.895431 18 0 17.1046 0 16Z"
                    fill="currentcolor" />
                  <path
                    d="M7 2C7 0.895431 7.89543 0 9 0C10.1046 0 11 0.895431 11 2C11 3.10457 10.1046 4 9 4C7.89543 4 7 3.10457 7 2Z"
                    fill="currentcolor" />
                  <path
                    d="M7 9C7 7.89543 7.89543 7 9 7C10.1046 7 11 7.89543 11 9C11 10.1046 10.1046 11 9 11C7.89543 11 7 10.1046 7 9Z"
                    fill="currentcolor" />
                  <path
                    d="M7 16C7 14.8954 7.89543 14 9 14C10.1046 14 11 14.8954 11 16C11 17.1046 10.1046 18 9 18C7.89543 18 7 17.1046 7 16Z"
                    fill="currentcolor" />
                  <path
                    d="M14 2C14 0.895431 14.8954 0 16 0C17.1046 0 18 0.895431 18 2C18 3.10457 17.1046 4 16 4C14.8954 4 14 3.10457 14 2Z"
                    fill="currentcolor" />
                  <path
                    d="M14 9C14 7.89543 14.8954 7 16 7C17.1046 7 18 7.89543 18 9C18 10.1046 17.1046 11 16 11C14.8954 11 14 10.1046 14 9Z"
                    fill="currentcolor" />
                  <path
                    d="M14 16C14 14.8954 14.8954 14 16 14C17.1046 14 18 14.8954 18 16C18 17.1046 17.1046 18 16 18C14.8954 18 14 17.1046 14 16Z"
                    fill="currentcolor" />
                </svg>
              </div>

           <div v-show="isMobileMenuOpen" class="mobile-menu-container">
    <ul class="mobile-menu">
      <li v-for="item in sortedMenuItems" :key="item.id" class="mobile-menu-item">
        <div class="menu-label">
          <router-link class="menu-link" :to="{
            name: 'Page',
            params: { id: item.id },
            query: { page_name: item.menu_name },
          }">
            {{ getMenuItemName(item) }}
          </router-link>
          <button v-if="item.submenus?.length" @click="toggleSubmenu(item.id)" class="submenu-toggle">
            {{ openSubmenus.includes(item.id) ? '-' : '+' }}
          </button>
        </div>

        <!-- Level 1 Submenu -->
        <ul v-if="item.submenus?.length && openSubmenus.includes(item.id)" class="submenu-mobile">
          <li v-for="subItem in sortedSubmenus(item)" :key="subItem.id" class="mobile-submenu-item">
            <div class="menu-label">
              <router-link class="menu-link" :to="{
                name: 'Page',
                params: { id: subItem.id },
                query: { page_name: subItem.menu_name },
              }">
                {{ getMenuItemName(subItem) }}
              </router-link>
              <button v-if="subItem.submenus?.length" @click="toggleSubmenu(subItem.id)" class="submenu-toggle">
                {{ openSubmenus.includes(subItem.id) ? '-' : '+' }}
              </button>
            </div>

            <!-- Level 2 Submenu -->
            <ul v-if="subItem.submenus?.length && openSubmenus.includes(subItem.id)" class="submenu-mobile nested">
              <li v-for="subSubItem in sortedSubmenus(subItem)" :key="subSubItem.id" class="mobile-submenu-item">
                <router-link class="menu-link" :to="{
                  name: 'Page',
                  params: { id: subSubItem.id },
                  query: { page_name: subSubItem.menu_name },
                }">
                  {{ getMenuItemName(subSubItem) }}
                </router-link>
              </li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
  </div>

              <div class="tgmobile__menu-backdrop"></div>
            </nav>
            <!-- End Mobile Menu -->
          </div>
        </div>
      </div>
    </header>
    <br>
    <br>
    <br>
    <div class="p_content-wrapper">
      <div v-if="currentId == '1'" class="content">
        <Carousel @loaded="handleCarouselLoaded" />
      </div>
      <!-- Statistic -->
      <section class="features__area" v-if="currentId == '1'">
        <div class="container-fluid p-0">
          <div class="features__item-wrap">
            <div class="row g-0">
              <div class="col-lg-3 col-md-6">
                <div class="features__item">
                  <div class="features__icon">
                    <i class="fa-solid fa-trophy"></i>
                  </div>
                  <div class="features__content">
                    <h4 class="title contactus"><a href="#">Households Mobilised</a></h4>
                    <h2 class="count"><span class="odometer odometer-auto-theme" data-count="45">
                        <div class="odometer-inside"><span class="odometer-digit"><span
                              class="odometer-digit-spacer">8</span><span class="odometer-digit-inner"><span
                                class="odometer-ribbon"><span class="odometer-ribbon-inner"><span
                                    class="odometer-value">4</span></span></span></span></span></div>
                      </span></h2>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="features__item">
                  <div class="features__icon">
                    <i class="fa-solid fa-users"></i>
                  </div>
                  <div class="features__content">
                    <h4 class="title contactus"><a href="#"> Self Help Groups</a></h4>
                    <h2 class="count"><span class="odometer odometer-auto-theme" data-count="45">
                        <div class="odometer-inside"><span class="odometer-digit"><span
                              class="odometer-digit-spacer">3</span><span class="odometer-digit-inner"><span
                                class="odometer-ribbon"><span class="odometer-ribbon-inner"><span
                                    class="odometer-value">4</span></span></span></span></span></div>
                      </span></h2>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="features__item">
                  <div class="features__icon">
                    <i class="fa-solid fa-user-group"></i>
                  </div>
                  <div class="features__content">
                    <h4 class="title contactus"><a href="#">Village Organisations</a></h4>
                    <h2 class="count"><span class="odometer odometer-auto-theme" data-count="45">
                        <div class="odometer-inside"><span class="odometer-digit"><span
                              class="odometer-digit-spacer">3</span><span class="odometer-digit-inner"><span
                                class="odometer-ribbon"><span class="odometer-ribbon-inner"><span
                                    class="odometer-value">4</span></span></span></span></span></div>
                      </span></h2>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="features__item">
                  <div class="features__icon">
                    <i class="fa-solid fa-project-diagram"></i>
                  </div>
                  <div class="features__content">
                    <h4 class="title contactus"><a href="#"> Cluster Level Federations
                      </a></h4>
                    <h2 class="count"><span class="odometer odometer-auto-theme" data-count="45">
                        <div class="odometer-inside"><span class="odometer-digit"><span
                              class="odometer-digit-spacer">3</span><span class="odometer-digit-inner"><span
                                class="odometer-ribbon"><span class="odometer-ribbon-inner"><span
                                    class="odometer-value">4</span></span></span></span></span></div>
                      </span></h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Latest news -->

      <section class="services-area services-bg position-relative mt-n2" v-if="currentId == '1' && isCarouselLoaded">
        <LatestNews :language="language" />
      </section>
      <section id="about" class="about-area pt-120 pb-120 mt-n9" v-if="currentId == '1'">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="about-img-wrap">
                <div class="shape">
                  <img :src="aboutus" alt="Apexa">
                </div>
                <div class="experience-year">
                  <div class="content">
                    <h6 class="circle rotateme"><span style="transform: rotate(0deg);">Y</span><span
                        style="transform: rotate(17deg);">e</span><span style="transform: rotate(34deg);">a</span><span
                        style="transform: rotate(51deg);">r</span><span style="transform: rotate(68deg);">s</span> <span
                        style="transform: rotate(85deg);">O</span><span style="transform: rotate(102deg);">f</span>
                      <span style="transform: rotate(119deg);">-</span> <span
                        style="transform: rotate(136deg);">E</span><span
                        style="transform: rotate(153deg);">x</span><span
                        style="transform: rotate(170deg);">p</span><span
                        style="transform: rotate(187deg);">e</span><span
                        style="transform: rotate(204deg);">r</span><span
                        style="transform: rotate(221deg);">i</span><span
                        style="transform: rotate(238deg);">e</span><span
                        style="transform: rotate(255deg);">n</span><span
                        style="transform: rotate(272deg);">c</span><span style="transform: rotate(289deg);">e</span>
                      <span style="transform: rotate(306deg);">1</span><span style="transform: rotate(323deg);">5</span>
                      <span style="transform: rotate(340deg);">-</span>
                    </h6>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="about-content">
                <div class="section-title mb-35 tg-heading-subheading animation-style3">
                  <h2 class="title tg-element-title" v-if="homepragraphdata" v-html="homepragraphdata"></h2>
                </div>
                <div class="about-bottom">
                  <router-link :to="{ name: 'Page', params: { id: 3 }, query: { page_name: 'About us' } }" class="btn"
                    data-aos="fade-up" data-aos-delay="600">
                    Read More
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="blog__post-area-two blog__post-bg-two" v-if="currentId == '1'">


      </section>

      <section class="about__area-five mt-n12" v-if="currentId == '1' && isCarouselLoaded">
        <Notifications :language="language" />
      </section>
      <!-- HomeParagraph end here  -->
      <div v-else class="content">
        <router-view></router-view>
        <br>
      </div>


      <section class="project__area-two" v-if="currentId == '1'">
        <Cards :language="language" />
      </section>
      <!-- Home FAQs -->
      <section class="faqs__area-six" v-if="currentId == '1'">
        <div class="circle" data-parallax="{&quot;x&quot; : 100 , &quot;y&quot; : 100 }"
          style="transform:translate3d(26.297px, 26.297px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(26.297px, 26.297px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
        </div>
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-6 mb-30">
              <div class="box-need-help">
                <div class="box-text-need-help">
                  <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M12.2563 21.887H19.8349C20.1467 21.887 20.3998 21.6339 20.3998 21.3221C20.3998 21.0103 20.1467 20.7572 19.8349 20.7572H12.2563C11.9445 20.7572 11.6914 21.0103 11.6914 21.3221C11.6914 21.6339 11.9444 21.887 12.2563 21.887Z"
                      fill="#F7A400"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M17.0264 21.3221V34.9233C17.0264 35.2351 17.2795 35.4882 17.5913 35.4882C17.9027 35.4882 18.1562 35.2351 18.1562 34.9233V21.3221C18.1562 21.0102 17.9027 20.7571 17.5913 20.7571C17.2795 20.7571 17.0264 21.0102 17.0264 21.3221Z"
                      fill="#F7A400"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M4.92772 0.291473C2.72897 0.291473 0.943359 2.07708 0.943359 4.27619C0.943359 6.47529 2.72897 8.26054 4.92772 8.26054C7.12688 8.26054 8.91249 6.47535 8.91249 4.27619C8.91249 2.07702 7.12688 0.291473 4.92772 0.291473ZM4.92772 1.42136C6.50353 1.42136 7.7826 2.70043 7.7826 4.27619C7.7826 5.85165 6.50353 7.13065 4.92772 7.13065C3.35225 7.13065 2.07325 5.85165 2.07325 4.27619C2.07325 2.70037 3.35231 1.42136 4.92772 1.42136Z"
                      fill="#F7A400"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M30.8529 0.291473C28.6538 0.291473 26.8682 2.07708 26.8682 4.27619C26.8682 6.47529 28.6538 8.26054 30.8529 8.26054C33.0517 8.26054 34.8373 6.47535 34.8373 4.27619C34.8373 2.07702 33.0517 0.291473 30.8529 0.291473ZM30.8529 1.42136C32.4284 1.42136 33.7074 2.70043 33.7074 4.27619C33.7074 5.85165 32.4284 7.13065 30.8529 7.13065C29.2771 7.13065 27.9981 5.85165 27.9981 4.27619C27.9981 2.70037 29.2771 1.42136 30.8529 1.42136Z"
                      fill="#F7A400"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M8.04144 16.3672C8.13485 16.5555 8.32845 16.6809 8.54728 16.6809L8.75707 16.6402L13.959 14.5608C14.7597 14.2411 15.6693 14.6309 15.9895 15.4316C16.3092 16.2319 15.9191 17.1415 15.1187 17.4616L8.24863 20.2076C7.59214 20.4701 6.84869 20.2577 6.42799 19.7063C6.28112 19.5138 6.02761 19.4363 5.79791 19.5138C5.56856 19.5914 5.41413 19.8069 5.41413 20.049V22.8862C5.41413 23.1732 5.62918 23.4142 5.91394 23.4474L10.4866 23.9754C11.5672 24.1001 12.3829 25.0153 12.3829 26.1034V34.9233C12.3829 35.2352 12.636 35.4882 12.9478 35.4882C13.2597 35.4882 13.5128 35.2351 13.5128 34.9233V26.1034C13.5128 24.4413 12.2669 23.0436 10.6161 22.8531L6.54396 22.3827V21.2031C7.19555 21.5025 7.96086 21.5394 8.6678 21.257L15.5383 18.511C16.9175 17.9592 17.5898 16.3917 17.0384 15.0121C16.487 13.6324 14.9195 12.9602 13.5399 13.5116L8.78082 15.4139L6.04687 10.9392C5.66085 10.3072 5.03148 9.86242 4.30722 9.70911C4.28501 9.70462 4.2628 9.69972 4.24017 9.69517C3.48124 9.53471 2.68993 9.70947 2.06925 10.1746C1.44856 10.6401 1.05876 11.3504 0.999974 12.124C0.667772 16.4952 0.00153606 25.2556 0.00153606 25.2556C0.000354475 25.2699 0 25.2842 0 25.2986C0 26.9587 1.24326 28.3553 2.89175 28.5481L6.96874 29.0746V34.9233C6.96874 35.2352 7.22184 35.4882 7.53366 35.4882C7.84548 35.4882 8.09857 35.2351 8.09857 34.9233V28.5779C8.09857 28.2935 7.88766 28.054 7.60591 28.0174L3.02609 27.4265C1.9531 27.3027 1.14141 26.3994 1.12977 25.3215C1.12977 25.3185 2.12632 12.2096 2.12632 12.2096C2.16058 11.761 2.38656 11.3486 2.74665 11.0789C3.10709 10.8089 3.56584 10.7075 4.00645 10.8006L4.07309 10.8145C4.49344 10.9035 4.85837 11.1618 5.08246 11.5283C5.93704 12.9271 7.66912 15.7624 8.007 16.3156C8.01846 16.3337 8.02974 16.351 8.04144 16.3672Z"
                      fill="#F7A400"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M3.5565 17.189L5.37188 20.1609C6.0562 21.281 7.44899 21.7443 8.66814 21.2569C8.95775 21.1413 9.09901 20.8121 8.98304 20.5229C8.86742 20.2332 8.53823 20.092 8.24898 20.2076C7.54168 20.4905 6.73342 20.2219 6.33605 19.5719L4.52073 16.5999C4.35802 16.334 4.00999 16.25 3.74413 16.4123C3.47816 16.5751 3.39379 16.923 3.5565 17.189Z"
                      fill="#F7A400"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M26.4758 16.27L21.0056 17.0435C19.5345 17.2514 18.5093 18.6148 18.7172 20.0859C18.9251 21.5567 20.2885 22.5822 21.7596 22.3743L28.4874 21.4226C28.7491 21.3856 29.0007 21.3111 29.2354 21.203V22.3825L25.1632 22.8529C23.5124 23.0435 22.2666 24.4411 22.2666 26.1032V34.9231C22.2666 35.235 22.5197 35.4881 22.8315 35.4881C23.1433 35.4881 23.3964 35.235 23.3964 34.9231V26.1032C23.3964 25.0152 24.2122 24.0999 25.2927 23.9752L29.8639 23.4472H29.8627C30.1456 23.4163 30.3651 23.1768 30.3651 22.886V20.0485C30.3651 19.8063 30.2107 19.5913 29.9814 19.5137C29.752 19.4357 29.4985 19.5134 29.3513 19.7058C29.1039 20.0297 28.7374 20.2462 28.3291 20.3039L21.6013 21.2552C20.7475 21.3761 19.9566 20.7811 19.8361 19.9273C19.7152 19.0738 20.3102 18.2829 21.1637 18.162L26.8964 17.3515L26.8188 17.3567L27.3009 17.0863L30.3576 12.0828C30.7485 11.4433 31.4645 11.0761 32.2121 11.1326C32.2227 11.1333 32.2329 11.134 32.2434 11.1348C33.2505 11.2109 34.0425 12.0267 34.0885 13.0353L34.6493 25.3104C34.6432 26.3928 33.8297 27.3016 32.7529 27.4263L28.2684 27.9442C27.9863 27.9766 27.7724 28.2135 27.7686 28.4975L27.6805 34.9152C27.6763 35.2271 27.926 35.4836 28.2375 35.4881C28.5494 35.4922 28.8059 35.2425 28.8104 34.9307L28.8914 29.0093L32.8825 28.5486C34.5333 28.358 35.7792 26.96 35.7792 25.2983C35.7792 25.2896 35.7788 25.281 35.7784 25.2723C35.7784 25.2723 35.404 17.0754 35.2172 12.9837C35.1449 11.4041 33.905 10.127 32.3285 10.0083C32.3179 10.0075 32.3074 10.0064 32.2972 10.0056C31.1267 9.91748 30.0054 10.4922 29.3934 11.4937L26.4758 16.27Z"
                      fill="#F7A400"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M23.2265 1.98628C23.2265 1.53698 23.048 1.1057 22.7301 0.787857C22.4122 0.469952 21.9814 0.291473 21.5317 0.291473H13.6499C12.7137 0.291473 11.9551 1.0504 11.9551 1.98628V9.16634C11.9551 9.53541 12.1705 9.87063 12.5065 10.0235C12.8424 10.1764 13.2367 10.1188 13.5151 9.87624C14.0585 9.40284 14.8243 8.73542 15.2509 8.36334C15.3541 8.27372 15.4859 8.22439 15.6223 8.22439H21.5316C21.9813 8.22439 22.4122 8.04585 22.7301 7.728C23.0479 7.4101 23.2264 6.97888 23.2264 6.52958V1.98628H23.2265ZM13.085 8.75244V1.98628C13.085 1.67446 13.3377 1.42136 13.6499 1.42136H21.5316C21.6815 1.42136 21.8254 1.48086 21.9312 1.58708C22.0371 1.69289 22.0966 1.83639 22.0966 1.98628V6.52958C22.0966 6.67947 22.0371 6.82297 21.9312 6.92878C21.8254 7.035 21.6815 7.0945 21.5316 7.0945H15.6224C15.2129 7.0945 14.8175 7.24249 14.5087 7.51142L13.085 8.75244Z"
                      fill="#F7A400"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M15.0747 3.79108H20.1938C20.5057 3.79108 20.7587 3.53799 20.7587 3.22617C20.7587 2.91435 20.5056 2.66125 20.1938 2.66125H15.0747C14.7629 2.66125 14.5098 2.91435 14.5098 3.22617C14.5098 3.53799 14.7628 3.79108 15.0747 3.79108Z"
                      fill="#F7A400"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M15.0747 5.8513H17.5917C17.9032 5.8513 18.1566 5.5982 18.1566 5.28638C18.1566 4.9745 17.9032 4.72147 17.5917 4.72147H15.0747C14.7629 4.72147 14.5098 4.97456 14.5098 5.28638C14.5097 5.5982 14.7628 5.8513 15.0747 5.8513Z"
                      fill="#F7A400"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M26.79 21.663L28.4874 21.4227C29.284 21.3101 29.9883 20.8472 30.4078 20.161L32.2232 17.189C32.3859 16.9231 32.3015 16.5751 32.0356 16.4124C31.7697 16.2501 31.4217 16.3341 31.259 16.6L29.4437 19.572C29.2004 19.9701 28.7913 20.2386 28.3292 20.3041L26.6317 20.5441C26.3229 20.5878 26.1075 20.874 26.1511 21.1828C26.1948 21.4912 26.4811 21.7066 26.79 21.663Z"
                      fill="#F7A400"></path>
                  </svg>
                  <h6>Need more help?</h6>
                  <p>Feeling inquisitive? Have a read through some of our FAQs or <a href="#">contact</a> our Supporters
                    for help</p>
                </div>
              </div>
            </div>
            <div class="col-lg-6 mb-30">
              <div class="box-faq-right">
                <h1 class="title tg-element-title mb-20">Frequently asked questions</h1>
                <div class="block-faqs">
                  <div class="accordion" id="accordionFAQ">
                    <div v-for="(faq, index) in faqData" :key="faq.id" class="accordion-item default">
                      <h5 class="accordion-header" :id="'heading' + index">
                        <button class="accordion-button text-heading-5" :class="{ collapsed: activeIndex !== index }"
                          type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse' + index"
                          :aria-expanded="activeIndex === index ? 'true' : 'false'" :aria-controls="'collapse' + index"
                          @click="toggle(index)">
                          {{ faq.english_title_question }}
                        </button>
                      </h5>
                      <div :id="'collapse' + index" class="accordion-collapse collapse"
                        :class="{ show: activeIndex === index }" :aria-labelledby="'heading' + index"
                        data-bs-parent="#accordionFAQ">
                        <div class="accordion-body">
                          {{ faq.english_answer }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <router-link :to="{ name: 'Page', params: { id: 65 }, query: { page_name: 'FAQ' } }" class="btn"
                :style="{ position: 'relative', '--after-display': 'none', '--before-display': 'none' }"
                data-aos="fade-up" data-aos-delay="600">
                See All FAQs
              </router-link>
            </div>
          </div>
        </div>
      </section>
      <!-- FAQ'S end here -->
      <!-- <section class="services__area-seven pt-120 pb-120" v-if="currentId == '1'">
        <FAQS  :language="language"/>
      </section> -->

      <div class="brand__area-two">
        <Logo />
      </div>

      <button class="scroll__top scroll-to-target open" data-target="html" @click="scrollToTop">
        <i class="fas fa-angle-up"></i>
      </button>
      <div class="footer-bottom">
        <Footer>
        </Footer>
        <div style="display: none;">
          <Page :language="language" />
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>

import { ref, onMounted, defineProps, inject, provide, watchEffect, computed, onUnmounted } from 'vue';
import axios from 'axios';
import Footer from './Footer.vue';
import Carousel from '../components/Carousel.vue';
import LatestNews from '../components/LatestNews.vue';
import Loader from '../../components/Loader.vue';
import Notifications from '../components/Notifications.vue';
import Cards from '../components/Cards.vue';
import Banner from '../components/Banner.vue';
import Logo from '../components/Logo.vue';
import Accessibility from '../settings/Accessibility.vue';
import Page from './Page.vue';
import { useRoute } from "vue-router";
import '../assets/css/bootstrap.min.css';
import '../assets/css/animate.min.css';
import '../assets/css/magnific-popup.css';
import '../assets/css/fontawesome-all.min.css';
import '../assets/css/default.css';
import '../assets/css/main.css';
import '../assets/js/swiper-bundle.js';
import '../assets/css/swiper-bundle.css';
import aboutus from '@/assets/images/aboutus.png';
const isCarouselLoaded = ref(false);
const menuItems = ref([]);
const header = ref([]);
const highContrastMode = ref(false);
const language = ref('English');
const isLoading = ref(true);
const route = useRoute();
const menuFontSize = ref(14); // Example font size
const homepragraphdata = ref([]);
const isMobileMenuOpen = ref(false)
const windowWidth = ref(window.innerWidth);
const activatelanguageData = ref();
const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
};
const openSubmenus = ref([]) // ✅ Correct initialization as an empty array

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
}
const initializeMenuItems = (items) => {
  if (!Array.isArray(items)) return []  // Safely handle non-array input

  return items.map(item => ({
    ...item,
    expanded: false,
    submenus: initializeMenuItems(item.submenus) // safely recurse
  }))
}

const toggleSubmenu = (id) => {
  const index = openSubmenus.value.indexOf(id)
  if (index > -1) openSubmenus.value.splice(index, 1)
  else openSubmenus.value.push(id)
}

const sortedMenuItems = computed(() => {
  return menuItems.value.slice().sort((a, b) => a.order - b.order);
});
const faqData = ref([]);

// Recursive sorting function for submenus
const sortedSubmenus = (item) => {
  console.log("Item", item);
  if (!item.submenus) return [];

  return item.submenus
    .slice()
    .sort((a, b) => a.order - b.order)
    .map((subItem) => ({
      ...subItem,
      submenus: sortedSubmenus(subItem) // Apply sorting recursively
    }));
};
const loadingfaqData = ref(false);
const handleLanguageChanged = () => {
  console.log('Language changed to:', language.value);
  fetchMenuItems();
};
const scrollToBottom = () => {
  window.scrollTo({
    top: document.documentElement.scrollHeight,
    behavior: 'smooth', // Ensures smooth scrolling
  });
};
provide('language', language);
const currentId = ref(route.params.id || '1');
watchEffect(() => {
  currentId.value = route.params.id || '1';
  console.log("Updated Page ID:", currentId.value);
});


const props = defineProps({
  id: {
    type: String,
  }
});


const handleCarouselLoaded = () => {
  console.log("Parent received: Carousel is ready");

  isCarouselLoaded.value = true;
};
const fetchMenuItems = async () => {
  try {
    const cacheKey = 'menuItemsCache';
    const cacheExpiryKey = 'menuItemsCacheExpiry';

    // Retrieve cached data and expiry time
    const cachedData = localStorage.getItem(cacheKey);
    const cacheExpiry = localStorage.getItem(cacheExpiryKey);

    // Check if cache exists and is still valid
    if (cachedData && cacheExpiry && Date.now() < parseInt(cacheExpiry)) {
      menuItems.value = JSON.parse(cachedData);
      return;
    }


    // Fetch fresh data from API
    const response = await axios.get('/get_mainmenus');
    menuItems.value = response.data;
    console.log('Menu items:', menuItems.value);
    // Store in local storage with expiration timestamp (10 minutes)
    localStorage.setItem(cacheKey, JSON.stringify(response.data));
    localStorage.setItem(cacheExpiryKey, (Date.now() + 1 * 60 * 1000).toString());

  } catch (error) {
    console.error('Failed to fetch menu items:', error);
  }
};


sortedMenuItems.value = initializeMenuItems(fetchMenuItems)


const fetchPageContent = async () => {
  const response = await axios.get(`/get_page_content/${currentId.value}`);
  if (response.data) {
    homepragraphdata.value = response.data.content
      ? response.data.content
      : response.data.content;
  }
}


const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth',
  });
};

const fetchHeader = async () => {
  try {
    const response = await axios.get('/get_website_description',);
    header.value = response.data;
  } catch (error) {
    console.error('Failed to fetch header:', error);
  }
};

const getMenuItemName = (item) => {

  switch (language.value) {
    case 'English':
      return item.menu_name;
    case 'Hindi':
      return item.hindi_name;
    case 'Khasi':
      return item.khasi_name;
    default:
      return item.menu_name;
  }
};



const resetFontSize = () => {
  menuFontSize.value = 14; // Reset to default font size
};
const toggleContrastMode = () => {
  highContrastMode.value = !highContrastMode.value;
  document.documentElement.classList.toggle(
    "high-contrast",
    highContrastMode.value
  );
};
// Function to update window width
const updateWindowWidth = () => {
  windowWidth.value = window.innerWidth;
};
const fetchFAQs = async () => {
  const cachedData = sessionStorage.getItem('faq');
  const cachedTimestamp = sessionStorage.getItem('faqHomeTimestamp');
  const now = Date.now();
  const cacheDuration = 5 * 60 * 1000; // Example: 5 minutes cache (you can adjust)

  if (cachedData && cachedTimestamp && now - parseInt(cachedTimestamp) < cacheDuration) {
    console.log('Using cached faqData from sessionStorage');
    faqData.value = JSON.parse(cachedData);
    return;
  }

  loadingfaqData.value = true;
  try {
    const response = await axios.get('/get_faq', {
      params: {
        flag: 1
      }
    });
    console.log('Fetched Home faqData:', response.data);
    if (response.data && Array.isArray(response.data)) {
      faqData.value = response.data;

      sessionStorage.setItem('faq', JSON.stringify(response.data));  // <<< corrected here too
      sessionStorage.setItem('faqHomeTimestamp', now.toString());
    } else {
      console.error('Invalid data format');
    }
  } catch (error) {
    console.error('Failed to fetch HomefaqData:', error);
  } finally {
    loadingfaqData.value = false;
  }
};

const getActivateLanguages = async () => {
  try {
    const response = await axios.get('/getActivateLanguages')
    if (response.data) {
      activatelanguageData.value = response.data;
      console.log("Activated Language", response.data);
    }
  }
  catch (error) {
    console.log('Failed to fetch  activate languages', error)
  }
}

onUnmounted(() => {
  window.removeEventListener('resize', updateWindowWidth);
});

// Computed property to check if it's a mobile view
const isMobileView = computed(() => windowWidth.value <= 991);

onMounted(async () => {
  try {
    await Promise.all([fetchMenuItems(), fetchHeader(), fetchPageContent(), fetchFAQs(), getActivateLanguages(), window.addEventListener('resize', updateWindowWidth)]);
  } finally {
    isLoading.value = false;
  }
});
</script>
<style scoped>
/* Remove default bullets and list styles */
ul {
  list-style: none;
  /* No bullets */
  margin: 0;
  /* Reset margin */
  padding: 0;
  /* Reset padding */
}

/* Prevent any content added by ::before or ::after pseudo-elements */
ul::before,
ul::after {
  content: none;
  /* No content */
}

#language-selector {
  font-size: 14px;
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 50px;
  background-color: white;
  width: 150px;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
}

.menu-link {
  font-size: 16px;
  font-weight: 600;
  color: #0c0c55;
  text-decoration: none;
  flex: 1;
  /* ✅ Allows space between text and + icon */
}


.menu-link.router-link-exact-active {
  color: #ffa500;
  /* Highlight active (e.g., "Home") */
}



.brand__area-eight {
  max-height: 150px;
  /* Adjust this value as needed to reduce the width */
  margin: 0 auto;
  /* Center the div within its parent container */
}



.mobile-menu-container {
  padding: 1rem;
  background-color: #ffffff !important; /* Ensure white, override grey */
  width: 100%;
  overflow-y: auto;
}

.mobile-menu,
.submenu {
  list-style: none;
  padding-left: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
}

.mobile-menu-item,
.mobile-submenu-item {
  border-bottom: 1px solid #e0e0e0;
  padding: 0.75rem 0;
}

.menu-label {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.menu-link {
  font-size: 16px;
  font-weight: 600;
  color: #0c0c55;
  text-decoration: none;
  flex: 1;
}

.submenu-toggle {
  background-color: transparent;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 16px;
  width: 28px;
  height: 28px;
  cursor: pointer;
  margin-left: 10px;
}

.submenu {
  padding-left: 1.5rem;
  transition: all 0.3s ease-in-out;
}

.submenu li {
  padding: 0.5rem 0;
}

.font-size-controls {
  list-style: none;
  display: flex;
  gap: 1px;
  padding: 0;
  margin-bottom: 15px;
}

.font-size-controls li a {
  text-decoration: none;
  cursor: pointer;
  font-size: 1rem;
}

.submenu-item {
  padding: 0.4rem 0;
}

.submenu-link {
  color: #333;
  text-decoration: none;
  font-size: 14px;
}





/* Main menu and submenus are vertical stacks */
.mobile-menu,
.submenu-mobile {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
}

/* Indent nested submenus */
.submenu-mobile.nested {
  padding-left: 1.5rem;
}

/* Menu item container */
.mobile-menu-item,
.mobile-submenu-item {
  padding: 0.75rem 0;
  border-bottom: 1px solid #eee;
  display: flex;
  flex-direction: column;
}

/* Row for label + toggle */
.menu-label {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

/* Link style */
.menu-link {
  font-size: 16px;
  font-weight: 600;
  color: #0c0c55;
  text-decoration: none;
  flex: 1;
}

/* Button for +/- toggle */
.submenu-toggle {
  background-color: transparent;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 16px;
  width: 28px;
  height: 28px;
  cursor: pointer;
  margin-left: 10px;
}

/* Responsive handling */
@media (max-width: 768px) {
  .mobile-menu-container {
    padding: 0.5rem;
  }

  .menu-link {
    font-size: 15px;
  }

  .submenu-toggle {
    width: 26px;
    height: 26px;
    font-size: 14px;
  }
}
/* Show submenu when hovering over parent */
.menu-item-has-children:hover>.sub-menu {
  display: block;
}

/* Align nested submenus to the left */
.sub-menu .sub-menu {
  top: 0;
  right: 100%;
  /* Ensures nested menus also align to the left */
}

/* Show deeper nested submenus on hover */
.sub-menu li.menu-item-has-children:hover>.sub-menu {
  display: block;
}

/* Hide submenus by default */
.sub-menu {
  display: none;
  position: absolute;
  top: 0;
  right: 100%;
  /* Move submenu to the left of the parent */
}

/* Default desktop and large screen styles (large screens) */
.tgmenu__nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: nowrap;
  /* Keep the menu in one row on large screens */
}

.tgmenu__navbar-wrap {
  display: flex;
  justify-content: space-between;
  flex-wrap: nowrap;
  width: 100%;
}

.navigation {
  display: flex;
  gap: 3px;
  flex-wrap: nowrap;
  /* Prevent wrapping */
  justify-content: center;
  /* Center the menu items */
}

.navigation li {
  white-space: nowrap;
  position: relative;
  white-space: normal;
  /* Allow wrapping */
  word-wrap: break-word;
  /* Break long words and wrap them to the next line */
  word-break: break-word;
  /* Break long words on boundary */

  /* Prevent text wrapping */
}

/* General Menu Styles */
.tgmenu__navbar-wrap {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0;
  margin: 0;
}



.navigation li a {
  display: inline-block;
  /* Ensure links are inline-block so they respect width */
  max-width: 100%;
  /* Ensure the link does not exceed its container's width */
  overflow: hidden;
  /* Avoid overflow */
  text-overflow: ellipsis;
  /* Optional: add ellipsis if text is too long */
  word-wrap: break-word;
}

/* Responsive Menu Styles */
@media (max-width: 991px) {
  .tgmenu__navbar-wrap {
    flex-direction: column;
    align-items: stretch;
  }


  .navigation li {
    width: 100%;
    text-align: left;
  }



  /* Accordion Style for Submenus */
  .sub-menu {
    list-style: none;
    padding: 0;
    margin: 0;
  }


  .menu-item-has-children a {
    position: relative;
  }

  /* Collapse Submenu Animation */
  .collapse:not(.show) {
    display: none;
  }
}

/* Default Logo Style */
.logo-img {
  height: 100px;
  width: 250px;
  margin-left: 30%;
}

/* Mobile Menu Alignment */
@media (max-width: 991px) {
  .navbar-collapse {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    /* Align menu items to the left */
  }


  .navigation {
    width: 100%;
  }

  .navigation li {
    width: 100%;
    text-align: left;
  }
}

@media (min-width: 992px) {
  .tgmenu__main-menu .navigation {
    display: flex;
    gap: 4px;
    /* Adjust the spacing between menu items */
  }

  .tgmenu__main-menu .navigation li {
    margin: 0 10px;
    /* Reduce left and right margin */
  }

  .tgmenu__main-menu .navigation li a {
    padding: 8px 12px;
    /* Adjust padding inside menu items */
  }
}

.btn::before,
.btn::after {
  content: none !important;
}
</style>