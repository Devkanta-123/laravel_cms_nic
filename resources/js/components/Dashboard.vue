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
                            <span class="info-box-number">Approved : {{ dashboardData.cards.approved }}, Pending: {{
                                dashboardData.cards.pending }}, Rejected : {{ dashboardData.cards.rejected }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-sliders-h highlight-icon "></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Carousel</span>
                            <span class="info-box-number">Approved : {{ dashboardData.carousel.approved }}, Pending: {{
                                dashboardData.carousel.pending }}, Rejected : {{ dashboardData.carousel.rejected
                                }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i
                                class="fa fa-question-circle highlight-icon"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">FAQ</span>
                            <span class="info-box-number">Approved : {{ dashboardData.faqs.approved }}, Pending: {{
                                dashboardData.faqs.pending }}, Rejected : {{ dashboardData.faqs.rejected }}</span>

                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fa fa-image highlight-icon"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Gallery</span>
                            <span class="info-box-number">Approved : {{ dashboardData.gallery.approved }}, Pending: {{
                                dashboardData.gallery.pending }}, Rejected : {{ dashboardData.gallery.rejected }}</span>
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
                            <span class="info-box-number">Approved : {{ dashboardData.latest_news.approved }}, Pending:
                                {{ dashboardData.latest_news.pending }}, Rejected : {{
                                    dashboardData.latest_news.rejected
                                }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fa fa-globe highlight-icon"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Logo</span>
                            <span class="info-box-number">Approved : {{ dashboardData.logo.approved }}, Pending: {{
                                dashboardData.logo.pending }}, Rejected : {{ dashboardData.logo.rejected }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fa fa-map-marker highlight-icon"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Map</span>
                            <span class="info-box-number">Approved : {{ dashboardData.map.approved }}, Pending: {{
                                dashboardData.map.pending }}, Rejected : {{ dashboardData.map.rejected }}</span>

                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fa fa-clipboard highlight-icon"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Notice Board</span>
                            <span class="info-box-number">Approved : {{ dashboardData.notice_board.approved }}, Pending:
                                {{ dashboardData.notice_board.pending }}, Rejected :
                                {{ dashboardData.notice_board.rejected }}</span>
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
                            <span class="info-box-number">Approved : {{ dashboardData.paragraph.approved }}, Pending: {{
                                dashboardData.paragraph.pending }}, Rejected : {{ dashboardData.paragraph.rejected
                                }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fa fa-users highlight-icon"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Whos Who</span>
                            <span class="info-box-number">Approved : {{ dashboardData.whos_who.approved }}, Pending: {{
                                dashboardData.whos_who.pending }}, Rejected : {{ dashboardData.whos_who.rejected
                                }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Monthly ComponentWise Counts</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <canvas id="barChart"></canvas>
                            </div>


                        </div>
                    </div>

                    <!-- /.card -->
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Notifications by Category</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="width: 400px; height: 300px;">
                                <canvas id="pieChart"></canvas>
                            </div>

                        </div>
                    </div>
                    <!-- /.card -->


                </div>

                 <div class="col-lg-3">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Content Status</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="width: 400px; height: 300px;">
                                <canvas id="contentStatus"></canvas>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row mb-5">
                    <div class="col-md-6">
                        <h5 class="text-center">Monthly Component Counts</h5>
                        <canvas id="barChart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-center">Notifications by Category</h5>
                        <canvas id="pieChart"></canvas>
                    </div>
                </div> -->
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Chart from 'chart.js/auto';
let barChartInstance = null;
let pieChartInstance = null;

const dashboardData = ref({
    cards: { approved: 0, pending: 0 },
    carousel: { approved: 0, pending: 0 },
    latest_news: { approved: 0, pending: 0 },
    notice_board: { approved: 0, pending: 0 },
    faqs: { approved: 0, pending: 0 },
    gallery: { approved: 0, pending: 0 },
    logo: { approved: 0, pending: 0 },
    map: { approved: 0, pending: 0 },
    paragraph: { approved: 0, pending: 0 },
    whos_who: { approved: 0, pending: 0 }
})

const getDashboardData = async () => {
    try {
        const response = await axios.get('/getDashboardData')
        dashboardData.value = response.data
    } catch (error) {
        console.error('Failed to fetch DashboardData:', error)
    }
}

const getAdminDashboardStatistics = async () => {
    try {
        debugger;
        const response = await axios.get('/getAdminDashboardStatistics')
        const data = response.data

        loadBarChart(data.monthly_component_counts)
        loadPieChart(data.notifications_by_category)
     loadContentStatus(response.data.content_status_data);

    } catch (err) {
        console.error('Error loading dashboard data:', err)
    }
}
const loadBarChart = (monthlyData) => {
    const barLabels = Object.keys(monthlyData)
    const barValues = Object.values(monthlyData)

    const ctx = document.getElementById('barChart').getContext('2d')
    if (barChartInstance) barChartInstance.destroy()

    barChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: barLabels,
            datasets: [{
                label: 'Components Created This Month',
                data: barValues,
                backgroundColor: 'rgba(0, 123, 255, 0.8)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => `${value}`
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: context => `${context.parsed.y} items`
                    }
                }
            }
        }
    })
}

const loadPieChart = (categoryData) => {
    const pieLabels = categoryData.map(item => item.category_name)
    const pieValues = categoryData.map(item => item.count)
    const pieColors = [
        '#007bff', '#6c757d', '#ffc107', '#28a745', '#dc3545',
        '#6610f2', '#20c997', '#fd7e14', '#17a2b8', '#e83e8c'
    ]

    const ctx = document.getElementById('pieChart').getContext('2d')
    if (pieChartInstance) pieChartInstance.destroy()

    pieChartInstance = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieValues,
                backgroundColor: pieColors.slice(0, pieLabels.length)
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.label}: ${ctx.parsed}`
                    }
                }
            }
        }
    })
}


const loadContentStatus = (ContentStatusData) => {
    const pieLabels = ContentStatusData.map(item => item.category_name);
    const pieValues = ContentStatusData.map(item => item.count);
    const pieColors = ['#F3A26D', '#D2D0A0', '#71C0BB','#096B68']; // Approved, New, Rejected,Updated

    const ctx = document.getElementById('contentStatus').getContext('2d');
    if (window.pieChartInstance) window.pieChartInstance.destroy();

    window.pieChartInstance = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieValues,
                backgroundColor: pieColors.slice(0, pieLabels.length)
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.label}: ${ctx.parsed}`
                    }
                }
            }
        }
    });
};








onMounted(() => {
    getDashboardData();
    getAdminDashboardStatistics();
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
