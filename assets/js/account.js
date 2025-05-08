function swapping(divNumber) {
    var element1 = document.getElementById("toggle1");
    var element2 = document.getElementById("toggle2");
    var element3 = document.getElementById("toggle3");
    var link1 = document.querySelector('body > div.account > div.swap > a.info');
    var link2 = document.querySelector("body > div.account > div.swap > a.balance");
    var link3 = document.querySelector("body > div.account > div.swap > a.orders");

    if (divNumber === 1 && element1.classList.contains("hidden")) {
        // Show element1, hide others
        element1.classList.remove("hidden");
        element2.classList.add("hidden");
        element3.classList.add("hidden");
        link1.style.color = "#d56b00";
        link2.style.color = "black";
        link3.style.color = "black";
    } else if (divNumber === 2 && element2.classList.contains("hidden")) {
        // Show element2, hide others
        element1.classList.add("hidden");
        element2.classList.remove("hidden");
        element3.classList.add("hidden");
        link1.style.color = "black";
        link2.style.color = "#d56b00";
        link3.style.color = "black";
    } else if (divNumber === 3 && element3.classList.contains("hidden")) {
        // Show element3, hide others
        element1.classList.add("hidden");
        element2.classList.add("hidden");
        element3.classList.remove("hidden");
        link1.style.color = "black";
        link2.style.color = "black";
        link3.style.color = "#d56b00";
    }
}
document.getElementById('copyCode').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default action of the link

    // Get the text content of the link
    const textToCopy = this.textContent;

    // Use the Clipboard API to copy the text
    navigator.clipboard.writeText(textToCopy).then(function() {
        // Show the custom popup
        const popup = document.getElementById('customPopup');
        const copiedText = document.getElementById('copiedText');
        copiedText.textContent = textToCopy; // Set the copied text in the popup

        popup.classList.add('show'); // Show the popup

        // Hide the popup after 3 seconds
        setTimeout(() => {
            popup.classList.remove('show');
            popup.classList.add('hide');

            // Remove the hide class after the animation ends
            setTimeout(() => {
                popup.classList.remove('hide');
            }, 500); // Match the duration of the slideOut animation
        }, 3000); // Popup stays visible for 3 seconds
    }).catch(function(err) {
        console.error('Failed to copy text: ', err);
    });
});
var rulesPopup = document.getElementById("rulesPopup");
var rulesLink = document.getElementById("rulesLink");
var couponPopup = document.getElementById("couponPopup");
var couponLink = document.getElementById("couponLink");
var closeButtons = document.querySelectorAll(".close"); // Select all close buttons

rulesLink.onclick = function(event) {
event.preventDefault();
rulesPopup.style.display = "flex";
};

couponLink.onclick = function(event) {
event.preventDefault();
couponPopup.style.display = "flex";
};

// Loop through all close buttons and attach event listeners
closeButtons.forEach(function(button) {
button.onclick = function() {
rulesPopup.style.display = "none";
couponPopup.style.display = "none";
};
});

// Close popup when clicking outside
window.onclick = function(event) {
if (event.target == rulesPopup) {
rulesPopup.style.display = "none";
}
if (event.target == couponPopup) {
couponPopup.style.display = "none";
}
};
/////////////////////////////
