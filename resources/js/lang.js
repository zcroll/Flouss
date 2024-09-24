import {usePage} from '@inertiajs/vue3'

export default function __(key,replcaments={}) {


    let translations = usePage().props.translations[key]||key;
    Object.keys(replcaments).forEach(key => {
        translations = translations.replace(`:${key}`, replacements[key]);
    }); 


    return translations;
}
