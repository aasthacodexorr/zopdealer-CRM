const colorDropdownSelection = (button_id, input, e) => {
  const btn = e.target.closest(".color-item");
  if (!btn) return;

  // Remove active state
  e.target.parentNode.parentNode
    .querySelectorAll(".color-item")
    .forEach((i) => i.classList.remove("active"));
  btn.classList.add("active");

  const color_name = btn.dataset.color;
  const hex = btn.dataset.hex;

  const color_button = document.getElementById(button_id);
  const input_field = document.getElementsByName(input);

  if (color_button && input_field) {
    color_button.querySelectorAll(
      ".color-preview .label",
    )[0].textContent = color_name;
    input_field[0].value = color_name;

    if (hex === "gradient") {
      color_button.querySelectorAll(
        ".color-preview .swatch",
      )[0].style.background =
        "conic-gradient(#6A1B9A,#C2185B,#F4511E,#FDD835,#43A047,#1E88E5,#6A1B9A)";
      color_button.querySelectorAll(
        ".color-preview .swatch",
      )[0].style.borderColor = "rgba(0,0,0,.15)";
    } else {
      color_button.querySelectorAll(
        ".color-preview .swatch",
      )[0].style.background = hex;
      color_button.querySelectorAll(
        ".color-preview .swatch",
      )[0].style.borderColor = "rgba(0,0,0,.15)";
    }
  }
};
