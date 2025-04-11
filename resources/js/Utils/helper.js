import {NIcon} from 'naive-ui'
import {h} from 'vue'
import {Motion} from "motion-v";
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
        )
        return coords
    } else {
        console.error("Геолокация не поддерживается браузером");
        return [
            127.506377, 50.304888
        ]
    }

    // return usePage().props.auth.user.department.coords
}

export const useTimeLeft = (dateTime) => {
    const timeLeft = ref(0)
    const isExpired = ref(false)

    let intervalId

    const updateTime = () => {
        const targetDate = new Date(dateTime)
        const endTimestamp = targetDate.getTime() + 24 * 60 * 60 * 1000
        const currentTimeLeft = endTimestamp - Date.now()

        timeLeft.value = currentTimeLeft
        isExpired.value = currentTimeLeft <= 0
    }

    onMounted(() => {
        updateTime()
        intervalId = setInterval(updateTime, 1000)
    })

    onUnmounted(() => {
        clearInterval(intervalId)
    })

    return { timeLeft, isExpired }
}

// Компонент таймера (привязан к ID статуса)
export const renderTime = (statusId, changedStatusAt, lastStatusAt) => {
    return defineComponent({
        setup() {
            const { timeLeft, isExpired } = useTimeLeft(lastStatusAt)

            const formatTime = (ms) => {
                // Ваша реализация форматирования времени
                if (ms <= -48 * 60 * 60 * 1000) return '-48:00:00'

                const seconds = Math.floor(ms / 1000)
                const absSeconds = Math.abs(seconds)
                const h = Math.floor(absSeconds / 3600)
                const m = Math.floor((absSeconds % 3600) / 60)
                const s = absSeconds % 60
                return `${seconds < 0 ? '-' : ''}${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`
            }

            return () => {
                const hoursLeft = timeLeft.value / (1000 * 60 * 60)

                if (statusId === 3) {
                    // Для статуса 'Закрыт' - показываем время с момента изменения
                    const timePassed = new Date(changedStatusAt).getTime() - new Date(lastStatusAt).getTime()
                    return h('span', {
                        style: { color: '#888', fontWeight: 600 }
                    }, `${formatTime(timePassed)}`)
                }

                if (statusId === 1) return h(
                    'span',
                    {
                        style: {
                            fontWeight: 500
                        }
                    },
                    {
                        default: () => '----'
                    }
                )

                if (hoursLeft <= -24) {
                    return h(Motion, {
                        initial: { opacity: 1 },
                        animate: {
                            opacity: [0.2, 1],
                            transition: {
                                duration: 1.0,
                                repeat: Infinity,
                                repeatType: 'mirror',
                                ease: 'easeInOut'
                            }
                        }
                    }, () => h('span', {
                        style: {
                            color: '#d03050',
                            fontWeight: 500
                        }
                    }, '-24:00:00'))
                }

                return h('span', {
                    style: {
                        color: isExpired.value ? '#d03050' : '#18a058',
                        fontWeight: 500
                    }
                }, formatTime(timeLeft.value))
            }
        }
    })
}

// Форматирование времени
export const formatTime = (ms) => {
    const absMs = Math.abs(ms)
    const hours = Math.floor(absMs / (1000 * 60 * 60))
    const minutes = Math.floor((absMs % (1000 * 60 * 60)) / (1000 * 60))
    const seconds = Math.floor((absMs % (1000 * 60)) / 1000)

    const sign = ms <= 0 ? '-' : ''
    return `${sign}${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`
}
