const isDarkBackground = (function() {
    let color = new Color(getComputedStyle(document.documentElement).getPropertyValue("--log-bg"));
    return color.getLightness() < 0.5;
})();

for (const text of document.querySelectorAll(".comic-text span[style]"))
{
    if (!text.style.color)
        continue;

    console.log(text.innerText, text.style.color);
    let textColor = new Color(text.style.color);

    const lightnessExtrema = isDarkBackground ? 0.7 : 0.3;
    const needClampLightness = isDarkBackground
        ? (textColor.getLightness() < lightnessExtrema)
        : (textColor.getLightness() > lightnessExtrema);

    if (needClampLightness)
    {
        textColor = textColor.lightness(lightnessExtrema);
        text.style.color = textColor.toString();
    }

    const saturationExtrema = 0.5;
    const needClampSaturation = (textColor.getSaturation() < saturationExtrema) && textColor.getHue();

    if (needClampSaturation)
    {
        textColor = textColor.saturation(saturationExtrema);
        text.style.color = textColor.toString();
    }
}