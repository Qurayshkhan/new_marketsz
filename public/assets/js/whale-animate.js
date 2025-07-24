const whale = document.getElementById("whale");
const float = document.querySelector(".animate-float");
let currentScroll = window.scrollY;

window.onscroll = () => {
    if (!whale || !float) return;
    const y = window.scrollY;
    const obj = {
        scrollY: y,
        currentScroll,
    };
    if (y > currentScroll) {
        whale.firstElementChild.firstElementChild.classList.remove(
            "flip-horizontal"
        );
        float.firstElementChild.classList.remove("flip-horizontal");
        currentScroll = y;
    }
    if (y < currentScroll) {
        whale.firstElementChild.firstElementChild.classList.add("flip-horizontal");
        float.firstElementChild.classList.add("flip-horizontal");
        currentScroll = y;
    }
    if (y > 600) {
        whale.style.transform = `translateX(-${(parseInt(window.scrollY) - 700) * 0.75
            }px)`;
    }
    if (y > 4000) {
        float.parentElement.style.transform = `translateX(${(parseInt(window.scrollY) - 4000) * 0.75
            }px)`;
    }
    console.log(obj);
};
