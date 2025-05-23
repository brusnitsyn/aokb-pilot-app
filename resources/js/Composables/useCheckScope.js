import { usePage } from '@inertiajs/vue3';
import {Scopes} from "@/auth.constants.js";
export function useCheckScope() {
    const { props } = usePage()
    const hasScope = (scope) => {
        return props.auth.user && props.auth.user.role.scopes.find(itm => itm.name === scope)
    }

    const hasRole = (role) => {
        return props.auth.user && props.auth.user.role.name === role
    }

    const scopes = Scopes

    return {
        hasScope,
        hasRole,
        scopes
    }
}
