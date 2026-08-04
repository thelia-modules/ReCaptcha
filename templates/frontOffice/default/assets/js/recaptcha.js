document.addEventListener('DOMContentLoaded' , () => {
    const allForm = document.querySelectorAll('form');

    allForm.forEach((form) => {

        const dataElement = form.querySelector('.g-recaptcha');

        if (!dataElement) return;

        loadRecaptchaScript(form);

        form.addEventListener("submit", async (event) => {
            if (typeof window.grecaptcha === 'undefined') {
                console.error('reCAPTCHA is not available, submitting without a token');

                return;
            }

            event.preventDefault();
            event.stopPropagation();

            window.grecaptcha.ready(async () =>
                {
                    try {
                        const response = await verifyRecaptcha(form, dataElement);

                        if (response) {
                            dataElement.value = response;
                            form.submit();
                        }
                    } catch (error) {
                        console.error(error);
                    }
                }
            );
        })
    })
})

function loadRecaptchaScript(form) {
    const fields = form.querySelectorAll('input, textarea, select');

    [...fields].forEach(field => {

        field.addEventListener('focus', () => {
            if (document.getElementById('ScriptRecaptcha') || !window.SCRIPTRECAPTCHA) return null;

            const scriptTag = document.createElement('script');
            scriptTag.setAttribute('src', window.SCRIPTRECAPTCHA);
            scriptTag.setAttribute('id', 'ScriptRecaptcha');
            document.head.appendChild(scriptTag);
        });
    })
}


async function verifyRecaptcha(form, dataElement) {
    const { sitekey } = dataElement.dataset;

    if (!sitekey) return;

    return new Promise((resolve, reject) => {
        window.grecaptcha.execute(sitekey, {action: 'submit'}).then((token) => {
            if (token) {
                resolve(token);
                return;
            }

            reject('Invalid Captcha');
        })
    });
}


