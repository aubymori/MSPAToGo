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

function centerToast(toastEl)
{
    let docWidth = document.documentElement.clientWidth;
    let toastWidth = toastEl.clientWidth;
    let left = (docWidth - toastWidth) / 2;
    toastEl.style.left = `${left}px`;
}

function fadeOutToast(toastEl)
{
    toastEl.classList.add("fading-out");
    toastEl.addEventListener("transitionend", function()
    {
        toastEl.remove();
    });
}

function showToast(text)
{
    const TOAST_MARGIN = 8;

    let toast = document.createElement("div");
    toast.className = "toast";
    toast.innerText = text;

    let top = 0;
    let currentToasts = document.querySelectorAll(".toast");
    for (let currentToast of currentToasts)
    {
        let rc = currentToast.getBoundingClientRect();
        if (rc.bottom > top)
            top = rc.bottom;
    }
    top += TOAST_MARGIN;
    toast.style.top = `${top}px`;

    document.body.appendChild(toast);
    centerToast(toast);

    toast.addEventListener("click", () => fadeOutToast(toast));
    setTimeout(() => fadeOutToast(toast), 2000);
}

function showMessageBox()
{
    document.documentElement.classList.add("modal-open", "message-box-open");
}

function showMessageBoxWithText(text)
{
    document.getElementById("message-box-content").innerText = text;
    showMessageBox();
}

window.addEventListener("resize", function()
{
    for (let toast of document.querySelectorAll(".toast"))
    {
        centerToast(toast);
    }
});

document.addEventListener("click", (event) =>
{
    let clicked = event.target;

    switch (clicked.id)
    {
        case "menu-background":
        case "menu-background-2":
        case "message-box-dismiss":
        {
            let doc = document.documentElement;
            if (doc.classList.contains("message-box-open") && doc.classList.contains("options-menu-open"))
            {
                doc.classList.remove("message-box-open");
            }
            else
            {
                doc.classList.remove("modal-open", "message-box-open", "menu-open", "options-menu-open");
            }
            
            break;
        }
        case "options-link":
            document.documentElement.classList.add("modal-open", "options-menu-open");
            break;
        case "auto-save":
            setCookie("autosave", "true");
            showToast("Auto-Save enabled!");
            // fall-thru
        case "save-game":
        {
            let content = document.getElementById("content");
            setCookie("s_cookie", content.dataset.s);
            setCookie("p_cookie", content.dataset.p);
            showToast("Game saved!");
            break;
        }
        case "load-game":
        {
            let s = getCookie("s_cookie");
            let p = getCookie("p_cookie");
            if (!s || !p)
            {
                showToast("You did not save your game!");
                break;
            }
            location.href = `/read/${s}/${p}`;
            break;
        }
        case "delete-game-data":
            deleteCookie("s_cookie");
            deleteCookie("p_cookie");
            deleteCookie("autosave");
            showToast("Game data deleted!");
            break;
        case "save-game-help":
            showMessageBoxWithText(
                `If you have cookies enabled, you can save your spot in the story. Click "Save Game", then
                when you return to the site, click "Load Game" to return to where you were.`.replace("\n", " ")
            );
            break;
        case "auto-save-help":
            showMessageBoxWithText(`If you have cookies enabled, click this to automatically save your spot in the story wherever you go! When you return to the site later, it will automatically take you to where you left off. Pretty neat!

If this mode ever gets annoying though, remember you can just delete the cookies by clicking "Delete Game Data".`)
    }

    let menuButton = document.getElementById("menu-button")
    if (menuButton && menuButton.contains(clicked))
    {
        document.documentElement.classList.add("modal-open", "menu-open");
    }

    if (clicked.classList.contains("log-show")
    || clicked.classList.contains("log-hide"))
    {
        clicked.parentElement.classList.toggle("open");
    }
});

// Banner tooltips for scratch interlude
const banner = document.getElementById("banner");

let imgtipEl = null;

function doImgtipAtPoint(x, y, img)
{
    const OFFSET_X = 20;
    const OFFSET_Y = 30;

    if (!imgtipEl)
    {
        imgtipEl = document.createElement("img");
        imgtipEl.id = "imgtip";
        imgtipEl.src = img;
        document.body.append(imgtipEl);
        imgtipEl.onload = function()
        {
            doImgtipAtPoint(x, y, img);
        }
        return;
    }

    let w = imgtipEl.width;
    let h = imgtipEl.height;

    let dw = document.documentElement.clientWidth;

    if (x + w + OFFSET_X > dw)
    {
        x -= w + OFFSET_X;
    }
    else
    {
        x += OFFSET_X;
    }
    y += OFFSET_Y;

    imgtipEl.style.left = `${x}px`;
    imgtipEl.style.top = `${y}px`;
    imgtipEl.hidden = false;
}

if (banner)
{
    // Tooltip for desktop
    if (document.body.classList.contains("desktop"))
    {
        // We only need to handle image tooltips. Desktop browsers will already
        // show title attr as a tooltip.
        if (banner.dataset.imgTooltip)
        {
            let bannerImg = banner.dataset.imgTooltip;
            banner.addEventListener("mouseover", (event) => 
            {
                doImgtipAtPoint(event.pageX, event.pageY, bannerImg);
            });
            banner.addEventListener("mousemove", (event) => 
            {
                doImgtipAtPoint(event.pageX, event.pageY, bannerImg);
            });
            banner.addEventListener("mouseleave", (event) => 
            {
                if (imgtipEl)
                    imgtipEl.hidden = true;
            });
        }
    }
    // Modal for mobile
    else
    {
        banner.addEventListener("click", (event) =>
        {
            if (!banner.getAttribute("title") && !banner.getAttribute("data-img-tooltip"))
                return null;

            if (banner.getAttribute("title"))
            {
                showMessageBoxWithText(banner.getAttribute("title"));
            }
            else
            {
                let img = document.createElement("img");
                img.src = banner.dataset.imgTooltip;
                let msgBoxContent = document.getElementById("message-box-content");
                msgBoxContent.innerHTML = "";
                msgBoxContent.appendChild(img);
                showMessageBox();
            }
        });
    }
}