import {usePage} from '@inertiajs/vue3'

export default function __(key, replacements = {}) {
    let translations = usePage().props.translations[key] || key;
    Object.keys(replacements).forEach(key => {
        translations = translations.replace(`:${key}`, replacements[key]);
    }); 

    return translations;
}
