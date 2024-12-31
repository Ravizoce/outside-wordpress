const button = document.getElementById("card_button_wrapper");

button.addEventListener("click", toggleclass);

function toggleclass() {
    const card_button = document.querySelector(".card_button");
    const card_content = document.querySelector(".card_content");

    card_button.classList.toggle("rotate_45");

    card_content.classList.add("card_content_animation");

    const content_front = card_content.querySelector(".content_front")
    const content_back = card_content.querySelector(".content_back")

    console.log(content_back.content_front);

    if ((content_back.classList.contains("display_none"))) {
        content_front.classList.toggle("display_none");
        content_front.classList.toggle("content_out");
        setTimeout(() => {
            content_back.classList.toggle("display_none");
            content_back.classList.toggle("content_in");
        }, 1000)

    } else {
        content_back.classList.toggle("display_none");
        content_back.classList.add("content_out");
        setTimeout(() => {
            content_front.classList.add("content_in");
            content_front.classList.toggle("display_none");
        }, 1000)
    }

    setTimeout(() => {
        content_front.classList.remove("content_out");
        content_front.classList.remove("content_in");
        content_back.classList.remove("content_out");
        content_back.classList.remove(" content_in");
    }, 2000)
}