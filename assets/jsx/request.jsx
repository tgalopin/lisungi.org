const needs = document.querySelectorAll('.request-need');

for (let i in needs) {
    if (!needs.hasOwnProperty(i)) {
        continue;
    }

    const need = needs[i];
    const checkbox = need.querySelector('input[type="checkbox"]');
    const details = need.querySelector('.request-need-details');

    if (!checkbox || !details) {
        continue;
    }

    function refresh() {
        if (checkbox.checked) {
            details.style.opacity = 1.0;
        } else {
            details.style.opacity = 0.5;
        }
    }

    refresh();
    checkbox.addEventListener('input', refresh);
}
