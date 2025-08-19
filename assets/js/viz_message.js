/* Prompt new users on whether they want to use VIZ or MSPA page numbers. */

const VIZ_MESSAGE_CONTENT = `
<b>Hey there!</b>
<br>
<p>
    It looks like this is your first time here. Homestuck actually
    has <i>two</i> sets of page numbers. The original MS Paint Adventures
    page numbers are continuous across all the adventures, making
    Homestuck start at page <b>001901</b>. The newer Homestuck.com page
    numbers are separate for each adventure, making Homestuck start at
    page <b>1</b>. Do you wish to use the original MSPA page numbers, or
    do you want the newer page numbers?
</p>
<p>
    <b>Example URL:</b>
    ${document.body.classList.contains("desktop") ? "" : "<br>"}
    <span id="viz-example-url">${location.host}/homestuck/1</span>
</p>
<input type="checkbox" id="message-viz-links">
<label for="message-viz-links">I want to use MSPA page numbers</label>
`;

if (getCookie("viz-links") === undefined)
{
    document.getElementById("message-box-content").innerHTML = VIZ_MESSAGE_CONTENT;

    // Set it prematurely so that if the user exits the dialog without changing the option
    // or refreshes the page it won't show up again
    setCookie("viz-links", "true");

    let checkbox   = document.getElementById("message-viz-links");
    let exampleUrl = document.getElementById("viz-example-url");
    checkbox.addEventListener("input", () =>
    {
        setCookie("viz-links", String(!checkbox.checked));

        // Update example URL
        let url = location.host;
        if (checkbox.checked)
        {
            url += "/read/6/001901";
        }
        else
        {
            url += "/homestuck/1";
        }
        exampleUrl.innerText = url;
    });

    // If user wants MSPA page numbers, reload to update the current page
    document.getElementById("message-box-dismiss").addEventListener("click", () =>
    {
        if (getCookie("viz-links") == "false")
        {
            location.reload();
        }
    });

    showMessageBox();
}