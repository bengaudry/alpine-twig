/** Inverse le thème dans localStorage */
function toggleStorageTheme() {
  const newTheme = getTheme() === "light" ? "dark" : "light";
  localStorage.setItem("theme", newTheme);

  return newTheme;
}

/** Retourne le thème stocké dans localStorage */
function getTheme() {
  const theme = localStorage.getItem("theme");
  return theme ?? "light";
}


