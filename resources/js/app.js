require('./bootstrap');

async function postData(url = '', data = {}) {
    // Default options are marked with *
    const response = await fetch(url, {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        mode: 'cors', // no-cors, *cors, same-origin
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin', // include, *same-origin, omit
        headers: {
            'Content-Type': 'application/json'
            // 'Content-Type': 'application/x-www-form-urlencoded',
        },
        redirect: 'follow', // manual, *follow, error
        referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
        body: JSON.stringify(data) // body data type must match "Content-Type" header
    });
    return response.json(); // parses JSON response into native JavaScript objects
}

window.submitFormAction = function(e) {
    // e.target.elements[form-action].value
    console.log(e);
}

document.addEventListener('click', function(e) {
    var target = e.target;
    if (target.tagName === 'I') {
        target = target.parentElement;
    }
    if (target.dataset.submitFormAction && target.form !== undefined && target.form.elements['form-action']) {
        target.form.elements['form-action'].value = target.dataset.submitFormAction;
        target.form.submit();
    }
    if (target.dataset.nameFlush && target.form !== undefined) {
        const field = target.form.elements[target.dataset.nameFlush];
        if (field && field.value) {
            field.value = '';
            if (field.dispatchEvent && window.Event) {
                field.dispatchEvent(new Event('change'));
            }
            field.focus();
        }
    }
    if (target.dataset.switchAll && target.form !== undefined && target.htmlFor) {
        const ref = document.getElementById(target.htmlFor);
        if (!ref) return;
        for (const element of target.form.elements) {
            const name = element.name;
            if (name && name.startsWith && name.startsWith(target.dataset.switchAll)) {
                element.checked = !ref.checked;
            }
        }
    }
})

window.api = {
    wiki: {
        preview: async function ({content}) {
            return await postData(route('api:wiki.preview'), {content})
        }
    }
}
