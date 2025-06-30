function sendInput(key, code, up)
{
    let game = document.getElementById("SBURBgameDiv");
    if (!game)
        game = document.querySelector("ruffle-embed");
    if (!game) return;

    game.focus();
    game.dispatchEvent(new KeyboardEvent(up ? "keyup" : "keydown", {
        "key": key, "code": key, "keyCode": code, bubbles: true
    }));
}

function registerButton(id, key, code)
{
    const btn = document.getElementById(id);
    if (!btn) return;
    btn.addEventListener("pointerdown", () => sendInput(key, code, false));
    document.addEventListener("pointerup", () => sendInput(key, code, true));
}

function registerToggleButton(id, key, code)
{
    const btn = document.getElementById(id);
    if (!btn) return;
    btn.addEventListener("click", (e) => {
        let btn = e.target;
        let toggled = btn.classList.toggle("toggled");
        sendInput(key, code, !toggled);
    });
}

registerButton("gamepad-up", "ArrowUp", 38);
registerButton("gamepad-down", "ArrowDown", 40);
registerButton("gamepad-left", "ArrowLeft", 37);
registerButton("gamepad-right", "ArrowRight", 39);
registerButton("gamepad-space", " ", 32);
registerToggleButton("gamepad-shift", "Shift", 16);

// Sburb's reported mouse position is fucked when we resize the canvas, calculate manually.
function onPointerMove(event)
{
    if (event.target.id == "SBURBStage")
    {
        let canvas = event.target;
        canvas.onmousemove = undefined;
        canvas.removeAttribute("onmousemove");
        
        var totalOffsetX = 0;
        var totalOffsetY = 0;
        var canvasX = 0;
        var canvasY = 0;
        var currentElement = canvas;
        do{
            totalOffsetX += currentElement.offsetLeft;
            totalOffsetY += currentElement.offsetTop;
        }
        while(currentElement = currentElement.offsetParent)
        canvasX = event.pageX - totalOffsetX;
        canvasY = event.pageY - totalOffsetY;
        
        Sburb.Mouse.x = Math.floor(canvasX / canvas.clientWidth * canvas.width);
        Sburb.Mouse.y = Math.floor(canvasY / canvas.clientHeight * canvas.height);
    }
}
document.addEventListener("pointermove", onPointerMove);

// Fix for tapping
document.addEventListener("pointerdown", (event) => {
    if (event.poinertType != "mouse" && event.target.id == "SBURBStage")
    {
        // Mobile doesn't fire pointermove, call manually
        onPointerMove(event);
        Sburb.onMouseDown(event, event.target);
    }
});
document.addEventListener("pointerup", (event) => {
    if (event.poinertType != "mouse" && event.target.id == "SBURBStage")
    {
        // Mobile doesn't fire pointermove, call manually
        onPointerMove(event);
        Sburb.onMouseUp(event, event.target);
    }
});