/* shared-nav.js — included by all customer-facing pages */
(function(){
  // Update cart badge
  function updateBadge(){
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const total = cart.reduce((s,i) => s + (i.qty||1), 0);
    document.querySelectorAll('.cart-badge').forEach(el => el.textContent = total);
  }
  updateBadge();

  // Auth-aware nav button
  const user = JSON.parse(localStorage.getItem('user')) || {};
  const btn = document.getElementById('navAuthBtn');
  const mBtn = document.getElementById('mobileAuthLink');
  if(btn){
    if(user.is_admin){
      btn.textContent = 'Admin Panel';
      btn.onclick = () => location.href = 'admin/dashboard.html';
    } else if(user.user_id){
      btn.textContent = 'My Account';
      btn.onclick = () => location.href = 'customer_dashboard.html';
    } else {
      btn.textContent = 'Login';
      btn.onclick = () => location.href = 'login.html';
    }
  }
  if(mBtn){
    if(user.is_admin){ mBtn.textContent = 'Admin Panel'; mBtn.href = 'admin/dashboard.html'; }
    else if(user.user_id){ mBtn.textContent = 'My Account'; mBtn.href = 'customer_dashboard.html'; }
  }

  // Mobile menu toggle
  window.toggleMobileMenu = function(){
    const m = document.getElementById('mobileMenu');
    if(m) m.classList.toggle('open');
  };

  // Toast utility
  window.showToast = function(msg){
    let t = document.getElementById('siteToast');
    if(!t){ t = document.createElement('div'); t.id = 'siteToast'; t.className = 'toast'; document.body.appendChild(t); }
    t.textContent = msg;
    t.classList.add('show');
    clearTimeout(t._tid);
    t._tid = setTimeout(() => t.classList.remove('show'), 2600);
  };

  // Re-export updateBadge globally
  window.updateCartBadge = updateBadge;
})();
