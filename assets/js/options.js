document.addEventListener("input", (e) =>
{
    let input = e.target;
    if (input.nodeName == "INPUT" && input.type == "range")
    {
        let valueNames = input.dataset.valueNames.split(",");
        let valueName = valueNames[input.value];
        document.getElementById(input.id + "-value").innerText = valueName;
    }
});