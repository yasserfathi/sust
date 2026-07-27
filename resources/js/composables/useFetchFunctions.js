import axios from "axios";
import { ref } from "vue";

export function useFetchFunctions(api) {
    const data = ref([]);
    const tableLoading = ref(false);
    const totalItems = ref(0);
    const options = ref({ page: 1, itemsPerPage: 5, sortBy: [], search: { name: '' } });

    async function apiData(params) {
        try {
            let itemsPerPage = params.itemsPerPage;

            if (itemsPerPage === -1) {
                itemsPerPage = totalItems.value;
            }

            const queryParams = new URLSearchParams({
                items: itemsPerPage,
                page: params.page,
            });

            if (params.search?.trim()) {
                queryParams.append('search', params.search.trim());
            }

            const sortBy = params.sortBy || [];
            if (sortBy.length > 0) {
                const sort = sortBy[0];
                if (sort.key === 'user.name') {
                    sort.key = 'name';
                }
                queryParams.append('orderby', sort.key);
                queryParams.append('ascend', sort.order === 'asc');
            }

            const response = await axios.get(`${api}?${queryParams.toString()}`);
            return {
                items: response.data.result.data,
                total: response.data.result.total,
            };
        } catch (error) {
            console.error("API Error:", error);
            throw new Error(`Failed to fetch data: ${error.message}`);
        }
    }

    async function LoadItems(params) {
        if (params != null) {
            options.value = { ...options.value, ...params };
        }
        tableLoading.value = true;
        try {
            const result = await apiData(options.value);
            data.value = result.items;
            totalItems.value = result.total;
        } catch (error) {
            throw error;
        } finally {
            tableLoading.value = false;
        }
    }

    return { LoadItems, data, tableLoading, totalItems, options };
}
