const cartCount = document.getElementById("cartCount");
let cart = 0;

// Add-to-cart buttons
document.querySelectorAll(".add-btn").forEach(button => {
  button.addEventListener("click", () => {
    cart++;
    cartCount.textContent = cart;
    button.textContent = "Added ✓";
    button.style.background = "#ef5b2a";

    setTimeout(() => {
      button.textContent = "Add +";
      button.style.background = "";
    }, 1200);
  });
});

// Favourite buttons
document.querySelectorAll(".heart").forEach(button => {
  button.addEventListener("click", () => {
    button.classList.toggle("liked");
    button.textContent = button.classList.contains("liked") ? "♥" : "♡";
  });
});

// Category filter
const categoryButtons = document.querySelectorAll(".category-card");
const foodCards = document.querySelectorAll(".food-card");

categoryButtons.forEach(button => {
  button.addEventListener("click", () => {
    categoryButtons.forEach(item => item.classList.remove("active"));
    button.classList.add("active");

    const selected = button.dataset.category;

    foodCards.forEach(card => {
      const match = card.dataset.category === selected;
      card.style.display = match || selected === "All" ? "" : "none";
    });
  });
});

// Mobile menu
const menuToggle = document.querySelector(".menu-toggle");
const nav = document.querySelector(".main-nav");

menuToggle.addEventListener("click", () => {
  const opened = nav.classList.toggle("mobile-open");
  nav.style.display = opened ? "flex" : "";
  nav.style.position = opened ? "absolute" : "";
  nav.style.top = opened ? "70px" : "";
  nav.style.left = opened ? "0" : "";
  nav.style.right = opened ? "0" : "";
  nav.style.background = opened ? "#fff" : "";
  nav.style.padding = opened ? "20px" : "";
  nav.style.flexDirection = opened ? "column" : "";
  nav.style.borderBottom = opened ? "1px solid #eee" : "";
});

// Close mobile menu after clicking a link
document.querySelectorAll(".main-nav a").forEach(link => {
  link.addEventListener("click", () => {
    nav.classList.remove("mobile-open");
    if (window.innerWidth <= 800) nav.style.display = "none";
  });
});

const themeToggle = document.getElementById("themeToggle");

themeToggle.addEventListener("click", () => {

    document.body.classList.toggle("dark-mode");

    // Save theme
    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("sanovaTheme", "dark");
    } else {
        localStorage.setItem("sanovaTheme", "light");
    }

});


// Load saved theme
if (localStorage.getItem("sanovaTheme") === "dark") {
    document.body.classList.add("dark-mode");
}
