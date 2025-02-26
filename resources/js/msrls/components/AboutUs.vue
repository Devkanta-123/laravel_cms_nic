<template>
    <!-- <section class="course-details pt-60 pb-120  d-flex flex-column justify-content-end">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 col-lg-12 " style="margin-left:-100px;margin-top: -10%;">
                    <div class="course-details-content" style="">
                        <div class="details-inner">
                        </div>
                        <div class="course-details-tab">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel"
                                    aria-labelledby="home-tab">
                                    <div class="tab-overview" style="margin-top: -6%;">
                                        <h2 class="title">About Us</h2>
                                        <p class="mb-30">As per the guidelines of NRLM, the Government of Meghalaya has
                                            formed a state society in the name – Meghalaya State Rural Livelihoods
                                            Society (MSRLS). The society is registered under the Meghalaya Society
                                            Registration Act XII of 1983 and was designated as the Nodal Agency for
                                            implementing NRLM in the state. The basic purpose of forming this society is
                                            to put in place a dedicated and sensitive support structure from the State
                                            level down to the sub-district level which will focus on building strong and
                                            self-managed institution of the poor at different levels. This will provide
                                            the poor a platform for collective action based on self-help and mutual
                                            cooperation, build linkages with mainstream financial institutions and
                                            Government departments to address the multi-faceted dimensions of rural
                                            poverty.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <section class="about-feature pt-120 pb-120" style="margin-top: -10%;">
        <div class="container">
            <div class="section-heading text-center">
                <h2 class="section-title wow fade-in-bottom" data-wow-delay="400ms"
                    style="visibility: visible; animation-delay: 400ms; animation-name: fade-in-bottom;">About Us</h2>
                <p class="mb-30 text-justify">As per the guidelines of NRLM, the Government of Meghalaya has
                    formed a state society in the name – Meghalaya State Rural Livelihoods
                    Society (MSRLS). The society is registered under the Meghalaya Society
                    Registration Act XII of 1983 and was designated as the Nodal Agency for
                    implementing NRLM in the state. The basic purpose of forming this society is
                    to put in place a dedicated and sensitive support structure from the State
                    level down to the sub-district level which will focus on building strong and
                    self-managed institution of the poor at different levels. This will provide
                    the poor a platform for collective action based on self-help and mutual
                    cooperation, build linkages with mainstream financial institutions and
                    Government departments to address the multi-faceted dimensions of rural
                    poverty.
                </p>
            </div>
            <!-- Documentations import -->
           <Documentations></Documentations>
        </div>
    </section>
</template>
<script scope>
import Documentations from "../components/Documentations.vue";

</script>

<style scoped>
.marquee-container {
  height: 150px; /* Set the height of the marquee container */
  overflow: hidden; /* Hide overflowing content */
}

.content-list {
  list-style-type: none; /* Remove default bullet points */
  margin: 0;
  padding: 0;
  animation: move-up 5s linear infinite; /* Apply the bottom-to-top animation */
}

.content-list li {
  font-size: 16px;
  padding: 10px 0;
}

@keyframes move-up {
  0% {
    transform: translateY(100%); /* Start from the bottom */
  }
  100% {
    transform: translateY(-100%); /* Move to the top */
  }
}
</style>