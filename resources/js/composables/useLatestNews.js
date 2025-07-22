// composables/useLatestNews.js
import { ref } from 'vue';
import axios from 'axios';

export function useLatestNews() {
    const items = ref([]);

    const fetchData = async() => {
        try {
            const response = await axios.post('/api/get_latest_news');
            items.value = response.data;

            // ✅ Reinitialize DataTable with scrolling
            setTimeout(() => {
                const table = $('#adminLatestNews');
                if ($.fn.DataTable.isDataTable(table)) {
                    table.DataTable().clear().destroy();
                }

                table.DataTable({
                    scrollY: '300px', // Add vertical scrolling
                    scrollCollapse: true,
                    paging: true,
                    searching: true,
                    responsive: true
                });
            }, 0);
        } catch (error) {
            console.error('Error fetching latest news:', error);
        }
    };

    return {
        items,
        fetchData
    };
}