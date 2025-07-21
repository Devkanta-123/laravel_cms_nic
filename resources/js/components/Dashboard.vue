<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Admin Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fa fa-layer-group highlight-icon"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Cards</span>
                            <span class="info-box-number">Approved : {{ dashboardData.cards.approved }}, Pending: {{ dashboardData.cards.pending }}, Rejected  : {{dashboardData.cards.rejected }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-sliders-h highlight-icon "></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Carousel</span>
                            <span class="info-box-number">Approved : {{ dashboardData.carousel.approved }}, Pending: {{ dashboardData.carousel.pending }}, Rejected  : {{dashboardData.carousel.rejected }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fa fa-question-circle highlight-icon"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">FAQ</span>                            
                            <span class="info-box-number">Approved : {{ dashboardData.faqs.approved }}, Pending: {{ dashboardData.faqs.pending }}, Rejected  : {{dashboardData.faqs.rejected }}</span>

                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fa fa-image highlight-icon"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Gallery</span>
                            <span class="info-box-number">Approved : {{ dashboardData.gallery.approved }}, Pending: {{ dashboardData.gallery.pending }}, Rejected  : {{dashboardData.gallery.rejected }}</span>
                        </div>
                    </div>
                </div>
            </div>

             <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fa fa-newspaper highlight-icon"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Lates News</span>
                            <span class="info-box-number">Approved : {{ dashboardData.latest_news.approved }}, Pending: {{ dashboardData.latest_news.pending }}, Rejected  : {{dashboardData.latest_news.rejected }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fa fa-globe highlight-icon"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Logo</span>
                            <span class="info-box-number">Approved : {{ dashboardData.logo.approved }}, Pending: {{ dashboardData.logo.pending }}, Rejected  : {{dashboardData.logo.rejected }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fa fa-map-marker highlight-icon"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Map</span>                            
                            <span class="info-box-number">Approved : {{ dashboardData.map.approved }}, Pending: {{ dashboardData.map.pending }}, Rejected  : {{dashboardData.map.rejected }}</span>

                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fa fa-clipboard highlight-icon"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Notice Board</span>
                            <span class="info-box-number">Approved : {{ dashboardData.notice_board.approved }}, Pending: {{ dashboardData.notice_board.pending }}, Rejected  : {{dashboardData.notice_board.rejected }}</span>
                        </div>
                    </div>
                </div>
            </div>

             <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fa fa-file highlight-icon"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Paragraph</span>
                            <span class="info-box-number">Approved : {{ dashboardData.paragraph.approved }}, Pending: {{ dashboardData.paragraph.pending }}, Rejected  : {{dashboardData.paragraph.rejected }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fa fa-users highlight-icon"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Whos Who</span>
                            <span class="info-box-number">Approved : {{ dashboardData.whos_who.approved }}, Pending: {{ dashboardData.whos_who.pending }}, Rejected  : {{dashboardData.whos_who.rejected }}</span>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
const dashboardData = ref();
const getDashboardData = async () => {
  try {
    debugger;
    const response = await axios.get('/getDashboardData')
    dashboardData.value = response.data
    console.log(response.data);
  } catch (error) {
    console.error('Failed to fetch DashboardData:', error)
  }
}

const isLoading = ref(true);

onMounted(() => {
   getDashboardData();
});
</script>

<!-- <style scoped>
.content-header {
  padding-top: 20px;
}
.content {
  position: relative;
}
</style> -->
