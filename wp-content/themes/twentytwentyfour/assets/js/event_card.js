const button = document.getElementById("card_button_wrapper");

// button.addEventListener("click", toggleclass);

function animator(unique_id){

    const wrapperDiv=document.querySelector('#'+unique_id );

    toggleclass(wrapperDiv);
}

function toggleclass(wrapperDiv) {
    // const wrapperDiv = document.querySelector("#featured_events" + unique_id );
    console.log(wrapperDiv);

    const card_button = wrapperDiv.querySelector(".card_button");
    card_button.disabled = true;
    const card_content = wrapperDiv.querySelector(".card_content");

    card_button.classList.toggle("rotate_45");

    card_content.classList.add("card_content_animation");

    const content_front = card_content.querySelector(".content_front")
    const content_back = card_content.querySelector(".content_back")

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
        card_button.disabled = false;
    }, 2000)
    card_button.disabled = false;
}

document.addEventListener("DOMContentLoaded", function () {
    console.log('Document is ready.');
    search_events();
});


function searchAjax() {
    const search_box = document.querySelector("#search_box");
    
    if (search_box.value.length > 3 || search_box.value.length == '') {
        search_events();
        
    }
}

function search_events() {
    const search_box = document.querySelector("#search_box");
    const searched_events = document.querySelector(".searched_events");
    jQuery.ajax({
        url: ajaxurl,
        method: 'get',
        data: {
            action: 'filter_events',
            search: search_box.value,
        },
        success: function (response) {
            const jsonResponse = JSON.parse(response);
            searched_events.innerHTML = '';
            searched_events.innerHTML = jsonResponse;
        },
        error: function (error) {
            console.log('AJAX error:', error);
        }
    });
}