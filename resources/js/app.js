require('./bootstrap');
/**
 * https://ace.c9.io/api/editor.html#Editor.remove
 */
const eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
const eventer = window[eventMethod];
const messageEvent = eventMethod === "attachEvent" ? "onmessage" : "message";
const frameCallbacks = {};
window.app = {
    init() {
        ace.config.loadModule('ace/ext/language_tools');
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
        // 4. Initialize ace editor
        var theme = "ace/theme/tomorrow_night"
        for (const editorField of document.querySelectorAll('[data-editor],[data-markdown-editor]')) {
            const editorDom = document.getElementById(editorField.dataset.markdownEditor || editorField.dataset.editor)
            const defaultMime = editorField.dataset.mime || 'text/markdown';
            const parent = editorDom.parentElement;
            const editor = ace.edit(editorDom, {
                theme: theme,
                mode: app.ace.getModeByMime(defaultMime),
                minLines: 10,
                maxLines: 25,
                showLineNumbers: true,
                cursorStyle: "smooth",
                fontSize: "14px",
                fontFamily: "monospace",
                highlightActiveLine: true,
                highlightGutterLine: false,
                printMargin: true,
                wrap: true,
                indentedSoftWrap: true,
                showGutter: false,
            })
            editor.on('change', async function() {
                const event = document.createEvent('Event');
                event.initEvent('change', true, true);
                editorField.value = editor.getValue();
                editorField.dispatchEvent(event);
            })
            if (parent) {
                for (const insertImageBtn of parent.querySelectorAll('[data-ace-upload]')) {
                    if (insertImageBtn.dataset.aceUpload) {
                        const url = insertImageBtn.dataset.aceUpload;
                        delete insertImageBtn.dataset.aceUpload;
                        insertImageBtn.addEventListener('click', function(e){
                            e.preventDefault();
                            app.modal.frame(url, function(data){
                                editor.insert('![Alt]('+data.id+' "Title")')
                                editor.renderer.scrollCursorIntoView()
                            });
                        })
                    }
                }
                const actions = {
                    redo(e) {
                        editor.redo();
                    },
                    undo(e) {
                        editor.undo();
                    },
                    formatBold(e) {
                        editor.insertSnippet("**${1:$SELECTION}**");
                        editor.renderer.scrollCursorIntoView()
                    },
                    formatItalic(e) {
                        editor.insertSnippet("*${1:$SELECTION}*");
                        editor.renderer.scrollCursorIntoView()
                    },
                    formatUnderline(e) {
                        editor.insertSnippet("__${1:$SELECTION}__");
                        editor.renderer.scrollCursorIntoView()
                    },
                    formatStrikethrough(e) {
                        editor.insertSnippet("~~${1:$SELECTION}~~");
                        editor.renderer.scrollCursorIntoView()
                    },
                    formatH1(e) {
                        const row = editor.getSelectionRange().start.row;
                        const line = editor.session.getLine(row);
                        if (!line.startsWith('# ')) {
                            editor.session.insert({row:row, column:0}, '# ')
                        }
                    },
                    formatH2(e) {
                        const row = editor.getSelectionRange().start.row;
                        const line = editor.session.getLine(row);
                        if (!line.startsWith('## ')) {
                            editor.session.insert({row:row, column:0}, '## ')
                        }
                    },
                    listIndexed(e) {
                        const row = editor.getSelectionRange().start.row;
                        const line = editor.session.getLine(row);
                        if (!line.startsWith("1.\t")) {
                            editor.session.insert({row:row, column:0}, "1.\t")
                        }
                    },
                    listMarked(e) {
                        const row = editor.getSelectionRange().start.row;
                        const line = editor.session.getLine(row);
                        if (!line.startsWith("*\t")) {
                            editor.session.insert({row:row, column:0}, "*\t")
                        }
                    },
                    href(e) {
                        editor.insertSnippet("[${1:$SELECTION}](${1:$SELECTION} \"${1:$SELECTION}\")");
                        editor.renderer.scrollCursorIntoView()
                    },
                };
                for (const actionBtn of parent.querySelectorAll('[data-ace-action]')) {
                    const action = actionBtn.dataset.aceAction;
                    delete actionBtn.dataset.aceAction;
                    actionBtn.addEventListener('click', function(e){
                        e.preventDefault();
                        if (actions[action]) {
                            actions[action](e);
                        }
                    })
                }
            }
        }
        for (const previewDom of document.querySelectorAll('[data-markdown-preview]')) {
            const target = document.getElementById(previewDom.dataset.markdownPreviewTarget)
            const previewAPI = previewDom.dataset.markdownPreviewApi;
            delete previewDom.dataset.markdownPreviewTarget;
            delete previewDom.dataset.markdownPreview;
            delete previewDom.dataset.markdownPreviewApi;
            if (target) {
                const previewer = async function() {
                    const resp = await window.app.request.post(previewAPI, {
                        content: target.value,
                    })
                    if (resp && resp.content) {
                        previewDom.innerHTML = resp.content;
                    }
                };
                previewer(); // initial load
                var timeout = null;
                target.addEventListener('change', function(e) {
                    if (timeout) clearTimeout(timeout);
                    timeout = setTimeout(previewer, 800);
                })
            }
        }
    },
    // Return if this window is popup / iframe
    isFrame() {
        return window.parent || window.opener;
    },
    // Ace editor helpers
    ace: {
        getModeByMime(mime)
        {
            switch (mime) {
                case 'text/html':
                    return 'ace/mode/html';
                case 'script/blade':
                    return 'ace/mode/php_laravel_blade';
                case 'text/markdown':
                    return 'ace/mode/markdown';
            }
            return 'ace/mode/text';
        }
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
        async post(uri = '', data = {}) {
            // Default options are marked with *
            const response = await fetch(uri, {
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
