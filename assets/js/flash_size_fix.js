// Make flashes fit to window
function fixFlashSizes()
{
    for (const flash of document.querySelectorAll("embed, ruffle-embed, iframe"))
    {
        // Reverse since we calculate by width and not height
        if (!flash.aspectRatio)
            flash.aspectRatio = flash.height / flash.width;

        let parentWidth = flash.parentElement.clientWidth;
        flash.width = parentWidth;
        flash.height = parentWidth * flash.aspectRatio;
    }
}

window.addEventListener("load", fixFlashSizes);
window.addEventListener("resize", fixFlashSizes);