## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2025-01-20 - Symbol-Only Buttons and Keyboard-Hidden Inputs
**Learning:** Raw text symbols like `+` or `-` in buttons cause screen readers to announce confusing literal names (e.g., "plus", "dash"). Furthermore, form inputs (like checkboxes) styled with `opacity: 0` inside `group-hover` containers become completely inaccessible to keyboard traversal unless explicitly given a `focus-visible:opacity-100` style, trapping or bypassing focus unexpectedly.
**Action:** Always wrap raw text symbols inside buttons with `<span aria-hidden="true">` and provide a semantic `aria-label` on the parent `<button>`. For inputs hidden behind hover effects, always add a matching focus visibility state like `focus-visible:opacity-100` so keyboard users can discover and interact with them.
