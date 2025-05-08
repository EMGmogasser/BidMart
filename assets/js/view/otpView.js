const View = (() => {
  const elements = {
    inputs: document.querySelectorAll(".otp-input input"),
    timerDisplay: document.getElementById("timer"),
    resendLink: document.querySelector(".resend-link"),
    verifyBtn: document.getElementById("verify"),
    lastInput: document.querySelectorAll(".otp-input input").length
      ? document.querySelectorAll(".otp-input input")[
          document.querySelectorAll(".otp-input input").length - 1
        ]
      : null,
  };

  const clearInputs = () =>
    elements.inputs.forEach((input) => (input.value = ""));

  const updateTimerDisplay = (time) => {
    if (time <= 0) {
      elements.timerDisplay.textContent = "";
    } else {
      const minutes = Math.floor(time / 60);
      const seconds = time % 60;
      elements.timerDisplay.textContent = `(${minutes}:${seconds
        .toString()
        .padStart(2, "0")})`;
    }
  };

  const disableResendLink = (disabled) => {
    elements.resendLink.disabled = disabled;
  };

  const showAlert = (title, text, icon) => {
    swal({
      title,
      text,
      icon,
      buttons: false,
    });
  };

  return {
    elements,
    clearInputs,
    updateTimerDisplay,
    disableResendLink,
    showAlert,
  };
})();

export default View;
