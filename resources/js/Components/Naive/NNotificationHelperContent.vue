<script>
import {useNotification} from "naive-ui";

export default defineComponent({
    setup() {
        window.$notifyHelper = useNotification()
    }
})
</script>
