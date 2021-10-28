require('./bootstrap');

const eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
const eventer = window[eventMethod];
const messageEvent = eventMethod === "attachEvent" ? "onmessage" : "message";
const frameCallbacks = {};
window.app = {
    init() {
        // 1. Iframe modal window:
        // use <data-frame-modal=URL> to call for IFRAME popup and then use
        // <data-iframe-return-url="attribute@selector"> to map the values
        // send back from iframe via {window.app.frame.reply}.
        const iframeModals = document.querySelectorAll('[data-iframe-modal]');
        if (iframeModals) for (const iframeModal of iframeModals) {
            iframeModal.addEventListener('click', function () {
                const iframeURL = iframeModal.dataset.iframeModal;
                window.app.modal.frame(iframeURL, function (data) {
                    for (const key in data) {
                        const param = 'iframeReturn' + key.charAt(0).toUpperCase() + key.substr(1);
                        const targetQuery = iframeModal.dataset[param];
                        if (targetQuery) {
                            const parts = targetQuery.split('@');
                            const targetSelector = parts.length > 0 ? parts[1] : parts[0];
                            const targetAttribute = parts.length > 0 ? parts[0] : 'value';
                            for (const target of document.querySelectorAll(targetSelector)) {
                                target[targetAttribute] = data[key];
                            }
                        }
                    }
                })
            })
        }
        // 2. Replace failed to load images with default 404 image
        document.body.addEventListener('error', function (event) {
            if (event.target.tagName === 'IMG') {
                const extension = event.target.src ? event.target.src.split('.').reverse()[0] || 'N/A' : 'N/A';
                event.target.src = '/placeholder.svg?value=.'+extension.toUpperCase();
            }
        },true);
        // 3. Show load alerts
        const alerts = window.alerts || [];
        for (const alert of alerts) {
            halfmoon.initStickyAlert(alert);
        }
    },
    // Return if this window is popup / iframe
    isFrame() {
        return window.parent || window.opener;
    },
    // Request helpers
    request: {
        // Request get parameters getter
        get(name, url = window.location.href) {
            console.log(window.location.href);
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        },
    },
    // Modal helpers
    modal: {
        // Open URL in iframe and expect return values from it
        frame(src, callback) {
            const sessionID = 'iframe-' + Math.floor(Math.random() * 100000000);
            const iframe = document.getElementById('iframe-modal-body');
            const hasGetParameters = src.split('?').length > 1;
            iframe.src = src + (hasGetParameters ? '&' : '?') + 'iframe=' + sessionID;
            iframe.onload = function () {
                iframe.onload = null;
                halfmoon.toggleModal('iframe-modal-popup');
                frameCallbacks[sessionID] = function (data) {
                    const popup = document.getElementById('iframe-modal-popup');
                    if (popup.classList.contains('show')) {
                        halfmoon.toggleModal('iframe-modal-popup');
                    }
                    iframe.innerHTML = '';
                    callback(data.data);
                };
            }
        }
    },
};
// Listen for return values from popups and iframes
eventer(messageEvent, function (e) {
    const data = e.data ? e.data : e.message;
    if (data.sessionID) {
        if (frameCallbacks[data.sessionID]) {
            frameCallbacks[data.sessionID](data);
            delete frameCallbacks[data.sessionID];
        }
    }
});
if (window.app.isFrame()) {
    window.app.modal.frame.reply = function (data) {
        const sessionID = window.app.request.get('iframe')
        if (window.parent) {
            window.parent.postMessage({sessionID: sessionID, data: data,}, "*")
        } else if (window.opener) {
            window.opener.postMessage({sessionID: sessionID, data: data,}, "*")
            window.close();
        }
    }
}
// Initialize application
document.addEventListener('DOMContentLoaded', window.app.init);

// ....


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

document.addEventListener('change', function (e) {
    const target = e.target;
    if (target.tagName === 'INPUT' && target.type === 'file') {
        const file = e.target.files[0];
        if (target.dataset.imgPreview) {
            const preview = document.getElementById(target.dataset.imgPreview);
            if (preview) {
                preview.src = URL.createObjectURL(file);
            }
        }
    }
})
document.addEventListener('click', function (e) {
    var target = e.target;
    if (target.tagName === 'I') {
        target = target.parentElement;
    }
    if (target.dataset.submitFormAction && target.form !== undefined && target.form.elements['form-action']) {
        target.form.elements['form-action'].value = target.dataset.submitFormAction;
        target.form.elements['form-model'].value = target.dataset.submitFormModel;
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


window.api = { // todo move to app api
    wiki: {
        preview: async function ({content}) {
            return await postData(route('api:wiki.preview'), {content})
        }
    }
}
