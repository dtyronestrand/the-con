## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2026-10-17 - Accessible custom checkboxes and focus states for icon-only actions
**Learning:** Relying on `display: none` for custom checkboxes breaks keyboard accessibility because hidden elements cannot receive focus. Also, using `group-hover:opacity-100` hides actions from keyboard users.
**Action:** Use Tailwind's `sr-only` and `peer` classes on the hidden input, and style a sibling element using `peer-focus-visible` for keyboard focus rings. Always pair `group-hover` with `group-focus-within` (or `focus-within`) so interactive elements are revealed when receiving focus, and always wrap clickable icons in accessible `<button>` tags with `aria-label`s.
