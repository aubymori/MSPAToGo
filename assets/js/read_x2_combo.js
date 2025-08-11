document.addEventListener("click", (event) => 
{
    let clicked = event.target;
    let isTiltedArrow = false;
    for (const link of document.querySelectorAll(".tilted-arrow"))
    {
        if (link.contains(clicked))
        {
            isTiltedArrow = true;
            break;
        }
    }
    if (!isTiltedArrow)
        return;

    window.scrollTo(0, 0);
    if (!document.body.classList.contains("desktop"))
        document.getElementById("content").classList.toggle("text-2");
});