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

// Need to calculate height of tilted arrow to style it properly
// Can't do that with CSS
let tiltedArrow;
if (tiltedArrow = document.querySelector(".tilted-arrow-link"))
{
    let tiltedArrowHeight = Math.round(tiltedArrow.getBoundingClientRect().height);
    let arrowHeight = Math.round(tiltedArrow.parentElement.getBoundingClientRect().height);
    let arrowPadding = tiltedArrowHeight - arrowHeight;

    document.documentElement.style.setProperty(
        "--arrow-padding",
        arrowPadding + "px");
}