function sendInput(key, code, up)
{
    const game = document.querySelector("ruffle-embed");
    if (!game)
        game = document.getElementById("SBURBgameDiv");
    if (!game) return;

    game.focus();
    game.dispatchEvent(new KeyboardEvent(up ? "keyup" : "keydown", {
        "key": key, "code": key, "keyCode": code, bubbles: true
    }));
}

function registerButton(id, key, code)
{
    const btn = document.getElementById(id);
    btn.addEventListener("pointerdown", () => sendInput(key, code, false));
    btn.addEventListener("pointerup", () => sendInput(key, code, true));
}

registerButton("gamepad-up", "ArrowUp", 38);
registerButton("gamepad-down", "ArrowDown", 40);
registerButton("gamepad-left", "ArrowLeft", 37);
registerButton("gamepad-right", "ArrowRight", 39);
registerButton("gamepad-space", "Space", 32);