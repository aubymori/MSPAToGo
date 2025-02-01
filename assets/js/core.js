function getCookie(name)
{
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(";").shift();
}

function setCookie(name, value)
{
    const date = new Date();
    date.setTime(date.getTime() + (34560000)); // 400 days
    document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/;SameSite=Strict`;
}

function deleteCookie(name)
{
    document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;SameSite=Strict`
}

document.addEventListener("click", (event) =>
{
    let clicked = event.target;

    switch (clicked.id)
    {
        case "menu-background":
            document.documentElement.classList.remove("menu-open");
            document.documentElement.classList.remove("options-menu-open");
            break;
        case "options-link":
            document.documentElement.classList.add("menu-open");
            document.documentElement.classList.add("options-menu-open");
            break;
        case "auto-save":
            setCookie("autosave", "true");
            // fall-thru
        case "save-game":
        {
            let content = document.getElementById("content");
            setCookie("s_cookie", content.dataset.s);
            setCookie("p_cookie", content.dataset.p);
            break;
        }
        case "load-game":
        {
            let s = getCookie("s_cookie");
            let p = getCookie("p_cookie");
            if (!s || !p)
            {
                alert("You did not save your game!");
                break;
            }
            location.href = `/read/${s}/${p}`;
            break;
        }
        case "delete-game-data":
            deleteCookie("s_cookie");
            deleteCookie("p_cookie");
            deleteCookie("autosave");
            break;
    }

    if (document.getElementById("menu-button").contains(clicked))
    {
        document.documentElement.classList.add("menu-open");
    }

    if (clicked.classList.contains("log-show")
    || clicked.classList.contains("log-hide"))
    {
        clicked.parentElement.classList.toggle("open");
    }
});