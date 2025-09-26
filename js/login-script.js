document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");
    const loginEmailInput = document.getElementById("loginEmail");
    const loginPasswordInput = document.getElementById("loginPassword");
    const rememberMeCheckbox = document.getElementById("rememberMe");

    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const email = loginEmailInput.value.trim();
        const password = loginPasswordInput.value.trim();
        const rememberMe = rememberMeCheckbox.checked;

        if (!email || !password) {
            alert("Please enter email and password.");
            return;
        }

        try {
            const response = await fetch("assets/php/login.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, password })
            });

            const result = await response.json();
            console.log("Login response:", result);
            
            if (result.success) {
                // Save user ID locally or in session
                if (rememberMe) {
                    localStorage.setItem("user_id", result.user_id);
                } else {
                    sessionStorage.setItem("user_id", result.user_id);
                }
                
                window.location.href = "dashboard.html";
            } else {
                alert("Login failed: " + result.message);
            }
        } catch (err) {
            console.error("Login request failed:", err);
            alert("Something went wrong. Please try again.");
        }
    });
});