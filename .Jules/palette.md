## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2026-10-10 - Keyboard accessibility for hidden hover elements
**Learning:** Interactive elements revealed only on mouse hover (e.g., using `group-hover:opacity-100`) remain completely hidden and inaccessible to keyboard users navigating via Tab, causing confusion and breaking accessibility.
**Action:** Always pair `group-hover:opacity-100` with `group-focus-within:opacity-100` (or `focus-visible:opacity-100`) and apply clear `focus-visible:ring-2` focus indicators to ensure full keyboard discoverability.
