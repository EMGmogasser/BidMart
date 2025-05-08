const Model = (() => {
  let resendTimer = 10;
  let timerId = null;
  let canResend = true;
  const otpResponse = "0000";

  const state = {
    resendTimer,
    timerId,
    canResend,
    otpResponse,
  };

  const setResendTimer = (value) => (state.resendTimer = value);
  const setTimerId = (id) => (state.timerId = id);
  const setCanResend = (value) => (state.canResend = value);

  return {
    state,
    setResendTimer,
    setTimerId,
    setCanResend,
  };
})();

export default Model;
