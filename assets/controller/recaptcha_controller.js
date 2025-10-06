import { Controller } from "@hotwired/stimulus";

class RecaptchaController extends Controller {
  static form = null;

  initialize() {
    this.form = this.element.closest("form");
    this.loadRecaptchaScript();
  }

  connect() {
    this.form.addEventListener("submit", (e) => {
      e.preventDefault();

      window.grecaptcha.ready(() => {
        const response = this.verifyRecaptcha(this.form, this.element).then(
          (token) => {
            this.element.value = token;
            e.target.submit();
          }
        );
      });
    });
  }

  loadRecaptchaScript() {
    if (!document.getElementById("recaptchaScript")) {
      const script = document.createElement("script");
      script.src = this.element.dataset.script;
      script.id = "recaptchaScript";
      this.form.appendChild(script);
    }
  }

  verifyRecaptcha(form, dataElement) {
    const { sitekey } = dataElement.dataset;

    if (!sitekey) return;

    return new Promise((resolve, reject) => {
      window.grecaptcha.execute(sitekey, { action: "submit" }).then((token) => {
        if (token) {
          resolve(token);
        }

        reject("Invalid Captcha");
      });
    });
  }
}

export default RecaptchaController;
