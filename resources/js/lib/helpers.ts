import { toast } from 'vue-sonner'
import { Page } from '@inertiajs/core'

export const toastSuccess = (message: string, description?: string, options? : typeof toast) => {
    toast.success(message, {
        description: description,
        ...options
    })
}

export const toastError = (message: string, description?: string, options? : typeof toast) => {
    toast.error(message, {
        description: description,
        ...options
    })
}

export const toastInfo = (message: string, description?: string, options? : typeof toast) => {
    toast.info(message, {
        description: description,
        ...options
    })
}

export const getRandomAuthImage = ()=>{
    const files = [
        "/images/auth/1.png",
        "/images/auth/2.png",
        "/images/auth/3.png",
        "/images/auth/4.png",
        "/images/auth/5.png",
        "/images/auth/6.png",
        "/images/auth/7.png",
        "/images/auth/8.png",
        "/images/auth/9.png",
    ]
    return files[Math.floor(Math.random() * files.length)]
}

export const alertResponse = (res: Page, sCallback?: CallableFunction, eCallback?: CallableFunction) => {
    const message = res.props.message
    if (res.props.success) {
        toastSuccess(message);
        if (sCallback) sCallback()
    }
    else {
        toastError(message);
        if (eCallback) eCallback()
    }
}

export const chunkArray = <T>(array: T[], size: number): [T[]] => {
    const result: [] = []
    for (let i = 0; i < array.length; i += size) {
        result.push(array.slice(i, i + size) as never)
    }
    return result as unknown as [T[]]
}

export const number_format = (val: number, decimal=2)=> {
    const num = (Math.ceil(val * Math.pow(10, decimal)) / Math.pow(10, decimal));
    if (Number.isNaN(num)) {
        return "0.0";
    }
    return num.toFixed(decimal);
}

export const dateAndTime = (dateStr: string)=>{
    return new Date(dateStr).toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    })
}

export function ucFirst(str: string): string {
    return str.charAt(0).toUpperCase() + str.slice(1);
}
