
document.addEventListener("DOMContentLoaded", () => {
    const signupForm = document.querySelector("#signupModal form");
    const emailInput = document.getElementById("signupEmail");
    const passwordInput = document.getElementById("signupPassword");
    const confirmPasswordInput = document.getElementById("signupConfirmPassword");

    // Basic strong password regex (min 8 chars, 1 uppercase, 1 lowercase, 1 number)
    const strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

    signupForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();
        let valid = true;

        // Reset validation states
        emailInput.classList.remove("is-invalid");
        passwordInput.classList.remove("is-invalid");
        confirmPasswordInput.classList.remove("is-invalid");

        // Validate email format
        if (!/^\S+@\S+\.\S+$/.test(email)) {
            emailInput.classList.add("is-invalid");
            valid = false;
        } else {
            // Optionally check if email actually exists using an API
            // Example: Using https://isitarealemail.com or similar services
            // Here we'll just simulate a check:
            // const emailExists = await checkEmailExists(email);
            // if (!emailExists) { emailInput.classList.add("is-invalid"); valid = false; }
        }

        // Validate password strength
        if (!strongPasswordRegex.test(password)) {
            passwordInput.classList.add("is-invalid");
            valid = false;
        }

        // Validate password confirmation
        if (password !== confirmPassword) {
            confirmPasswordInput.classList.add("is-invalid");
            valid = false;
        }

        // ✅ If all valid, redirect to profile-setup.html
        if (valid) {
            window.location.href = "profile-setup.html";
        }
    });
});
