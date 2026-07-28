document.addEventListener('DOMContentLoaded' , () => {
    const allForm = document.querySelectorAll('form');

    allForm.forEach((form) => {
        const dataElement = form.querySelector('.g-recaptcha');
        if (!dataElement) return;

        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            event.stopPropagation();

            window.grecaptcha.ready(async () =>
                {
                    const response = await verifyRecaptcha(form, dataElement);

                    if (response) {
                        dataElement.value = response;
                        form.submit();
                    }
                }
            );
        })
    })
})

async function verifyRecaptcha(form, dataElement) {
    const { sitekey } = dataElement.dataset;

    if (!sitekey) return;

    return new Promise((resolve, reject) => {
        window.grecaptcha.execute(sitekey, {action: 'submit'}).then((token) => {
            if (token) {
                resolve(token);
            }

            reject('Invalid Captcha');
        })
    });
}
