"use strict";

export const Model = {
  inputFields: {
    name: document.querySelector("input#username"),
    email: document.querySelector("input#email"),
    phone: document.querySelector("input#phone"),
    password: document.querySelector("input#password"),
    confirmPassword: document.querySelector("input#confirm_password"),
  },

  countryCode: "+20",

  setCountryCode(code) {
    Model.countryCode = code;
  },

  inputStatus: {
    name: document.querySelector("div.status-messages#username"),
    email: document.querySelector("div.status-messages#email"),
    phone: document.querySelector("div.status-messages#phone"),
    password: document.querySelector("div.status-messages#password"),
    confirmPassword: document.querySelector(
      "div.status-messages#confirm_password"
    ),
  },

  inputRules: {
    name: [
      { regex: /^[A-Za-z]/, id: "start", valid: false },
      {
        regex: /^[A-Za-z0-9_ ]{3,}$/,
        id: "length",
        valid: false,
      },
      {
        regex: /^[A-Za-z0-9_ ]+$/,
        id: "letters",
        valid: false,
      },
    ],
    email: [
      {
        regex: /^[a-zA-Z0-9._%+-]{6,}@(gmail\.com|outlook\.com|icloud\.com)$/,
        id: "email",
        valid: false,
      },
    ],
    phone: [{ regex: /^0\d{10}$/, id: "phone", valid: false }],
    password: [
      { regex: /[!@#$%^&*(),.?":{}|<>]/, id: "special", valid: false },
      { regex: /^(?=.*[a-z])(?=.*[A-Z]).*$/, id: "upper_lower", valid: false },
      { regex: /.{8,}/, id: "eight_letters", valid: false },
    ],
  },

  validateField(key, value) {
    const rules = this.inputRules[key] || [];
    if (!value) {
      for (const rule of rules) {
        rule.valid = false;
      }
      return;
    }
    for (const rule of rules) {
      if (rule.regex.test(value)) rule.valid = true;
      else rule.valid = false;
    }
  },
};
