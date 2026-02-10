document.addEventListener("DOMContentLoaded", function () {

    const slides = [
        {
            title: "Track Your Spending",
            desc: "Stay in control of your expenses with real-time tracking.",
            img: "Stock1.png"
        },
        {
            title: "Set Smart Budgets",
            desc: "Create monthly budgets and never overspend again.",
            img: "Stock2.png"
        },
        {
            title: "Visualise Your Money",
            desc: "Clear charts help you understand where your money goes.",
            img: "Stock3.png"
        },
        {
            title: "Stay Strong with Buff Budgets",
            desc: "Conquer your finances with powerful tracking tools.",
            img: "Stock4.png"
        }
    ];

    let currentSlide = 0;

    const titleEl = document.getElementById("carousel-title");
    const descEl = document.getElementById("carousel-desc");
    const imgEl = document.getElementById("carousel-image");

    function changeSlide() {

        imgEl.style.opacity = 0;
        titleEl.style.opacity = 0;
        descEl.style.opacity = 0;

        setTimeout(() => {

            currentSlide = (currentSlide + 1) % slides.length;

            titleEl.textContent = slides[currentSlide].title;
            descEl.textContent = slides[currentSlide].desc;
            imgEl.src = slides[currentSlide].img;

            imgEl.style.opacity = 1;
            titleEl.style.opacity = 1;
            descEl.style.opacity = 1;

        }, 300);
    }

    setInterval(changeSlide, 4000);

});
