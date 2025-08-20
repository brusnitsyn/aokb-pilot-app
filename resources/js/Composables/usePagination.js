const createAxiosInstance = () => {
    const instance = axios.create({
        baseURL: '/',
        withCredentials: true,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Cache-Control': 'no-cache, no-store, must-revalidate',
            'Pragma': 'no-cache',
            'Expires': '0'
        }
    })

    // Добавляем timestamp к каждому запросу чтобы избежать кеширования
    instance.interceptors.request.use((config) => {
        if (config.method?.toLowerCase() === 'get') {
            config.params = {
                ...config.params,
                _t: Date.now() // Добавляем timestamp
            }
        }
        return config
    })

    return instance
}

export function usePagination(apiRoute, initialParams = {}) {
    const items = ref([])
    const currentPage = ref(1)
    const lastPage = ref(1)
    const loading = ref(false)
    const hasMore = ref(true)

    const loadData = async (page, append = false) => {
        if (loading.value) return

        loading.value = true
        try {
            const axiosInstance = createAxiosInstance()
            console.log('GEt state - currentPage:', page, 'lastPage:', lastPage.value)
            await axiosInstance.get(route(apiRoute, { page, ...initialParams}, true))
                .then(res => {
                    if (page === 1 || !append) {
                        items.value = res.data.data
                        // currentPage.value = 1
                    } else {
                        items.value = [...items.value, ...res.data.data]
                    }

                    if (hasMore.value) {
                        currentPage.value = res.data.meta.current_page + 1
                    }

                    lastPage.value = res.data.meta.last_page

                    if (res.data.meta.current_page >= res.data.meta.last_page) {
                        hasMore.value = false
                    }

                    console.log('Updated state - currentPage:', currentPage.value, 'lastPage:', lastPage.value)
            })
        } catch (error) {
            console.error('Ошибка загрузки:', error)
        } finally {
            loading.value = false
        }
    }

    const loadMore = async (page) => {
        if (hasMore.value && !loading.value) {
            await loadData(page, true)
        }
    }

    const refresh = async () => {
        await loadData(1, false)
    }

    return {
        items,
        currentPage,
        lastPage,
        loading,
        hasMore,
        loadData,
        loadMore,
        refresh
    }
}
