
// Unified dynamic navbar rendering
function getUser() {
  try {
    const stored = localStorage.getItem('user');
    if (stored) {
      return JSON.parse(stored);
    }
  } catch {
    // ignore parse errors
  }

  const fullName = localStorage.getItem('customer_name');
  const email = localStorage.getItem('customer_email');
  const id = localStorage.getItem('customer_id');
  if (fullName || email) {
    return {
      user_id: id || 'customer',
      full_name: fullName || '',
      email: email || '',
      is_admin: false
    };
  }

  return null;
}

function getPage() {
  // Returns the current page filename (e.g., Shop.html)
  return window.location.pathname.split('/').pop();
}

function renderNavbar() {
  const user = getUser();
  const page = getPage();
  const navbar = document.getElementById('main-navbar');
  if (!navbar) return;

  // Main navigation links
  const links = [
    { href: 'Home.html', label: 'Home' },
    { href: 'Shop.html', label: 'Shop' },
    { href: 'Collection.html', label: 'Collection' },
    { href: 'Contact.html', label: 'Contact' },
    { href: 'cart.html', label: 'Cart' }
  ];

  // Auth/user links
  let authLinks = '';
  if (user && user.user_id) {
    // If admin, show admin dashboard
    if (user.is_admin) {
      authLinks = `<a href="admin_dashboard.html">Admin</a> <a href="#" onclick="logout()">Logout</a>`;
    } else {
      authLinks = `
        <a href="customer_dashboard.html">Dashboard</a>
        <a href="profile.html">Profile</a>
        <a href="#" onclick="logout()">Logout</a>
      `;
    }
  } else {
    authLinks = `<a href="login.html">Login</a>`;
  }

  // Build HTML
  let html = '';
  links.forEach(link => {
    const active = page === link.href ? 'style="color:#ff4fa3;font-weight:bold"' : '';
    html += `<a href="${link.href}" ${active}>${link.label}</a>`;
  });
  html += `<span id="auth-links">${authLinks}</span>`;
  navbar.innerHTML = html;
}

function logout() {
  localStorage.removeItem('user');
  localStorage.removeItem('customer_name');
  localStorage.removeItem('customer_email');
  localStorage.removeItem('customer_id');
  fetch('logout.php').then(() => {
    window.location.href = 'login.html';
  });
}

document.addEventListener('DOMContentLoaded', renderNavbar);
if (document.readyState === 'interactive' || document.readyState === 'complete') {
  renderNavbar();
}
