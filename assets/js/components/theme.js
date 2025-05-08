(() => {
  const darkModeSwitch = document.getElementById("dark-mode-switch");
  // Pages to exclude from being stored as previous pages
  const excludePages = ["login.php", "register.php", "otp.php", "reset_pass.php", "reset_password.php"];

  // Get the current page path
  const currentPage = window.location.href.split("/").pop() || "index.php";

  // Get the previous page stored in localStorage
  const previousPage = localStorage.getItem("currentPage");

  // Only store the previous page if it's not in the excluded list
  if (previousPage && !excludePages.includes(previousPage)) {
      localStorage.setItem("previousPage", previousPage);
  }

  // Update the current page in localStorage
  localStorage.setItem("currentPage", currentPage);

  // Debugging: Log the current and previous pages

  // ======= Theme Management =======

  // Get theme from browser or localStorage
  let theme =
    localStorage.getItem("theme") ||
    (window.matchMedia("(prefers-color-scheme: dark)").matches
      ? "dark"
      : "light");
  setTheme(theme);

  // Add event listener for theme toggle
  darkModeSwitch?.addEventListener("change", toggleTheme);

  function toggleTheme() {
    const selectedTheme = darkModeSwitch.checked ? "dark" : "light";
    setTheme(selectedTheme);
    localStorage.setItem("theme", selectedTheme);
  }

  function setTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);
    if (darkModeSwitch) darkModeSwitch.checked = theme === "dark";
  }
})();

