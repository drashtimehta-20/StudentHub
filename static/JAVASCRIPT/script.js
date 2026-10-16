const notification = document.getElementById("notification");
const closeNotification = document.getElementById("closeNotification");
closeNotification.addEventListener("click", function() {
    notification.style.display = "none";
});

const menuButton = document.getElementById("menuButton");

const navbar = document.getElementById("navbar");

menuButton.addEventListener("click", function() {
    navbar.classList.toggle("show");
});

const faqQuestions = document.querySelectorAll(".faq-question");

faqQuestions.forEach(function(question) {
   question.addEventListener("click", function() {
        const answer = question.nextElementSibling;
              answer.classList.toggle("show");
        const symbol = question.querySelector("span");
        if (answer.classList.contains("show")) {
            symbol.textContent = "−";
        }

        else {
            symbol.textContent = "+";
        }
    });
});

const slides = document.querySelectorAll(".slide");
const nextButton = document.getElementById("nextButton");
const previousButton = document.getElementById("previousButton");
let currentSlide = 0;
function showSlide(index) {
    slides.forEach(function(slide) {
        slide.classList.remove("active");
    });

    slides[index].classList.add("active");
}

nextButton.addEventListener("click", function() {
   currentSlide++;
    if (currentSlide >= slides.length) {
        currentSlide = 0;
    }

    showSlide(currentSlide);
});


previousButton.addEventListener("click", function() {
    currentSlide--;
    if (currentSlide < 0) {
        currentSlide = slides.length - 1;
    }
    showSlide(currentSlide);
});