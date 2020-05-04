import fr_CD from './translations/fr_CD';

const translations = {
    fr_CD: fr_CD,
};

export function trans(key) {
    const locale = document.body.getAttribute('data-locale');

    return translations[locale][key] ?? key;
}
