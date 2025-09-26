const userId = localStorage.getItem("user_id") || sessionStorage.getItem("user_id");
const loginToken = localStorage.getItem("login_token") || sessionStorage.getItem("login_token");

if (!userId || !loginToken) {
    alert("Please log in first.");
    window.location.href = "login.html"; // unified redirect
} else {
    // Validate token with backend
    fetch(`assets/php/validate-token.php?token=${loginToken}`)
        .then(res => res.json())
        .then(data => {
        if (!data.valid) {
            alert("Session expired. Please log in again.");
            localStorage.clear();
            sessionStorage.clear();
            window.location.href = "login.html"; // unified redirect
        } else {
            // Display user ID
            const userIdDisplay = document.getElementById("userIdDisplay");
            userIdDisplay.textContent = data.user_id;
        }
    })
        .catch(err => {
        console.error("Token validation failed:", err);
        alert("Something went wrong. Please log in again.");
        localStorage.clear();
        sessionStorage.clear();
        window.location.href = "login.html"; // unified redirect
    });
}

// Logout button clears storage
document.getElementById("logoutBtn").addEventListener("click", () => {
    localStorage.clear();
    sessionStorage.clear();
    alert("You have been logged out.");
    window.location.href = "login.html"; // unified redirect
});