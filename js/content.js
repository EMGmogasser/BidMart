// Define the API URL
const API_URL = "https://hk.herova.net/InPageApi/pageContent-api.php";

// Function to clean and parse the IMAGE field
function parseImageJSON(imageString) {
    try {
        // Remove the outer array brackets and unescape quotes
        const cleanedString = imageString
            .replace(/^\[|\]$/g, '') // Remove square brackets
            .replace(/\\\"/g, '"')   // Unescape quotes
            .replace(/""/g, '"')     // Remove double quotes that are incorrectly wrapped around the URLs
            .replace(/([a-zA-Z0-9]+)(?=:)/g, (match) => {
                // Only add quotes around keys if they are valid keys, not URLs
                if (!match.startsWith("https://") && !match.startsWith("http://")) {
                    return `"${match}"`;
                }
                return match; // Leave URLs as they are
            })
            // Remove any incorrect quotes after https://
            .replace(/"https":\/\//g, 'https://')
            .replace(/"http":\/\//g, 'http://')
            .replace(/""/g, '"')    // Ensure there are no extra quotes left
            .replace(/(["'])\1/g, '$1') // Fix case where there are two identical quotes like "" -> "
            .replace(/"https":\/\/"/g, 'https://') // Remove any extra quotes around https://
            .replace(/"http":\/\/"/g, 'http://'); // Remove any extra quotes around http://

        // Manually fix the structure to make it valid JSON
        const jsonString = `{${cleanedString}}`;
        
        //console.log("Corrected JSON String: ", jsonString);

        // Parse the corrected string into JSON
        return JSON.parse(jsonString);
    } catch (error) {
        console.error("Error parsing IMAGE field:", error);
        return null; // Return null if parsing fails
    }
}

// Function to update the HTML dynamically
function updateHTML(data) {
    data.forEach(item => {
        const imageField = parseImageJSON(item.IMAGE);

        switch (item.SECTION_NAME) {
            case "Cover":
                // Update the cover section
                document.querySelector(".cover h1").textContent = item.CONTENT;
                if (imageField?.image) {
                    document.querySelector(".cover img").src = imageField.image;
                }
                break;

            case "Ads":
                // Update the ads section
                if (imageField?.background) {
                    document.querySelector(".ads").style.backgroundImage = `url(${imageField.background})`;
                }
                break;

            case "Apply":
                // Update the apply section
                document.querySelector(".apply h2").textContent = item.CONTENT;
                document.querySelector(".apply p").innerHTML = item.SUB_TITLE?.replace("\r\n", "<br />") || "";
                if (imageField?.background) {
                    document.querySelector(".apply").style.backgroundImage = `url(${imageField.background})`;
                }
                break;

            case "Parteners":
                // Update the clients section
                document.querySelector(".clients h2").textContent = item.CONTENT;
                document.querySelector(".clients p").textContent = item.SUB_TITLE;

                // Update client images
                const clientList = document.querySelector(".clients-list");
                clientList.innerHTML = ""; // Clear existing images
                if (imageField) {
                    for (const key in imageField) {
                        const img = document.createElement("img");
                        img.src = imageField[key];
                        img.alt = key;
                        clientList.appendChild(img);
                    }
                }
                break;

            default:
                console.warn(`Unhandled section: ${item.SECTION_NAME}`);
        }
    });
}

// Fetch the API data
async function fetchData() {
    try {
        const response = await fetch(API_URL);
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        const jsonResponse = await response.json();

        if (jsonResponse.status === "success") {
            //console.log("API Response: ", JSON.stringify(jsonResponse));
            updateHTML(jsonResponse.data);
        } else {
            console.error("Error in API response:", jsonResponse.message);
        }
    } catch (error) {
        console.error("Error fetching data:", error);
    }
}

// Call the fetchData function when the page loads
document.addEventListener("DOMContentLoaded", fetchData);
