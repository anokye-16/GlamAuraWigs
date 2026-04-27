// navbar.js: Handles dynamic navbar links based on login status

function getUser() {
  // Try to get user info from sessionStorage (set after login)
  try {
    return JSON.parse(sessionStorage.getItem('user'));
  } catch {
    return null;
  }
}

function renderNavbarAuthLinks() {
  const user = getUser();
  const authLinks = document.getElementById('auth-links');
  if (!authLinks) return;

  if (user && user.user_id) {
    // Logged in
    authLinks.innerHTML = `
      <a href="customer_dashboard.html">Dashboard</a>
      <a href="profile.html">Profile</a>
      <a href="#" onclick="logout()">Logout</a>
    `;
  } else {
    // Not logged in
    authLinks.innerHTML = `<a href="login.html">Login</a>`;
  }
}

function logout() {
  // Clear session and redirect
  sessionStorage.removeItem('user');
  fetch('logout.php').then(() => {
    window.location.href = 'login.html';
  });
}

document.addEventListener('DOMContentLoaded', renderNavbarAuthLinks);
