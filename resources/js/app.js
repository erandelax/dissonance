require('./bootstrap');

const eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
const eventer = window[eventMethod];
const messageEvent = eventMethod === "attachEvent" ? "onmessage" : "message";
const frameCallbacks = {};
window.app = {
    isFrame() {
        return window.parent || window.opener;
    },
    modal: {
        frame(src, callback) {
            const sessionID = 'iframe-' + Math.floor(Math.random() * 100000000);
            const iframe = document.getElementById('iframe-modal-body');
            iframe.src = src + '?iframe=' + sessionID + '#' + sessionID;
            iframe.onload = function () {
                iframe.onload = null;
                halfmoon.toggleModal('iframe-modal-popup');
                frameCallbacks[sessionID] = function(data) {
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
        const sessionID = window.location.hash.substr(1);
        if (window.parent) {
            window.parent.postMessage({
                sessionID: sessionID,
                data: data,
            }, "*")
        } else if (window.opener) {
            window.opener.postMessage({
                sessionID: sessionID,
                data: data,
            }, "*")
            window.close();
        }
    }
} else {
    window.app.modal.frame.reply = function (data) {
        console.warn('I cant reply if there is no parent window :(');
    }
}
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

document.addEventListener('change', function(e) {
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
document.addEventListener('DOMContentLoaded', function(){
    document.body.addEventListener(
        "error",
        function(event) {
            if (event.target.tagName === 'IMG') {
                event.target.src = '/img/404.svg';
            }
        },
        true
    );
})
document.addEventListener('click', function(e) {
    var target = e.target;
    if (target.tagName === 'I') {
        target = target.parentElement;
    }
    if(target.dataset.iframeInput) {
        window.app.modal.frame(target.dataset.iframeInput, function(data){
            target.value = data.value;
        })
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


window.api = {
    wiki: {
        preview: async function ({content}) {
            return await postData(route('api:wiki.preview'), {content})
        }
    }
}
