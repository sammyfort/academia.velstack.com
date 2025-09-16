import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { toastError, toastSuccess } from "@/lib/helpers";

export function useDelete() {
    const isDeletingOne = ref(false);
    const isDeletingMany = ref(false);

    const deleteOne = (routeName: string, id: number | string) => {
        isDeletingOne.value = true;
        router.delete(route(routeName, id), {
            onSuccess: (res) => {
                if (res.props.success) toastSuccess(res.props.message);
                else toastError(res.props.message);
            },
            onFinish: () => {
                isDeletingOne.value = false;

            },
            onError: () => {
                isDeletingOne.value = false;
            }
        });
    };

    const deleteMany = (routeName: string, keys: (number | string)[], done?: () => void,   postDelete?: (keys: (number | string)[]) => void ) => {
        if (!keys.length) return;

        isDeletingMany.value = true;

        router.delete(route(routeName), {
            data: { keys },
            onSuccess: (res) => {
                if (res.props.success) toastSuccess(res.props.message);
                else toastError(res.props.message);
            },
            onFinish: () => {
                isDeletingMany.value = false;
                if (done) done();
                if (postDelete) postDelete(keys);
            },
            onError: () => {
                isDeletingMany.value = false;
            },
        });
    };


    return {
        isDeletingOne,
        isDeletingMany,
        deleteOne,
        deleteMany,
    };
}
