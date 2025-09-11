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

export const dateAndTime = (dateStr: string, withTime: boolean = false) => {
    const date = new Date(dateStr)
    return date.toLocaleString("en-US", withTime
        ? {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
        }
        : {
            year: "numeric",
            month: "short",
            day: "numeric",
        }
    )
}

export function ucFirst(str: string): string {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

export function copyRefContent(elRef, message? = 'Content copied to clipboard !') {
    if (!elRef) return;
    const code = elRef.innerText;
    copyContent(code, message)
}

export function copyContent(code: string, message? = 'Content copied to clipboard !') {
    // Try Clipboard API
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(code)
            .then(() => {
                toastSuccess(message);
            })
            .catch(err => {
                toastError("Failed to copy to clipboard");
            });
    } else {
        // Fallback using execCommand
        const textarea = document.createElement('textarea');
        textarea.value = code;
        textarea.style.position = 'fixed'; // Avoid scrolling to bottom
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();

        try {
            const successful = document.execCommand('copy');
            successful
                ? toastSuccess(message)
                : toastError("Failed to copy to clipboard");
        } catch (err) {
            toastError("Failed to copy to clipboard");
        }
        document.body.removeChild(textarea);
    }
}

export function cediSign(): string{
    return "₵"
}

export function generateShareLinks(url: string|null = null, text = "Check this out!") {
    const shareUrl = encodeURIComponent(url || window.location.href);
    const shareText = encodeURIComponent(text);

    return {
        facebook: `https://www.facebook.com/sharer/sharer.php?u=${shareUrl}`,
        whatsapp: `https://wa.me/?text=${shareText}%20${shareUrl}`,
        x: `https://twitter.com/intent/tweet?text=${shareText}&url=${shareUrl}`,
    };
}

export function randomString(length: number = 8) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
}

export function whatsappChatLink(mobileNumber: string, message: string) {
    if (!mobileNumber || !mobileNumber.length) return;
    const cleanedNumber = mobileNumber.replace(/[\s\-()+]/g, "");
    if (!/^\d+$/.test(cleanedNumber)) {
        return
    }
    if (cleanedNumber.length < 8) {
        return
    }
    const encodedMessage = encodeURIComponent(message);
    return `https://wa.me/${cleanedNumber}?text=${encodedMessage}`;
}
