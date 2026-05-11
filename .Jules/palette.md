## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2026-05-11 - Accessible Custom Checkboxes and Button Elements
**Learning:** Custom checkboxes often break keyboard accessibility if the native `<input>` is hidden using `display: none`. Custom button elements like `<p role="button">` require explicit keyboard handling (`@keydown.enter`, `@keydown.space.prevent`) and `tabindex="0"` to be properly actionable by keyboard users. Also, hover utility classes like `group-hover` must always be paired with `group-focus-within` to reveal child actions.
**Action:** When styling custom checkboxes, hide the native input visually (`opacity: 0; position: absolute; width: 1px; height: 1px;`) and apply `:focus-visible` to sibling elements. When making non-button elements interactive, ensure they are placed in the tab sequence and have keyboard event handlers.
