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

        // ✅ If all valid, send data to backend
        if (valid) {
            try {
                const response = await fetch("assets/php/signup.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ email, password })
                });

                const result = await response.json();
                console.log("Signup response:", result);

                if (result.success) {
                    alert("Sign up successful!");
                    // Save user ID locally so we can link profile later
                    localStorage.setItem("user_id", result.user_id);
                    window.location.href = "profile-setup.html";
                } else {
                    alert("Sign up failed: " + result.message);
                }
            } catch (err) {
                console.error("Signup request failed:", err);
                alert("Something went wrong. Please try again.");
            }
        }
    });
});
