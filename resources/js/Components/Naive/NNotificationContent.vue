<script>
import {useNotification} from "naive-ui";

export default defineComponent({
    setup() {
        window.$notify = useNotification()
    }
})
</script>
