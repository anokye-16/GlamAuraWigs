# GlamAura Wigs — Manual Test Plan & Results
## Assignment 7: Integration, Testing, and Evaluation

---

## Test Environment
- **Browser:** Google Chrome / Mozilla Firefox
- **Local Server:** XAMPP (Apache + MySQL) OR standalone HTML (localStorage mode)
- **Pass Rate: 13/13 (100%)**

---

## Test Cases

| TC | Module | Feature | Steps | Expected | Actual Result | Status |
|----|--------|---------|-------|----------|---------------|--------|
| TC-01 | Homepage | Page Load | Open Home.html | Hero, products, navbar render | All sections loaded correctly | PASS ✅ |
| TC-02 | Shop | Search & Filter | Search "Blonde"; filter "Curly" | Matching products shown | Search returned 2; filter showed 6 curly | PASS ✅ |
| TC-03 | Cart | Add to Cart + Persist | Add item, refresh page | Badge updates; item stays | Toast appeared; persisted after refresh | PASS ✅ |
| TC-04 | Cart | Length Pricing | Change length 12" to 18" in cart | Price × 1.7 multiplier | GH₵ 1,500 → GH₵ 2,550 | PASS ✅ |
| TC-05 | Checkout | VAT + Total Calc | Add item, go to checkout | Sub + 12.5% VAT + delivery = total | 2,550 + 318.75 + 100 = 2,968.75 ✅ | PASS ✅ |
| TC-06 | Checkout | Form Validation | Click Place Order with empty form | Error shown; order blocked | Error message displayed correctly | PASS ✅ |
| TC-07 | Confirmation | Order Placement | Fill form, place order | Confirm page with unique order ID | GA-XXXX shown with 5-step tracker | PASS ✅ |
| TC-08 | Auth | Sign Up + Login | Create account; login with it | Dashboard loads with user's name | Account saved; dashboard showed name | PASS ✅ |
| TC-09 | Auth | Admin Login | Login admin@glamaura.com / admin123 | Admin dashboard loads | All 6 modules accessible | PASS ✅ |
| TC-10 | Dashboard | Order History | Place order; view customer dashboard | Order in My Orders table | Order listed with correct total/status | PASS ✅ |
| TC-11 | CRM | Customer DB + Feedback | Open backend_system.html CRM panels | Customer list and feedback table | All 3 CRM sub-panels rendered | PASS ✅ |
| TC-12 | Finance | Invoice Generation | Click + Generate Invoice | New invoice row added to table | INV-2026-0285 generated; toast shown | PASS ✅ |
| TC-13 | Supply Chain | Flow + Suppliers | Open Supply Chain panels | Flow diagram + supplier directory | All panels rendered with correct data | PASS ✅ |

---

**Total: 13/13 PASSED — 100% Pass Rate**

See integration_report.html for the full visual test report.
