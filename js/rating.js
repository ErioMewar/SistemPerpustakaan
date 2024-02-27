document.addEventListener("DOMContentLoaded", function () {
  const ratingInputs = document.querySelectorAll(".rating input");
  const ratingLabels = document.querySelectorAll(".rating label");
  let isClicked = false;
  let storedRating = sessionStorage.getItem("storedRating");

  ratingLabels.forEach((label, index) => {
    label.addEventListener("mouseenter", () => !isClicked && highlightStars(index));
    label.addEventListener("mouseleave", () => !isClicked && resetStars());
    label.addEventListener("click", () => {
      isClicked = true;
      storedRating = index + 1;
      sessionStorage.setItem("storedRating", storedRating);
      setRating(storedRating);
    });
  });

  // Function to highlight stars up to the given index
  function highlightStars(index) {
    resetStars();
    ratingLabels.forEach((label, i) => i <= index && (label.style.color = "#fbff00"));
  }

  // Function to reset all stars to default color
  function resetStars() {
    ratingLabels.forEach((label) => (label.style.color = "#444444"));
  }

  // Function to set rating based on stored value
  function setRating(rating) {
    resetStars();
    ratingInputs[rating - 1].checked = true;
    highlightStars(rating - 1);
  }

  // Clear sessionStorage on page refresh or unload
  window.addEventListener("beforeunload", function () {
    sessionStorage.clear();
  });

  // Check if there's a storedRating, then set the rating
  if (storedRating) {
    setRating(storedRating);
  } else {
    resetStars(); // Reset stars to default if no stored rating
  }

  // Validate before submitting the form
  const form = document.querySelector("form");
  form.addEventListener("submit", function (event) {
    if (!isClicked && event.submitter.getAttribute('name') !== 'cancel') {
      alert("Berikan rating sebelum mengirim ulasan.");
      event.preventDefault();
    }
  });
});
