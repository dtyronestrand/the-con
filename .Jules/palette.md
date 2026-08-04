## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-05-15 - Keyboard Accessibility in Custom Forms
**Learning:** Using `display: none` on native inputs (like checkboxes) completely breaks keyboard accessibility, as focus cannot be applied to hidden elements. Additionally, custom interactive elements relying on `group-focus-within` need to be explicitly focusable (using `tabindex="0"`) and handle standard keyboard interactions (`@keydown.enter`, `@keydown.space.prevent`) to be accessible.
**Action:** When building custom styled checkboxes or interactive elements, use visually hidden CSS (`opacity: 0; position: absolute;`) instead of `display: none`. Always ensure that custom button-like elements have `tabindex="0"` and handle keyboard activation events, and ensure visual focus states are clear for keyboard users.
