const form = document.getElementById("registrationForm");
form.addEventListener("submit", function(event) {
    event.preventDefault();
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const mobile = document.getElementById("mobile").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword =document.getElementById("confirmPassword").value;
    const course = document.getElementById("course").value;
    const year = document.getElementById("year").value;
    const gender = document.querySelector('input[name="gender"]:checked');
    const terms = document.getElementById("terms").checked;

    document.getElementById("nameError").textContent = "";
    document.getElementById("emailError").textContent = "";
    document.getElementById("mobileError").textContent = "";
    document.getElementById("passwordError").textContent = "";
    document.getElementById("confirmPasswordError").textContent = "";
    document.getElementById("courseError").textContent = "";
    document.getElementById("yearError").textContent = "";
    document.getElementById("genderError").textContent = "";
    document.getElementById("termsError").textContent = "";

    let valid = true;

    const namePattern = /^[A-Za-z ]+$/;
    const emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
    const mobilePattern = /^[0-9]{10}$/;
    const passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,}$/;

    if (name === "") {
        document.getElementById("nameError").textContent = "Please enter your name.";
        valid = false;
    }
    else if (!namePattern.test(name)) {
        document.getElementById("nameError").textContent = "Name should contain only letters.";
        valid = false;
    }

    if (email === "") {
        document.getElementById("emailError").textContent = "Please enter your email.";
        valid = false;
    }
    else if (!emailPattern.test(email)) {
        document.getElementById("emailError").textContent = "Please enter a valid email address.";
        valid = false;
    }

    if (mobile === "") {
        document.getElementById("mobileError").textContent = "Please enter your mobile number.";
        valid = false;
    }
    else if (!mobilePattern.test(mobile)) {
        document.getElementById("mobileError").textContent = "Mobile number must contain 10 digits.";
        valid = false;
    }

    if (password === "") {
        document.getElementById("passwordError").textContent = "Please enter a password.";
        valid = false;
    }
    else if (!passwordPattern.test(password)) {
        document.getElementById("passwordError").textContent = "Password must be 8 characters with uppercase, lowercase and number.";
        valid = false;
    }

    if (confirmPassword === "") {
        document.getElementById("confirmPasswordError").textContent = "Please confirm your password.";
        valid = false;
    }
    else if (password !== confirmPassword) {
        document.getElementById("confirmPasswordError").textContent = "Passwords do not match.";
        valid = false;
    }

    if (course === "") {
        document.getElementById("courseError").textContent = "Please select a course.";
        valid = false;
    }

    if (year === "") {
        document.getElementById("yearError").textContent = "Please select your year.";
        valid = false;
    }

    if (!gender) {
        document.getElementById("genderError").textContent = "Please select your gender.";
        valid = false;
    }

    if (!terms) {
        document.getElementById("termsError").textContent = "Please accept the terms and conditions.";
        valid = false;
    }

    if (valid) {
        alert("Registration successful!");
        form.reset();
    }
});