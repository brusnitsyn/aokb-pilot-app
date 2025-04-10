import {NIcon} from 'naive-ui'
import {usePage} from "@inertiajs/vue3";
export const renderIcon = (icon) => {
    return () => {
        return h(NIcon, null, {
            default: () => h(icon)
        })
    }
}

// Функция для получения геолокации
export const fetchUserLocation = () => {
    const coords = []
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                coords.push(position.coords.longitude)
                coords.push(position.coords.latitude)
            },
            (error) => {
                console.error("Ошибка геолокации:", error.message);
            },
            { enableHighAccuracy: true }
        );
    } else {
        console.error("Геолокация не поддерживается браузером");
    }
    return coords
    // return usePage().props.auth.user.department.coords
}
