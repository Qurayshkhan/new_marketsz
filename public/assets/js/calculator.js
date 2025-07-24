const cFloat = document.querySelector(".animate-cFloat");
let currentScroll = window.scrollY;

window.onscroll = () => {
    const y = window.scrollY;
    const obj = { y, currentScroll };
    if (y > currentScroll) {
        cFloat.children[0].classList.remove("flip-horizontal");
        currentScroll = y;
    }
    if (y < currentScroll) {
        cFloat.children[0].classList.add("flip-horizontal");
        currentScroll = y;
    }
    if (y > 1600) {
        cFloat.parentElement.style.transform = `translateX(${(parseInt(window.scrollY) - 1600) * 0.75
            }px)`;
    }
    console.log(obj);
};
