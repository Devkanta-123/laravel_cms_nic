<template>
    <!-- <div class="left-menu">
        <ul class="list-group">
            <li
                class="list-group-item"
                v-for="item in menuItems"
                :key="item.id"
            >
                <router-link :to="item.link" class="list-group-item-action">
                    {{ item.name }}
                </router-link>
            </li>
        </ul>
    </div> -->
    <aside class="blog__sidebar">
        <div class="sidebar__widget">
            <h4 class="sidebar__widget-title">Notice Board</h4>
            <div class="sidebar__cat-list">
                <ul class="list-wrap">
                    <li>
                        <a href="#"><i class="flaticon-arrow-button"></i>Business Solution (15)</a>
                    </li>
                    <li>
                        <a href="#"><i class="flaticon-arrow-button"></i>Marketing Planning (11)</a>
                    </li>
                    <li>
                        <a href="#"><i class="flaticon-arrow-button"></i>SEO Consulting (05)</a>
                    </li>
                    <li>
                        <a href="#"><i class="flaticon-arrow-button"></i>Project Management (07)</a>
                    </li>
                    <li>
                        <a href="#"><i class="flaticon-arrow-button"></i>Business Development
                            (04)</a>
                    </li>
                    <li>
                        <a href="#"><i class="flaticon-arrow-button"></i>Marketing Plan (22)</a>
                    </li>
                </ul>
            </div>
        </div>

    </aside>
</template>

<script setup>




                    // response = await axios.get('/get_notifications', { params: { flag: 'A' } });

</script>

<style scoped>

</style>
