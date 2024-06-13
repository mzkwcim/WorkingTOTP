document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("registrationForm");
    form.addEventListener("submit", function(event) {
        const password = form.elements["password"].value;
        const passwordConfirm = form.elements["password_confirm"].value;
        const errorElement = document.createElement("p");
        errorElement.style.color = "red";

        if (password !== passwordConfirm) {
            errorElement.textContent = "Hasła muszą się zgadzać.";
            form.appendChild(errorElement);
            event.preventDefault();
            return;
        }

        if (password.length < 12) {
            errorElement.textContent = "Hasło musi mieć co najmniej 12 znaków.";
            form.appendChild(errorElement);
            event.preventDefault();
            return;
        }

        if (!/[A-Z]/.test(password)) {
            errorElement.textContent = "Hasło musi zawierać co najmniej jedną wielką literę.";
            form.appendChild(errorElement);
            event.preventDefault();
            return;
        }

        if (!/[a-z]/.test(password)) {
            errorElement.textContent = "Hasło musi zawierać co najmniej jedną małą literę.";
            form.appendChild(errorElement);
            event.preventDefault();
            return;
        }

        if (!/\d/.test(password)) {
            errorElement.textContent = "Hasło musi zawierać co najmniej jedną cyfrę.";
            form.appendChild(errorElement);
            event.preventDefault();
            return;
        }

        if (!/[\W]/.test(password)) {
            errorElement.textContent = "Hasło musi zawierać co najmniej jeden znak specjalny.";
            form.appendChild(errorElement);
            event.preventDefault();
            return;
        }
    });
});
