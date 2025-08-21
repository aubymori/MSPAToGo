function isBadElement(el)
{
    return el.nodeName == "INPUT" || el.isContentEditable // don't want to mess up user text input
    || el.nodeName == "CANVAS" // openbound
    || el.nodeName == "EMBED"; // flashes (walkarounds)
}

document.addEventListener("keydown", (e) =>
{
    if (isBadElement(e.target))
        return;

    // Space to toggle logs
    if (e.key == " ")
    {
        let button = document.querySelector(".read-log.open .log-hide, .read-log:not(.open) .log-show");
        if (button)
        {
            e.preventDefault();
            button.click();
        }
    }
});

document.addEventListener("keyup", (e) =>
{
    if (isBadElement(e.target))
        return;

    // Left to go back
    if (e.key == "ArrowLeft")
    {
        let link = document.getElementById("go-back");
        if (link)
        {
            e.preventDefault();
            link.click();
        }
    }
    // Right for next page
    else if (e.key == "ArrowRight")
    {
        let link = null;
        let links = document.querySelectorAll(".command > a");

        // If one of the pages that leads to a Terezi password page,
        // just go to the next regular page.
        if (links.length == 2 && links[0].innerText == "[???????]")
        {
            link = links[1];
        }
        // Else, only do on pages with one link. Some old MSPA pages
        // have multiple actual options.
        else if (links.length == 1)
        {
            link = links[0];
        }

        if (link)
        {
            e.preventDefault();
            link.click();
        }
    }
});